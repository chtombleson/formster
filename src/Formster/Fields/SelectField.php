<?php
namespace Formster\Fields;

class SelectField extends AbstractField
{
    protected $type = 'select';

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

        $html .= '<select name="' . $this->getName() . '" ';

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

        if (isset($options['values'])) {
            foreach ($options['values'] as $k => $v) {
                $html .= '<option value="' . $k . '"';

                if ($this->getValue() && $this->getValue() == $k) {
                    $html .= ' selected';
                }

                $html .= '>' . $v . '</option>';
            }
        }

        $html .= '</select>';

        if (isset($options['suffix'])) {
            $html .= $options['suffix'];
        }

        return $html;
    }
}

