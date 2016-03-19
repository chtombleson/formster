<?php
namespace Formster\Fields;

abstract class AbstractField
{
    protected $name;
    protected $value;
    protected $options;
    protected $type;

    public function __construct($name, $value=null, $options=[])
    {
        $this->name = $name;
        $this->value = $value;
        $this->options = $options;
    }

    public function get()
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'value' => $this->value,
            'options' => $this->options,
        ];
    }

    public function set($properties)
    {
        if (isset($properties['value'])) {
            $this->setValue($properties['value']);
        }

        if (isset($properties['options'])) {
            $this->setOptions($properties['options']);
        }

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    abstract function render();
}

