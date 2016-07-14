<?php 

namespace App\Http\Controllers\Admin;

use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\ArticleInfo;

class ArticleInfoController extends Controller{

//    public function all($entity){
//        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
//        $this->filter = \DataFilter::source(ArticleInfo::with('regByAdmin'));
//        $this->addFilter('regByAdmin.first_name', 'admin');
//        $this->addFilter('title');
//        $this->addFilter('writer');
//        $this->addFilterSelect('flag', new ArticleInfo(), null, 'set');
//        //$this->addFilter('created_at', 'upload_at', 'daterange')->format('Y-m-d', 'en');
//        $this->commonSearch();
//
//        $this->grid = \DataGrid::source($this->filter);
//        $this->addGrid('title');
//        $this->addGrid('flag');
//        $this->addGrid('writer');
//        $this->addGrid('click');
//        $this->addGrid('descp');
//        $this->addGrid('{{ $regByAdmin->first_name }}', 'admin');
//        $this->addGrid('created_at');
//        $this->addGrid('updated_at');
//        $this->addStylesToGrid();
//        return $this->returnView(null, null, 'modify|delete');
//    }
//
//    public function  edit($entity){
//    	$this->edit = \DataEdit::source(new ArticleInfo());
//    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
//        $this->initEntity(trans('panel::fields.articleInfo'));
//        $this->addEdit('title');
//        $this->addEdit('s2example-22', 'select', 'flag')->options(ArticleInfo::getSetValues('flag'))->attributes(['class'=>'s2example-22', 'multiple'=>'', 'style'=>'width:99%;', 'data-name'=>'flag']);
//        $this->addEdit('flag', 'hidden');
//        $this->addEdit('writer');
//        $this->addEdit('descp', 'textarea');
//        $this->addEdit('content', 'redactor')->attributes(['style'=>'height:800px']);
//        $this->addEdit('click', 'hidden')->insertValue(rand(50, 999));
//        $this->addEdit('regBy', 'hidden')->insertValue(auth()->user()->id);
//
//        return $this->returnEditView();
//    }
    public function getView(){
        $id = Input::get('id');
        echo $id;
        $info = ArticleInfo::find($id);
        echo $info->title;
        echo '<hr/>';
        print_r($info);
        return;
        //return response(print_r($info));
    }
    public function getMe(){
        echo 'me';
    }
}
