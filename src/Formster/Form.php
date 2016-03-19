<?php
namespace Formster;

use Psr\Http\Message\RequestInterface;

class Form
{
    protected $action;
    protected $method;
    protected $enctype;
    protected $id;

    protected $fields;

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
    }

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
        ];
    }

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

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $method = strtolower($method);

        if (!in_array($method, ['get', 'post'])) {
            throw new Exception('Form method must be either get or post');
        }

        $this->method = $method;

        return $this;
    }

    public function getEnctype()
    {
        return $this->enctype;
    }

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

    public function getId()
    {
        return $this->id;
    }

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
     *
     * @return $this
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

        return $this;
    }

    public function removeField($name)
    {
        $this->fields->remove($name);

        return $this;
    }

    public function getField($name)
    {
        return $this->fields->get($name);
    }

    public function renderForm()
    {
        $html  = $this->renderFormOpen();

        foreach ($this->fields->get() as $field) {
            $html .= $field->render();
        }

        $html .= $this->renderFormClose();

        return $html;
    }

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

    public function renderFormClose()
    {
        return '</form>';
    }

    public function renderField($name)
    {
        return $this->fields->get($name)->render();
    }
}

