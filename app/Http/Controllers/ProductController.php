<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Project;
use App\MyFnc\Permission;
use App\ProdQty;
use App\ProdOrderRel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\CoopManu;
use App\Storehouse;
use App\Product;

class ProductController extends CrudController {

	protected $excelResult = array();
	protected $request = null;
	
    public function all($entity) {
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->productCommonAll();
        //$this->filter->add('pnum', 'p', 'text');
        $this->addGrid('shNum');
        $this->addGrid('thickness');
        $this->addGrid('width');
        $this->addGrid('long');
        $this->grid->add('{{ $storehouse->storehouse }}',trans("panel::fields.storehouse"));
        $this->addGrid('weight');
        $this->addGrid('unit');
        $this->grid->add('{{ $coopManu->cname }}',trans("panel::fields.coopManu"));
        //$this->addGrid('isUnitSame');
        $this->addGrid('qty');
        $this->grid->add('extantQty',trans("panel::fields.all").trans("panel::fields.extantQty"));
        $this->grid->add('outQty',trans("panel::fields.all").trans("panel::fields.outQty"));
        //$this->addGrid('inStorage')->actions('in', explode('|', 'btn|'.trans('panel::fields.inStorage')."|ProductIn"));
        $this->addGrid('inStorageRecord')->actions('all', explode('|', 'btn|'.trans('panel::fields.inStorageRecord')."|ProductIn"));
        $this->addGrid('outStorageRecord')->actions('all', explode('|', 'btn|'.trans('panel::fields.outStorageRecord')."|ProductOut|pid"));
        $this->grid->edit('edit', trans('panel::fields.edit'), 'modify|delete');
        $this->grid->row(function ($row) {
        	
        	/* if ($row->cell('isUnitSame')->value == 0)
        	{
        		$row->cell('isUnitSame')->value = '否';
        		$row->cell('weight')->value = '-';
        	}else {
        		$row->cell('isUnitSame')->value = '是';
        		$row->cell('inStorage')->value->with('name', 'disabled');
        	} */
        	if (ProdOrderRel::firstOrNew(['pid'=>$row->cell('id')->value])->exists) {
        		$row->cell('_edit')->value->with('actions', ['modify']);
        	}
        	$row->cell('outQty')->value = ProdQty::whereNotNull("inid")->where('pid', $row->cell('id')->value)->sum('qty');
        });
        //$this->grid->link('panel/'.$this->entity.'/edit', trans('panel::fields.add'), "TR", ['class'=>'btn btn-primary']);
       	
        return $this->returnView();
    }

    public function edit($entity) {
    	$this->edit = \DataEdit::source(new Product());
       	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
       	$this->productCommonEdit();
       	return $this->returnEditView();
    }
    
    public function batchUpload($entity) {
    	$this->edit = \DataEdit::source(new Project());
        if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->edit->label(trans('panel::fields.batch').trans('panel::fields.release1').trans('panel::fields.product'));
        $uploadUrl = 'public/uploads/batch/'.auth()->user()->id;
        $annex = $this->edit->add('annex', trans('panel::fields.file').trans('panel::fields.upload'), 'file')->rule('required|xlsx,xls')->move($uploadUrl, md5(auth()->user()->id));
       	if(!empty(session('errors'))) {
       		$annex->has_error = " has-error";
       		$annex->messages = [str_replace($annex->name, $annex->label, session('errors')->all()[0])];
       	}
       	
        $this->edit->attributes(['url'=>url('panel/Product/batchUploadDo')]);
        return $this->returnEditView();
    }
    
