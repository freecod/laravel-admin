<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Address extends Field
{
	protected $apiKey;
	
	protected $view = 'admin::form.address';

    /**
     * Address constructor.
     * @param $column
     * @param $arguments
     *
     * ($column, $label, $dadataKey = '', $lat = '', $lon = '')
     */
	public function __construct($column, $arguments)
	{
        $this->label = $arguments[0] ?? 'Address';
		
		$this->column = [
			'address' => $column,
		];

		if (isset($arguments[2])) {
            $this->column['lat'] = $arguments[2];
        }

        if (isset($arguments[3])) {
            $this->column['lon'] = $arguments[3];
        }

        $this->apiKey = $arguments[1] ?? null;

		$this->id = $this->formatId($this->column);
	}
	
	protected static $css = [
		'/vendor/laravel-admin/dadata/css/suggestions.min.css',
	];
	
	protected static $js = [
		'/vendor/laravel-admin/dadata/js/jquery.xdomainrequest.min.js',
		'/vendor/laravel-admin/dadata/js/jquery.suggestions.min.js',
	];
	
	public function default($address)
	{
		$values = is_array($address) ? $address : [$address];
		
		$this->default = [
			'address' => $values[0] ?? null,
			'lat' => $values[1] ?? null,
			'lon' => $values[2] ?? null,
		];
		
		return $this;
	}
	
	public function render()
	{
	    $lat = $this->id['lat'] ?? 'false';
        $lon = $this->id['lon'] ?? 'false';

	    if ($this->apiKey && !isset($this->attributes['readonly'])) {

            $this->script = <<<EOT
            $(".{$this->id['address']}").suggestions({
                token: "{$this->apiKey}",
                type: "ADDRESS",
                count: 10,
                deferRequestBy: 500,
        
                onSelect: function(suggestion) {
                
                    if ({$lat}) {
                        $("#{$lat}").val(suggestion.data.geo_lat);
                    }
                    
                    if ({$lon}) {
                        $("#{$lon}").val(suggestion.data.geo_lon);
                    }
                }
            });
EOT;
        }

		return parent::render();
	}
}