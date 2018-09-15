<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class SimpleCheckbox extends Field
{
    protected $view = 'admin::form.simple_checkbox';

    use Field\PlainInput;

    public function render()
    {
        if (isset($this->attributes['readonly'])) {
            $this->attribute('disabled', true);
        }

        $this->script = <<<EOT

        $("#check_{$this->id}").on('change', function() {
            var state = $(this).prop("checked");

            var realInput = $(".{$this->id}");

            if (state) {
                realInput.val('1');
            } else {
                realInput.val('0');
            }
        });
EOT;

        $this->initPlainInput();

        $this
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', old($this->column, $this->value()))
            ->defaultAttribute('class', 'form-control '.$this->getElementClassString());

        $this->addVariables([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);

        return parent::render();
    }
}
