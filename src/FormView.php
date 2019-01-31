<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Form\Field;
use Encore\Admin\FormView\Builder;
use Encore\Admin\FormView\Tools;

class FormView extends Form
{
    /**
     * Create a new form instance.
     *
     * @param $model
     * @param \Closure $callback
     */
    public function __construct($model, Closure $callback = null)
    {
        parent::__construct($model, $callback);
        
        $this->builder = new Builder($this);
    }
    
    /**
     * Generate a edit form.
     *
     * @param $id
     *
     * @return $this
     */
    public function edit($id)
    {
        $this->builder->setMode(Builder::MODE_EDIT);
        $this->builder->setResourceId($id);
        
        $this->setFieldValue($id);
        
        $this->tools(function (Tools $tools) {
            $tools->disableEdit();
        });
        
        return $this;
    }
    
    /**
     * Generate a view form.
     *
     * @param $id
     *
     * @return $this
     */
    public function view($id)
    {
        $this->builder->setMode(Builder::MODE_VIEW);
        $this->builder->setResourceId($id);
        
        $this->setFieldValue($id);
        $this->tools(function (Tools $tools) {
            $tools->disableView();
        });
        
        $this->builder->getFooter()->disableReset();
        $this->builder->getFooter()->disableSubmit();
        $this->builder->getFooter()->disableEditingCheck();
        $this->builder->getFooter()->disableViewCheck();
        
        return $this;
    }
    
    /**
     * Return field for modify
     *
     * @param $column
     * @return Field|null
     */
    public function modifyField($column)
    {
        return $this->getFieldByColumn($column);
    }
    
    /**
     * Remove field
     * @param $column
     * @return bool
     */
    public function deleteFiled($column)
    {
        $fields = $this->builder->fields()->filter(
            function (Field $field) use ($column) {
                if (is_array($field->column())) {
                    return in_array($column, $field->column());
                }
                
                return $field->column() == $column;
            }
        )->all();
        
        if (count($fields)) {
            $key = array_first(array_keys($fields));
            $this->builder->fields()->forget($key);
            return true;
        }
        
        return false;
    }
}