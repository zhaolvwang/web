<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use Illuminate\Support\Facades\Auth;
use Matriphe\Imageupload\Imageupload;
use App\Http\Controllers\ConstantTool;
use App\Http\Requests\ResourceListPostRequest;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use App\ResourceList;
use Illuminate\Http\Response;
use App\ResourceArea;
use App\App;
use App\ResAttentionRel;
use Maatwebsite\Excel\Facades\Excel;

class ResourceController extends Controller {

	protected $excelResult = array();
	
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
		if (auth()->check()) {
			$resourceList = ResourceList::where('resource_list.reStatus', '审核通过')->where('resource_list.uid', '!=', auth()->user()->id);
		}
		else {
			$resourceList = ResourceList::where('resource_list.reStatus', '审核通过');
		}
		
		if (Input::get('dlTms') == 'desc') {
			$resourceList->orderby('resource_list.dlTms', 'desc');
		}
		elseif (Input::get('dlTms') == 'asc') {
			$resourceList->orderby('resource_list.dlTms', 'asc');
		}
		else {
			$resourceList->orderby('resource_list.created_at', 'desc');
		}
		if (Input::get('action') == 'attention') 
			$resourceList->leftJoin('res_attention_rel', 'resource_list.id', '=', 'res_attention_rel.rlid')->where('res_attention_rel.uid', auth()->user()->id)->select('resource_list.*', 'res_attention_rel.*', 'resource_list.id');
		$this->filter = \DataFilter::source($resourceList);
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->filter->add('coopManu')->attributes(['style'=>'display:none']);
		$this->filter->add('variety')->attributes(['style'=>'display:none']);
		$this->filter->add('cmpy')->attributes(['style'=>'display:none']);
		$this->filter->add('city')->attributes(['style'=>'display:none']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
		
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->grid->add('id');
		$this->grid->add('cmpy');
		$this->grid->add('contact');
		$this->grid->add('variety');
		$this->grid->add('coopManu');
		$this->grid->add('city');
		$this->grid->add('dlTms');
		$this->grid->add('annex');
		$this->grid->add('annexName');
		$this->grid->add('uid');
		$this->grid->add('isAttention');
		//$this->grid->paginate(1);
		$this->grid->row(function ($row) {
			if (auth()->check()) {
				$row->cell('isAttention')->value = ResAttentionRel::firstOrNew(['uid'=>auth()->user()->id, 'rlid'=>$row->cell('id')->value])->exists;
			}
			else {
				$row->cell('isAttention')->value = false;
			}
			
		});
		$this->grid->getGrid('rapyd::datagrid_custom_resource');
		
		/*
		 * 资源单地区数据
		 */
		$city = ResourceArea::lists('city', 'id')->all();
		/*
		 * 厂商数据
		 */
		$coopManu = $this->getOptionGroupData(new CoopManu(), 'cname');
		
		return view('fore.resource', array(
				'grid' 	      => $this->grid,
				'city' 	      => $city,
				'coopManu' 	      => $coopManu,
				'filter' 	      => $this->filter,
				'import_message' => Input::get('import_message'),
		));
	}
	
	public function getDownload() {
		ResourceList::findOrNew(Input::get('id'))->increment('dlTms');
		return redirect('public/uploads/resource/'.Input::get('uid')."/".Input::get('annex'));
	}
	
	/*
	 * 需登录
	 */
	public function getUpload()
	{
		$coopManu = $this->getOptionGroupData(new CoopManu(), 'cname');
		return view('fore.resource_upload')->with('coopManu', $coopManu);
	}
	
	public function postUpload(ResourceListPostRequest $request)
	{
		$resourceList = new ResourceList();
		$resourceList->fill(Input::all())->save();
		return new Response('<script>alert("资源单上传成功！");window.location.href="'.url('member/resource/index').'"</script>');
	}
	
	/*
	 * 需登录
	 */
	public function getAttention()
	{
		$uid = auth()->user()->id;
		if (!ResAttentionRel::firstOrNew(['uid'=>$uid, 'rlid'=>Input::get('id')])->exists) {
			$attention = new ResAttentionRel();
			$attention->rlid = Input::get('id');
			$attention->uid = $uid;
			$attention->save();
		}
		return new Response('<script>alert("关注成功！");window.location.href="'.url('resource').'"</script>');
	}
	
	public function getPreview() {
		/*
		 * Input::get('id')--->resource_list.id
		 */
		$resource = ResourceList::findOrNew(Input::get('id'));
		Excel::load('public/uploads/resource/'.$resource->uid.'/'.$resource->annex, function($reader) {
			//获取excel的第几张表
			$reader = $reader->getSheet(0);
			//获取表中的数据
			$this->excelResult = $reader->toArray();
			//在这里的时候$results 已经是excel中的数据了,可以再这里对他进行操作,入库或者其他....
		});
		//dd($this->excelResult);
		return view('fore.resource_preview')
			->with('result', $this->excelResult)
			->with('resource', $resource)
		;
	}
}
