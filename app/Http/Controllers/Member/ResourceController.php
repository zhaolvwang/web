<?php 
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use App\Http\Controllers\ConstantTool;
use Serverfireteam\Panel\CrudController;
use App\OrderInfo;
use App\ResourceList;
use Illuminate\Support\Facades\Auth;
use App\ResAttentionRel;
use Illuminate\Support\Facades\Input;

class ResourceController extends CrudController {

	public function index()
	{
		$this->resourceCommon(ResourceList::where('uid', Auth::id()));
		$this->grid = \DataGrid::source($this->filter);
		$this->grid->attributes(['width'=>"100%", 'border'=>"0", 'cellspacing'=>"0", 'cellspadding'=>"0", 'class'=>"member-data-table resource-table operate-tbl"]);
		$this->grid->add('annexName', '资源单名称')->attributes(['align'=>"left", 'width'=>"40%"]);
		$this->grid->add('annex')->style('display:none');
		$this->grid->add('uid')->style('display:none');
		$this->grid->add('id')->style('display:none');
		$this->grid->add('created_at', '上传时间')->attributes(['align'=>"center", 'width'=>"20%"]);
		$this->grid->add('dlTms', '下载次数')->attributes(['align'=>"center", 'width'=>"20%"]);
		$this->grid->row(function ($row) {
			foreach ($row->cells as $cell) {
				if ($cell->name == 'annexName') {
					$cell->attributes(['align'=>"left", 'width'=>"40%"]);
				}
				else if($cell->name == 'annex' || $cell->name == 'uid' || $cell->name == 'id') {
					$cell->attributes(['style'=>"display:none"]);
				}
				else {
					$cell->attributes(['align'=>"center", 'width'=>"20%", 'class'=>$cell->name]);
				}
			}
		});
		$this->grid->getGrid('rapyd::datagrid_custom');
		return $this->baseReturnView();
	}
	
	public function attention()
	{
		$this->filter = \DataFilter::source(ResAttentionRel::with('user', 'resource')->where('uid', auth()->user()->id));
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->filter->add('resource.dinum', '资源单名称', 'text')->placeholder('请输入资源单名称')->attributes(['class'=>'in-txt']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
		$this->grid = \DataGrid::source($this->filter);
		$this->grid->add('{{ $user->uname }}');
		$this->grid->add('{{ $resource->uid }}');
		$this->grid->add('{{ $resource->contact }}');
		$this->grid->add('{{ $resource->cmpy }}');
		$this->grid->add('{{ $resource->annex }}');
		$this->grid->add('{{ $resource->annexName }}');
		$this->setGridCommon();
		$this->grid->getGrid('rapyd::datagrid_custom_attention');
		return $this->baseReturnView();
	}
	
	public function cancelAttention () {
		/*
		 * Input::get('id')--->res_attention_rel.id
		 */
		ResAttentionRel::findOrNew(Input::get('id'))->delete();
		return response('<script>alert("取消关注成功！");window.location.href="'.url('member/resource/attention').'"</script>');
	}
	
	private function resourceCommon($source = null) {
		$this->filter = \DataFilter::source($source ? $source : new OrderInfo);
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->filter->add('dinum', '资源单名称', 'text')->placeholder('请输入资源单名称')->attributes(['class'=>'in-txt']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
	}
}
