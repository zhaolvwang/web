<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Storehouse;
use App\MyFnc\Permission;

class StorehouseController extends CrudController {

    public function all($entity) {
        parent::all($entity);
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(new Storehouse());
        $this->filter->add('id', trans('panel::fields.siid'), 'text');
        $this->filter->add('storehouse', trans('panel::fields.storehouse'), 'text');
        $this->filter->add('storeAddr', trans('panel::fields.storeAddr'), 'text');
        $this->filter->submit(trans('panel::fields.search'));
        $this->filter->reset(trans('panel::fields.reset'));
        $this->filter->build();
       
        $this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', trans('panel::fields.siid'), true)->style("width:140px");
        $this->addGrid('storehouse');
        $this->addGrid('storeAddr');
        $this->addGrid('telephone');
        $this->grid->link('panel/'.$this->entity.'/edit', trans('panel::fields.add'), "TR", ['class'=>'btn btn-primary']);
        $this->grid->edit('edit', trans('panel::fields.edit'), 'modify');
        return view('panelViews::all', array(
        		'grid' 	      => $this->grid,
        		'filter' 	      => $this->filter,
        		'title'          => $this->entity ,
        		'current_entity' => $this->entity,
        		'method' => $this->routeMethod,
        		'import_message' => (\Session::has('import_message')) ? \Session::get('import_message') : '',
        ));
    }

    public function edit($entity) {
    	$this->edit = \DataEdit::source(new Storehouse());
        if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');

        $this->initEntity(trans('panel::fields.storehouse'));
        
        $this->edit->add('id', trans('panel::fields.shNum'), 'text')->rule('unique:storehouse_info,id|required');
        $this->addEdit('storehouse')->rule('required');
        $this->addEdit('storeAddr')->rule('required');
        $this->addEdit('telephone')->rule('required');

        return $this->returnEditView();
    }
}
