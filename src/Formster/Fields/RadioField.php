<?php
namespace Formster\Fields;

class RadioField extends AbstractField
{
    protected $type = 'radio';

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

        if (isset($options['values'])) {
            foreach ($options['values'] as $k => $v) {
                $html .= '<input type="' . $this->getType() . '" name="' . $this->getName() . '"';

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

                if ($this->getValue() && $this->getValue() == $k) {
                    $html .= ' checked';
                }

                $html .= '> ' . $v;
            }
        }

        if (isset($options['suffix'])) {
            $html .= $options['suffix'];
        }

        return $html;
    }
}

