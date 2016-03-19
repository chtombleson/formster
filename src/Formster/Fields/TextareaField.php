<?php
namespace Formster\Fields;

class TextareaField extends AbstractField
{
    protected $type = 'textarea';

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

        $html .= '<textarea name="' . $this->getName() . '" ';


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

        if ($this->getValue()) {
            $html .= $this->getValue();
        }

        $html .= '</textarea>';

        if (isset($options['suffix'])) {
            $html .= $options['suffix'];
        }

        return $html;
    }
}