    public function batchUploadDo($entity, $request) {
    	$this->validate(
    			$request,
    			array(
    					'annex' => 'required|xlsx,xls,csv',
    			)
    	);
    	$this->request = $request;
    	if (!empty($_FILES['annex']['name']))
    	{
    		$uid = auth()->user()->id;
    		$savePath = $_SERVER['DOCUMENT_ROOT']."/public/uploads/batch/";
	    	if (!is_dir($savePath))
	    	 	mkdir($savePath);
	    	if (!is_dir($savePath . $uid . '/'))
	    	 	mkdir($savePath . $uid . '/'); 
	    	$uploadName = $_FILES['annex']['tmp_name'];
	    	$file_type = explode(".", $_FILES['annex']['name']);
	    	$fileName = md5(auth()->user()->id).".".$file_type[count($file_type)-1];
	    	$targetPath = $savePath . $uid . '/'.$fileName;
	    	$temp=iconv("utf-8", "gbk//ignore", $targetPath); 
	     	if(is_uploaded_file($uploadName)){
	    		if (move_uploaded_file($uploadName, $temp)) {
	    			Excel::load('public/uploads/batch/'.auth()->user()->id.'/'.$fileName, function($reader) {
			    		//获取excel的第几张表
			    		$reader = $reader->getSheet(0);
			    		//获取表中的数据
			    		$this->excelResult = $reader->toArray();
			    		//在这里的时候$results 已经是excel中的数据了,可以再这里对他进行操作,入库或者其他....
			    		if (is_array($this->excelResult)) {
			    			foreach ($this->excelResult as $k=>$v) {
			    				if ($k != 0) {
			    					$product = new Product();
			    					/*
			    					 * 编码第一位代表库  第二位0  然后是日期比如今天150828 后面依次递增
			    					 * 10150828001
			    					 * 后面留3位好了
			    					 */
			    					$siid = Storehouse::firstOrNew(['storehouse'=>$v[10]])->id;
			    					if (!$siid)
			    						$siid = 1;
			    					$firstSixNum = (strlen($siid) == 2 ? $siid : $siid.'0').date('ymd');
			    					$maxPnum = Product::whereRaw('LEFT(pnum, 8) = ?', [$firstSixNum])->max('pnum');
			    					$maxPnum = $maxPnum ? $maxPnum : $firstSixNum.'000';
			    					
			    					$product->pnum = substr($maxPnum+1, 0, 11);
			    					$product->batchNum = $v[1];
			    					$product->pname = $v[2];
			    					$product->shNum = $v[0];
			    					$product->thickness = $v[3] ? $v[3] : 0;
			    					$product->width = $v[4] ? $v[4] : 0;
			    					$product->long = $v[5] ? $v[5] : 0;
			    					$product->coopid = CoopManu::firstOrNew(['cname'=>$v[6]])->id;
			    					$product->matType = $v[7];
			    					$product->unit = $v[8];
			    					$product->isUnitSame = 1;
			    					$product->siid = $siid;
			    					$product->weight = $v[11];
			    					$product->qty = str_replace(",", "", $v[12]);
			    					$product->enterInBy = $v[13];
			    					$product->alloyNum = $v[14];
			    					$product->processStatus = $v[15];
			    					$product->surface = $v[16];
			    					$product->coating = $v[17];
			    					$product->surPerp = $v[18];
			    					$product->pastMembrane = $v[19];
			    					$product->prodUsage = $v[20];
			    					$product->inDiameter = $v[21];
			    					$product->outDiameter = $v[22];
			    					$product->packForm = $v[23];
			    					$product->moistureproof = $v[24];
			    					$product->mothProffing = $v[25];
			    					$product->packPaper = $v[26];
			    					$product->iAlIngotPrc = str_replace(",", "", $v[27]);
			    					$product->iProcessFee = str_replace(",", "", $v[28]);
			    					$product->iUnitPrc = str_replace(",", "", $v[29]);
			    					$product->iTotalPrc = str_replace(",", "", $v[30]);
			    					$product->oAlIngotPrc = str_replace(",", "", $v[31]);
			    					$product->oProcessFee = str_replace(",", "", $v[32]);
			    					$product->oUnitPrc = str_replace(",", "", $v[33]);
			    					$product->oTotalPrc = str_replace(",", "", $v[34]);
			    					$product->save();
			    				}
			    			}
			    			$this->request->session()->flash('import_message', '批量上传发布产品成功');
			    		}
			    		//dd($this->excelResult[220][6]);
			    	});
	    		}
	    	}
	    	
	    		//
	    	return redirect('panel/Product/all');
    	}
    }
}
