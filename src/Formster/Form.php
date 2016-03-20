<?php
namespace Formster;

use Psr\Http\Message\ServerRequestInterface;

class Form
{
    /**
     * @var string Form action
     */
    protected $action;

    /**
     * @var string Form method
     */
    protected $method;

    /**
     * @var string Form enctype
     */
    protected $enctype;

    /**
     * @var string Form id
     */
    protected $id;

    /**
     * @var Formster\Fields
     */
    protected $fields;

    /**
     * @var Formster\Validation
     */
    protected $validation;

    /**
     * @var Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var array Form errors
     */
    protected $errors = [];

    /**
     * @param $method   string Form method
     * @param $action   string Form action
     * @param $enctype  string Form enctype
     * @param $id       string Form id
     */
    public function __construct($method='post', $action=null, $enctype=null, $id=null)
    {
        $this->setMethod($method);

        if ($action) {
            $this->setAction($action);
        }

        if ($enctype) {
            $this->setEnctype($enctype);
        }

        if ($id) {
            $this->setId($id);
        }

        $this->fields = new Fields();
        $this->validation = new Validation();
    }

    /**
     * @return array Form properties
     */
    public function get()
    {
        $fields = $this->fields->get();

        if (!empty($fields)) {
            $fields = array_map(
                function($f) { return $f->get(); },
                $this->fields->get()
            );
        }

        return [
            'action' => $this->action,
            'method' => $this->method,
            'enctype' => $this->enctype,
            'id' => $this->id,
            'fields' => $fields,
            'validation' => $this->validation->get(),
        ];
    }

    /**
     * @param $properties array Form properties
     * @return self
     */
    public function set($properties)
    {
        if (isset($properties['action'])) {
            $this->setAction($properties['action']);
        }

        if (isset($properties['method'])) {
            $this->setMethod($properties['method']);
        }

        if (isset($properties['enctype'])) {
            $this->setEnctype($properties['enctype']);
        }

        if (isset($properties['id'])) {
            $this->setId($properties['id']);
        }

        return $this;
    }

    /**
     * @return string Form action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action string Form action
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string Form method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param $method string Form method
     * @return self
     * @throws Formster\Exception On invalid form method
     */
    public function setMethod($method)
    {
        $method = strtolower($method);

        if (!in_array($method, ['get', 'post'])) {
            throw new Exception('Form method must be either get or post');
        }

        $this->method = $method;

        return $this;
    }

    /**
     * @return string Form enctype
     */
    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * @param $enctype string Form enctype
     * @return self
     * @throws Formster\Exception On invalid enctype
     */
    public function setEnctype($enctype)
    {
        $enctypes = [
            'application/x-www-form-urlencoded',
            'multipart/form-data',
            'text/plain'
        ];

        if (!in_array($enctype, $enctypes)) {
            throw new Exception('Form enctype must be either ' . implode(' or ', $enctypes));
        }

        $this->enctype = $enctype;

        return $this;
    }

    /**
     * @return string Form id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id string Form id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param array $properties
     *  - name: string Input name
     *  - type: string Input type (text, select, radio, checkbox, textarea etc.)
     *  - value: string Value for input
     *  - options: array
     *      - label: string|bool Label name or bool for show
     *      - prefix: string HTML to place before input element
     *      - suffix: string HTML to place after input element
     *      - values: array Values for select, checkbox and radio
     *      - attributes: array
     *          - class: string|array CSS class names
     *          - required: boolean Required input attribute
     *          - min: int Min amount of values
     *          - max: int Max amount of values
     *          - maxlength: int Max length of value
     *  - validate: array Validation filters (not_empty, boolean, email, float, int, ip, mac, url)
     *
     * @return self
     */
    public function addField($properties)
    {
        if (!isset($properties['name']) || !isset($properties['type'])) {
            throw new Exception('Field must have a name and type');
        }

        $value = isset($properties['value']) ? $properties['value'] : null;
        $options = isset($properties['options']) ? $properties['options'] : [];

        $this->fields->add(
            $properties['name'],
            $properties['type'],
            $value,
            $options
        );

        if (isset($properties['validate'])) {
            $this->addValidation($properties['name'], $properties['validate']);
        }

        return $this;
    }

    /**
     * @param $name string Field name
     * @return self
     */
    public function removeField($name)
    {
        $this->fields->remove($name);

        return $this;
    }

    /**
     * @param $name string Field name
     * @return Formster\Fields\AbstractField Field object
     */
    public function getField($name)
    {
        return $this->fields->get($name);
    }

    /**
     * @param $field  string Field name
     * @param $filter string Filter (not_empty, boolean, email, float, int, ip, mac, url)
     * @return self
     */
    public function addValidation($field, $filters)
    {
        foreach ($filters as $filter) {
            $this->validation->add($field, $filter);
        }

        return $this;
    }

    /**
     * @param $request Psr\Http\Message\ServerRequestInterface User request
     * @return self
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        if (!$this->request) {
            return false;
        }

        $this->errors = $this->validation->validate($this->request);

        if (count($this->errors) > 0) {
            return false;
        }

        return true;
    }

    /**
     * @return array Form errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array|null Form data
     */
    public function getData()
    {
        if (!$this->request) {
            return null;
        }

        switch ($this->getMethod()) {
            case 'get':
                return $this->request->getQueryParams();
                break;

            case 'post':
                return $this->request->getParsedBody();
                break;
        }

        return null;
    }

    /**
     * @return string
     */
    public function renderForm()
    {
        $html  = $this->renderFormOpen();

        foreach ($this->fields->get() as $field) {
            $html .= $field->render();
        }

        $html .= $this->renderFormClose();

        return $html;
    }

    /**
     * @return string
     */
    public function renderFormOpen()
    {
        $html  = '<form method="' . strtolower($this->getMethod()) . '" ';

        if ($this->getAction()) {
            $html .= 'action="' . $this->getAction() . '" ';
        }

        if ($this->getEnctype()) {
            $html .= 'enctype="' . $this->getEnctype() . '" ';
        }

        if ($this->getId()) {
            $html .= 'id="' . $this->getId() . '" ';
        }

        $html = rtrim($html) . '>';

        return $html;
    }

    /**
     * @return string
     */
    public function renderFormClose()
    {
        return '</form>';
    }

    /**
     * @param $name string Field name
     * @return string
     */
    public function renderField($name)
    {
        return $this->fields->get($name)->render();
    }
}

