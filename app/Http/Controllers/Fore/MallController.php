<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use Illuminate\Support\Facades\Auth;
use Matriphe\Imageupload\Imageupload;
use App\Http\Controllers\ConstantTool;
use App\ProdQty;
use App\CoopManu;
use App\Product;
use Illuminate\Support\Facades\Input;

class MallController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$prodQty = ProdQty::with("product", 'inBy', 'storehouse')->where('stockStatus', '入库')->whereNull('inid');
		if (Input::get('search_content')) {
			$prodQty->whereHas('product', function ($query){
				$query->where(function ($q2){
					$q2
					->orWhere('pname', 'LIKE', "%".Input::get('search_content')."%")
					->orWhere('alloyNum', 'LIKE', "%".Input::get('search_content')."%")
					->orWhere('matType', 'LIKE', "%".Input::get('search_content')."%")
					->orWhere('processStatus', 'LIKE', "%".Input::get('search_content')."%")
					->orWhere(function($q3){
						$q3->whereHas('coopManu', function ($q4){
							$q4->where('cname', 'LIKE', "%".Input::get('search_content')."%");
						});
					});
				});
			});
		}
		$this->filter = \DataFilter::source($prodQty);
		$this->filter->add('product.matType');
		$this->filter->add('product.alloyNum');
		$this->filter->add('product.processStatus');
		$this->filter->add('product.coopid')->clause('where');
		$this->filter->build();
		
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->grid->add('{{ $product->pname }}');
		$this->grid->add('{{ $product->alloyNum }}');
		$this->grid->add('{{ $product->processStatus }}');
		$this->grid->add('{{ $product->thickness }}');
		$this->grid->add('{{ $product->width }}');
		$this->grid->add('{{ $product->long }}');
		$this->grid->add('{{ $product->standard }}');
		$this->grid->add('{{ $product->oUnitPrc }}');
		$this->grid->add('{{ $storehouse->storehouse }}');
		$this->grid->add('{{ $product->coopid }}');
		$this->grid->add('{{ $product->matType }}');
		$this->grid->add('{{ $product->unit }}');
		$this->grid->add('qty');
		$this->grid->add('weight');
		$this->grid->add('lockedQty');
		$this->grid->paginate(20);
		$this->grid->orderBy('id', 'desc');
		$this->grid->row(function ($row) {
			$row->cell('{{ $product->standard }}')->value = $this->getProdStandard($row->cell('{{ $product->thickness }}')->value, 
					$row->cell('{{ $product->width }}')->value, $row->cell('{{ $product->long }}')->value);
			$row->cell('{{ $product->coopid }}')->value = CoopManu::findOrNew($row->cell('{{ $product->coopid }}')->value)->cname;
			
			/*
			 * 现货供应的数量，应该是现存数量(入库数量-出库数量)-锁货数量
			 */
			$row->cell('qty')->value = $row->cell('qty')->value - ProdQty::where('inid', $row->cell('id')->value)->sum('qty') - $row->cell('lockedQty')->value;
		});
		$this->grid->getGrid('rapyd::datagrid_custom_mall');
		$staff = [];
		$staffData = \App\Admin::where('groupId', 14)->get()->all();
		foreach ($staffData as $v) {
			$staff[$v->id] = $v->first_name.'-'.$v->account.'-'.$v->mobile;
		}
		return $this->baseReturnView('fore.mall', null, [
				'matType'=>Product::getEnumValues('matType'),
				'alloyNum'=>Product::getEnumValues('alloyNum'),
				'processStatus'=>Product::getEnumValues('processStatus'),
				'coopManu'=>CoopManu::lists('cname', 'id')->all(),				'staff'=>$staff,
		]); 
	}
	
	

}
