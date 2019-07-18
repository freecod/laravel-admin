<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;

class Decimal extends Text
{
    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias'      => 'decimal',
        'rightAlign' => true,
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prepend('<i class="fa fa-terminal fa-fw"></i>')
            ->defaultAttribute('style', 'width: 130px');

        $this->initPlainInput();


        $this
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', $this->value())
            ->defaultAttribute('class', 'form-control '.$this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        $this->addVariables([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);

        Admin::script($this->script);

        return view($this->getView(), $this->variables());
    }
}
