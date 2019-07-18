<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class SwitchField extends Field
{
    protected static $css = [
        '/vendor/laravel-admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/bootstrap-switch/dist/js/bootstrap-switch.min.js',
    ];

    protected $states = [
        'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'primary'],
        'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'default'],
    ];
    
    protected $checkbox = false;
    
    public function checkbox($useCheckboxStyle = true)
    {
        $this->checkbox = $useCheckboxStyle;
        return $this;
    }

    protected $size = 'small';

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function states($states = [])
    {
        foreach (Arr::dot($states) as $key => $state) {
            Arr::set($this->states, $key, $state);
        }

        return $this;
    }

    public function prepare($value)
    {
        if (isset($this->states[$value])) {
            return $this->states[$value]['value'];
        }

        return $value;
    }

    public function render()
    {
        foreach ($this->states as $state => $option) {
            if ($this->value() == $option['value']) {
                $this->value = $state;
                break;
            }
        }

        if ($this->checkbox) {
    
            $this->script = <<<EOT

$('{$this->getElementClassSelector()}.la_checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue switch-checkbox'}).on('ifChanged', function() {
            var state = $(this).prop("checked");
            $(this).closest('.icheckbox_minimal-blue').next().val(state ? 'on' : 'off').change();
        });

EOT;
        } else {
            $this->script = <<<EOT

$('{$this->getElementClassSelector()}.la_checkbox').bootstrapSwitch({
    size:'{$this->size}',
    onText: '{$this->states['on']['text']}',
    offText: '{$this->states['off']['text']}',
    onColor: '{$this->states['on']['color']}',
    offColor: '{$this->states['off']['color']}',
    onSwitchChange: function(event, state) {
        $(event.target).closest('.bootstrap-switch').next().val(state ? 'on' : 'off').change();
    }
});

EOT;
        }

        return parent::render();
    }
}
