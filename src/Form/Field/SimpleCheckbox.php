<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class SimpleCheckbox extends Field
{
	protected $view = 'admin::form.simple_checkbox';
	
	protected static $css = [
		'/vendor/laravel-admin/AdminLTE/plugins/iCheck/all.css',
	];
	
	protected static $js = [
		'/vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js',
	];
	
	use Field\PlainInput;
	
	public function render()
	{
		if (isset($this->attributes['readonly'])) {
			$this->attribute('disabled', true);
		}
		
		$this
			->defaultAttribute('id', $this->id)
			->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
			->defaultAttribute('value', old($this->column, $this->value()))
			->defaultAttribute('class', 'form-control '.$this->getElementClassString());
		
		$name = $this->elementName ?: $this->formatName($this->column);
		
		$this->script = <<<EOT
		$('{$this->getElementClassSelector()}').iCheck({checkboxClass:'icheckbox_minimal-blue'});
		$('{$this->getElementClassSelector()}').on('ifChanged', function() {
            var state = $(this).prop("checked");
            var realInput = $(this).closest('.simple-checkbox-field').find('input[type = hidden]');

            if (state) {
                realInput.val('1');
            } else {
                realInput.val('0');
            }
        });
EOT;
		
		$this->initPlainInput();
		
		$this->addVariables([
			'prepend' => $this->prepend,
			'append'  => $this->append,
		]);
		
		return parent::render();
	}
}
