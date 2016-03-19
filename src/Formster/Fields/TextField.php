<?php
namespace Formster\Fields;

class TextField extends AbstractField
{
    protected $type = 'text';

    public function render()
    {
        $options = $this->getOptions();
        $html = '';

        if (isset($options['prefix'])) {
            $html .= $options['prefix'];
        }

        if (isset($options['label'])) {
            $html .= '<label for="' . $this->getName() . '">';

            if (is_bool($options['label'])) {
                $html .= ucwords($this->getName());
            } else if (is_string($options['label'])) {
                $html .= ucwords($options['label']);
            }

            $html .= '</label>';
        }

        $html .= '<input type="' . $this->getType() . '" ';
        $html .= 'name="' . $this->getName() . '" ';

        if ($this->getValue()) {
            $html .= 'value="' . $this->getValue() . '" ';
        }

        if (isset($options['attributes'])) {
            foreach ($options['attributes'] as $attr => $val) {
                switch ($attr) {
                    case 'class':
                        if (is_array($val)) {
                            $html .= 'class="' . implode(' ', $val) . '" ';
                        } else {
                            $html .= 'class="' . $val . '" ';
                        }
                        break;

                    default:
                        $html .= $attr . '="' . $val . '" ';
                }
            }
        }

        $html = rtrim($html) . '>';

        if (isset($options['suffix'])) {
            $html .= $options['suffix'];
        }

        return $html;
    }
}

