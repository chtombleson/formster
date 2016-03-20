<?php
namespace Formster\Fields;

class ButtonField extends AbstractField
{
    protected $type = 'button';

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

        $html .= '<button ';

        if (isset($options['submit']) && $option['submit']) {
            $html .= 'type="submit" ';
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

        $html = rtrim($html) . '>' . $options['value'] . '</button>';

        if (isset($options['suffix'])) {
            $html .= $options['suffix'];
        }

        return $html;
    }
}

