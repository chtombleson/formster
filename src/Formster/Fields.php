<?php
namespace Formster;

use Formster\Exception;

class Fields
{
    private $type_map = [
        'text' => 'TextField',
        'textarea' => 'TextareaField',
        'button' => 'ButtonField',
        'checkbox' => 'CheckboxField',
        'email' => 'EmailField',
        'password' => 'PasswordField',
        'radio' => 'RadioField',
        'search' => 'SearchField',
        'select' => 'SelectField',
        'submit' => 'SubmitField',
        'dropdown' => 'SelectField',
        'tel' => 'TelField',
        'telephone' => 'TelField',
        'phone' => 'TelField',
        'uri' => 'UrlField',
        'url' => 'UrlField',
    ];

    protected $fields = [];

    public function get($name=null)
    {
        if ($name) {
            return isset($this->fields[$name]) ? $this->fields[$name] : null;
        }

        return $this->fields;
    }

    public function add($name, $type, $value=null, $options=[])
    {
        if (!in_array($type, array_keys($this->type_map))) {
            throw new Exception(
                'Field not valid, valid types are: ' . implode(', ', array_keys($this->type_map))
            );
        }

        $class = '\Formster\Fields\\' . $this->type_map[$type];

        $this->fields[$name] = new $class(
            $name,
            $value,
            $options
        );

        return $this;
    }

    public function remove($name)
    {
        if (isset($this->fields[$name])) {
            unset($this->fields[$name]);
        }

        return $this;
    }
}

