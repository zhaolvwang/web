<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Input;

abstract class Controller extends BaseController
{
	use DispatchesJobs, ValidatesRequests;
	
    public $grid;
    public $entity;
    public $edit;
    public $filter;
    public $model;
    public $route;
    public $status;
    protected $routeMethod;
    
    public function __construct()
    {
    	// $this->entity = $params['entity'];
    	$route = \App::make('route');
    	$this->route = $route;
    	$routeParamters = $route::current()->parameters();
    	$this->setEntity(isset($routeParamters['entity']) ? $routeParamters['entity'] : '');
    	$this->routeMethod = isset($routeParamters['methods']) ? $routeParamters['methods'] : '';
    }
    public function getEntity(){
    	return $this->entity;
    }
    
    public function setEntity($entity){
    	$this->entity = $entity;
    }
    
    public function getProdStandard($thickness, $width, $long, $other = '') {
    	$thinckness =  $thickness ? $other.$thickness."*" : "";
    	$width =  $width."*";
    	return $thinckness.$width.$long;
    }
    
    /**
     * @param string $view
     * @param string $prefix
     * @return \Illuminate\View\View
     * 
     * for example: 
     * 	if $prefix is null, $view=>'member.trade.order';
     * 	else not null, $view=>$prefix.$this->entity.$this->routeMethod
     * 
     */
    public function baseReturnView($view = null, $prefix = null, $data = null)
    {
    	
    	$route = $this->route;
    	if (!$view) {
    		$prefix = $prefix ? $prefix : $route::current()->getAction()['prefix'];
    		$view = str_replace('/', '', $prefix).'.'.$this->entity.'.'. $this->routeMethod;
    	}
    	return view($view, array(
    			'grid' 	      => $this->grid,
    			'filter' 	      => $this->filter,
    			'current_entity' => $this->entity,
    			'method' => $this->routeMethod,
    			'import_message' => Input::get('import_message'),
    			'data' => $data,
    	));
    }
    
    public function setGridCommon()
    {
    	$this->grid->add('id', 'id')->style('display:none');
    	$this->grid->add('created_at', 'id')->style('display:none');
    	$this->grid->row(function ($row) {
    		$row->cell('id')->style("display:none");
    		$row->cell('created_at')->style("display:none");
    	});
    }
    
    public function getOptionGroupData($model, $fieldName = 'name', $item = 'item', $id = 'id')
    {
    	$items1 = $model->lists($item, $id)->all();
    	$items2 = array_unique($items1);
    	$fieldNames = $model->lists($fieldName, $id)->all();
    	$options = array();
    	foreach ($items2 as $value) {
    		$options[$value] = array();
    		foreach ($items1 as $k => $v) {
    			if ($v == $value)
    			{
    				$options[$value][$k] = $fieldNames[$k];
    			}
    		}
    	}
    	return $options;
    }
}
