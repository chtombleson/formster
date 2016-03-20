<?php
namespace Formster;

use Psr\Http\Message\ServerRequestInterface;

class Validation
{
    private $filters = [
        'boolean' => FILTER_VALIDATE_BOOLEAN,
        'bool' => FILTER_VALIDATE_BOOLEAN,
        'email' => FILTER_VALIDATE_EMAIL,
        'float' => FILTER_VALIDATE_FLOAT,
        'int' => FILTER_VALIDATE_INT,
        'ip' => FILTER_VALIDATE_IP,
        'mac' => FILTER_VALIDATE_MAC,
        'url' => FILTER_VALIDATE_URL,
        'not_empty' => null,
    ];

    protected $validators = [];

    public function get($field=null)
    {
        if ($field) {
            return isset($validators[$field]) ? $validators[$field] : null;
        }

        return $this->validators;
    }

    public function add($field, $filter)
    {
        if (!in_array($filter, array_keys($this->filters))) {
            throw new Exception('Validation filter: ' . $filter . ' is not supported');
        }

        if (!isset($this->validators[$field])) {
            $this->validator[$field] = [];
        }

        $this->validators[$field][] = $filter;
    }

    public function validate(ServerRequestInterface $request)
    {
        $errors = [];
        $data = $request->getParsedBody();

        foreach ($this->validators as $field => $filters) {
            foreach ($filters as $filter) {
                $valid = false;

                switch ($filter) {
                    case 'not_empty':
                        $valid = !empty($data[$field]);

                        if (!$valid) {
                            if (!isset($errors[$field])) {
                                $errors[$field] = [];
                            }

                            $errors[$field][] = 'is_empty';
                        }
                        break;

                    default:
                        $valid = filter_var($data[$field], $this->filters[$filter]);

                        if (!$valid) {
                            if (!isset($errors[$field])) {
                                $errors[$field] = [];
                            }

                            $errors[$field][] = $filter . '_is_not_valid';
                        }
                }
            }
        }

        return $errors;
    }
}

