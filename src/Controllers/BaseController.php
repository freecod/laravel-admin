<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\FormView;
use Illuminate\Routing\Controller;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

abstract class BaseController extends Controller
{
	use HasResourceActions;

	/* Eloquent model class */
	protected $class;
	/* resource route base name (for permission check) */
	protected $route;
	/* check user permission and hide buttons */
	protected $permissionCheck = false;

	/* example: Campaigns */
    protected $title;
    /* example: campaign */
    protected $titleOnce;
    /* example: some description */
    protected $description;
    
    /* allow show action */
    protected $allowShow = false;
    
    /* form save buttons */
    protected $formSaveAndView = false;
    protected $formSaveAndCreate = false;
    protected $formSaveAndEdit = true;

    /**
     * @param Grid $grid
     * @return Grid
     */
    abstract protected function gridData(Grid $grid);
	
	/**
	 * @param FormView $form
	 * @param null $id
	 * @return mixed
	 */
    abstract protected function formData(FormView $form, $id = null);

    /**
     * @return Grid
     * @throws \Exception
     */
    protected function grid()
    {
        $grid = new Grid($this->getModel());
        $this->gridData($grid);
    
        if ( !$this->allowShow) {
            $grid->actions(function (Grid\Displayers\Actions $tools) {
                $tools->disableView();
            });
        }
        
        if ($this->permissionCheck) {
            if ( !$this->route) {
                throw new \Exception("You must set in controller property 'route' for permission check");
            }
            $this->permittedButtonGrid($grid, $this->route);
        }

        return $grid;
    }
	
	/**
	 * @param null $id
	 * @return FormView
	 * @throws \Exception
	 */
    protected function form($id = null)
    {
        $form = new FormView($this->getModel());
        
        if ( !$this->formSaveAndView) {
            $form->disableViewCheck();
        }
    
        if ( !$this->formSaveAndEdit) {
            $form->disableEditingCheck();
        }
    
        if ( !$this->formSaveAndCreate) {
            $form->disableCreatingCheck();
        }
    
        if ( !$this->allowShow) {
            $form->tools(function (FormView\Tools $tools) {
                $tools->disableView();
            });
        }
        
        $this->formData($form, $id);

        if ($this->permissionCheck) {
            if ( !$this->route) {
                throw new \Exception("You must set in controller property 'route' for permission check");
            }
            $this->permittedButtonForm($form, $this->route);
        }

        return $form;
    }

    public function index(Content $content)
    {
        return $content
            ->header($this->title)
            ->description($this->description)
            ->body($this->grid());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.view'))
            ->description($this->titleOnce)
            ->body($this->form($id)->view($id));
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description($this->titleOnce)
            ->body($this->form($id)->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.new'))
            ->description($this->titleOnce)
            ->body($this->form());
    }
    
    
    /**
     * Return Eloquent model for current controller
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getModel()
    {
        if ( !$this->class) {
            throw new \Exception("Set in controller property 'model' with Eloquent model class");
        }
        
        return is_string($this->class) ? new $this->class : $this->class;
    }
    
    /**
     * Add default line filter for grid
     *
     * @param Grid $grid
     * @param $fields
     * @param null $title
     */
	protected function addGridFilter(Grid $grid, $fields, $title = null)
    {
        $fields = is_array($fields) ? $fields : [$fields];

        $grid->filter(function (Grid\Filter $filter) use ($fields, $title) {

            $filter->disableIdFilter();

            $filter->where(function ($query) use ($fields) {

                foreach ($fields as $field) {
                    // условие по связи
                    if ($pos = mb_strpos($field, '.')) {

                        $relation = mb_substr($field, 0, $pos);
                        $relField = mb_substr($field, $pos+1);

                        $query->orWhereHas($relation, function ($subQuery) use ($relField) {
                            $subQuery->where($relField, 'like', "%{$this->input}%");
                        });

                    } else {
                        $query->orWhere($field, 'like', "%{$this->input}%");
                    }
                }

            }, $title ?? 'Поиск');
        });
    }

    /**
     * Setup available button in Grid based on permission
     *
     * @param Grid $grid
     * @param string $routeBase
     */
    protected function permittedButtonGrid(Grid $grid, $routeBase)
    {
        $disableButtons = $this->noPermissionButtonMap($routeBase);

        if (key_exists('create', $disableButtons)) {
            $grid->disableCreateButton();
        }

        $grid->actions(function (Grid\Displayers\Actions $tools) use ($disableButtons) {

            foreach ($disableButtons as $key => $can) {

                $method = "disable" . ucfirst($key);
                if (method_exists($tools, $method)) {
                    $tools->{$method}();
                }
            }
        });
    }

    /**
     * Setup available button in Form based on permission
     *
     * @param FormView $form
     * @param string $routeBase
     */
    protected function permittedButtonForm(FormView $form, $routeBase)
    {
        $disableButtons = $this->noPermissionButtonMap($routeBase);

        $form->tools(function (FormView\Tools $tools) use ($disableButtons) {

            foreach ($disableButtons as $key => $can) {

                $method = "disable" . ucfirst($key);
                if (method_exists($tools, $method)) {
                    $tools->{$method}();
                }
            }
        });
    }

    protected function noPermissionButtonMap($routeBase)
    {
    	$fullPermission = \Admin::user()->can("{$routeBase}");
		    
        $permission = [
            'view'   => $fullPermission ? true : \Admin::user()->can("{$routeBase}.show"),
            'create' => $fullPermission ? true : \Admin::user()->can("{$routeBase}.create"),
            'edit'   => $fullPermission ? true : \Admin::user()->can("{$routeBase}.edit"),
            'delete' => $fullPermission ? true : \Admin::user()->can("{$routeBase}.delete"),
        ];

        return array_filter($permission, function($value) {
            return !$value;
        });
    }
}