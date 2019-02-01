<?php

namespace Encore\Admin\FormView;

use Encore\Admin\Form\Builder as FormBuilder;
use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\Row;

class Builder extends FormBuilder
{
    const MODE_VIEW = 'view';
    
    /**
     * Do initialize.
     */
    public function init()
    {
        parent::init();
        $this->tools = new Tools($this);
    }
    
    /**
     * @param null $slice
     * @return string
     */
    public function getResource($slice = null)
    {
        if ($this->isMode(self::MODE_VIEW)) {
            return $this->form->resource(-1);
        }
        
        return parent::getResource($slice);
    }
    
    /**
     * Get Form action.
     *
     * @return string
     */
    public function getAction()
    {
        if ($this->isMode(self::MODE_VIEW)) {
            // empty form action in view mode
            return '#';
        }
    
        return parent::getAction();
    }
    
    /**
     * @return string
     */
    public function title()
    {
        if ($this->isMode(self::MODE_VIEW)) {
            return trans('admin.view');
        }
    
        return parent::title();
    }
    
    /**
     * Render form.
     *
     * @return string
     */
    public function render()
    {
        if ( !$this->isMode(self::MODE_VIEW)) {
            return parent::render();
        }
        
        $this->removeReservedFields();
        
        $tabObj = $this->form->getTab();
        
        if (!$tabObj->isEmpty()) {
            $script = <<<'SCRIPT'

var hash = document.location.hash;
if (hash) {
    $('.nav-tabs a[href="' + hash + '"]').tab('show');
}

// Change hash for page-reload
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    history.pushState(null,null, e.target.hash);
});

if ($('.has-error').length) {
    $('.has-error').each(function () {
        var tabId = '#'+$(this).closest('.tab-pane').attr('id');
        $('li a[href="'+tabId+'"] i').removeClass('hide');
    });

    var first = $('.has-error:first').closest('.tab-pane').attr('id');
    $('li a[href="#'+first+'"]').tab('show');
}

SCRIPT;
            Admin::script($script);
        }
        
        if ($this->hasRows()) {
            
            foreach ($this->getRows() as $row) {
                /** @var Row $row */
                
                foreach ($row->getFields() as $field) {
                    /** @var Field $field */
                    $field->readOnly();
                }
            }
            
        } else {
            foreach ($this->fields() as $field) {
                /** @var Field $field */
                $field->readOnly();
            }
        }
    
        $data = [
            'form'   => $this,
            'tabObj' => $tabObj,
            'width'  => $this->width,
        ];
        
        return view($this->view, $data)->render();
    }
}