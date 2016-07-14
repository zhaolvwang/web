<?php 
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use App\Http\Controllers\ConstantTool;
use Serverfireteam\Panel\CrudController;
use App\OrderInfo;
use Illuminate\Support\Facades\Input;
use Matriphe\Imageupload\Imageupload;
use App\IntegralInfo;
use App\User;

class AccountController extends CrudController {

	public function index()
	{
		return $this->baseReturnView(null, null, auth()->user());
	}
	
	public function integral()
	{
		$this->filter = \DataFilter::source(IntegralInfo::with('user')->where('uid', auth()->user()->id));
		$this->grid = \DataGrid::source($this->filter);
		$this->grid->attributes(['width'=>"100%", 'border'=>"0", 'cellspacing'=>"0", 'cellspadding'>="0", 'class'=>"member-data-table resource-table"]);
		$this->grid->add('created_at', '创建日期')->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'15%']);
		$this->grid->add('msg', '操作日志')->attributes(['align'=>'left', 'valign'=>'middle', 'width'=>'45%']);
		$this->grid->add('in', '收入')->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'10%']);
		$this->grid->add('out', '支出')->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'10%']);
		$this->grid->add('type')->style('display:none');
		$this->grid->add('val')->style('display:none');
		$this->grid->add('origIntegral', "剩余积分")->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'10%']);
		$this->grid->row(function ($row) {
			$row->cell('type')->style('display:none');
			$row->cell('val')->style('display:none');
			
			$row->cell('created_at')->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'15%']);
			$row->cell('in')->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'10%']);
			$row->cell('out')->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'10%']);
			$row->cell('origIntegral')->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>'10%']);
			$row->cell('msg')->attributes(['align'=>'left', 'valign'=>'middle', 'width'=>'45%']);
			
			if($row->cell('type')->value == 0) {
				$row->cell('in')->value = '-';
				$row->cell('out')->value = $row->cell('val')->value;
			}
			else {
				$row->cell('in')->value = $row->cell('val')->value;
				$row->cell('out')->value = '-';
			}
		});
		$this->grid->getGrid('rapyd::datagrid_custom');
		return $this->baseReturnView(null, null, ['integral'=>User::findOrNew(auth()->user()->id)->integral]);
	}
	
	public function security()
	{
		return $this->baseReturnView();
	}
	
	public function avatar()
	{
		return $this->baseReturnView();
	}
	
	public function uploadAvatar()
	{
		$user = auth()->user();
		$targ_w = $targ_h = 150;
		$jpeg_quality = 90;
		$src = substr(Input::get('avatar'), 1);
		switch (Input::get('fileType')) {
			case 'gif':
				$img_r = imagecreatefromgif($src);
				break;
			case 'png':
				$img_r = imagecreatefrompng($src);
				break;
			
			default:
				$img_r = imagecreatefromjpeg($src);
				break;
		}
		
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
				$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		
		$newFilePath = "public/uploads/images/";
		if (!is_dir($newFilePath))
			mkdir($newFilePath);
		if (!is_dir($newFilePath.$user->id.'/'))
			mkdir($newFilePath.$user->id.'/');
		$savePath = $newFilePath.$user->id.'/'.'avatar_'.Input::get('fileName');
		switch (Input::get('fileType')) {
			case 'gif':
				imagegif($dst_r, $savePath, $jpeg_quality);
				break;
			case 'png':
				imagepng($dst_r, $savePath, $jpeg_quality);
				break;
					
			default:
				imagejpeg($dst_r, $savePath, $jpeg_quality);
				break;
		}
		$user->headPic = 'avatar_'.Input::get('fileName');
		$user->save();
		return redirect('member/account/index');
	}
	
	
	private function accountCommon($source = null) {
		$this->filter = \DataFilter::source($source ? $source : new OrderInfo);
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->filter->add('dinum', '资源单名称', 'text')->placeholder('请输入资源单名称')->attributes(['class'=>'in-txt']);
		$this->filter->submit(trans('panel::fields.query'));
	}
}
