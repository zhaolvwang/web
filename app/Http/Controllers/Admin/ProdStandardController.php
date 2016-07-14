<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Product;
use App\MyFnc\Permission;
use Illuminate\Http\Request;
use App\CoopManu;

class ProdStandardController extends CrudController {

    public function all($entity) {
    	parent::edit($entity);
    	if (auth()->user()->groupId != 0) return view('panelViews::hasNoAuthority');
    	
        return view('panelViews::editProdStandard', $this->getRequestData());
    }
    public function edit($entity) {
    	parent::edit($entity);
    	return redirect('panel/ProdStandard/all');
    }
    public function update($entity, $request) {
    	if (auth()->user()->groupId != 0) return view('panelViews::hasNoAuthority');
    	foreach ($request->except('_token', 'coopManu') as $k=>$v)
    	{
    		Product::setEnumNewValues($k, explode(",", $v));
    	}
    	$data = $this->getRequestData();
    	$data['import_message'] = trans('panel::fields.successfullEdit');
        return view('panelViews::editProdStandard', $data);
    }
    
    private function enumData($data) {
    	$tmp = array();
    	foreach ($data as $d)
    	{
    		array_push($tmp, ['name'=>$d, 'data'=>Product::getImplodeEnumValues($d), 'label'=>trans('panel::fields.'.$d)]);
    	}
    	return $tmp;
    }
    
    private function getRequestData() {
    	/*
    	 * 合作厂商：coopManu
    	 * 
    	 * 
    	 * {!! Form::label(Lang::get('panel::fields.coopManu')) !!}<br>
@foreach ($coopManuItems as $item)
{!! Form::label($item['item']) !!}<br>
<input type="text" class="form-control" data-role="tagsinput" name="coopManu[]" value="{{ $item['cname'] }}" style="display: none;">
@endforeach
    	 * 
    	 */
    	$coopManuItems = array();
    	$items = array_unique(CoopManu::lists('item', 'id')->all());
    	foreach ($items as $i)
    	{
    		$tmp = array();
    		foreach (CoopManu::where('item', '=', $i)->get()->toArray() as $v) {
    			array_push($tmp, $v['cname']);
    		}
    		array_push($coopManuItems, array('item'=>$i, 'cname'=>implode(",", $tmp)));
    	}
    	/*
    	 * END
    	 */
    	return [
        				//'coopManuItems'=>$coopManuItems,
        				/* 'data'=>$this->enumData([
        						'unit', 'alloyNum', 'processStatus', 'matType', 
        						'surface', 'coating', 'surPerp', 'pastMembrane', 'prodUsage', 'packForm',
        						'packPaper', 'moistureproof', 'mothProffing',
        				]), */
        				'data'=>$this->enumData([
        						'unit', 'alloyNum', 'processStatus', 
        						'surface', 'coating', 'surPerp', 'pastMembrane', 'prodUsage', 'packForm',
        						'packPaper', 'moistureproof', 'mothProffing',
        				]),
        		];
    }
}
