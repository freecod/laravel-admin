<?php

namespace Encore\Admin\FormView;

use Encore\Admin\Facades\Admin;
use \Encore\Admin\Form\Tools as FormTools;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools extends FormTools
{
    /**
     * Collection of tools.
     *
     * @var array
     */
    protected $tools = ['delete', 'view', 'edit', 'list'];

	/**
	 * Disable `edit` tool.
	 *
	 * @return $this
	 */
	public function disableEdit()
	{
		array_delete($this->tools, 'edit');
		
		return $this;
	}
	
	/**
	 * Get request path for edit.
	 *
	 * @return string
	 */
	protected function getEditPath()
	{
		$key = $this->form->getResourceId();
		
		return $this->getListPath().'/'.$key.'/edit';
	}
	
	/**
	 * Render `edit` button.
	 *
	 * @return string
	 */
	protected function renderEdit()
	{
		$edit = trans('admin.edit');
		
		return <<<HTML
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{$this->getEditPath()}" class="btn btn-sm btn-primary" title="{$edit}">
        <i class="fa fa-edit"></i><span class="hidden-xs"> {$edit}</span>
    </a>
</div>
HTML;
	}
}
