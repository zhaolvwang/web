<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;

class ProjectController extends CrudController {

    public function all($entity) {

        parent::all($entity);

        $this->filter = \DataFilter::source(new \App\Project());
        $this->filter->add('id', 'ID', 'text');
        $this->filter->add('name', 'Name', 'text');
        $this->filter->submit('search');
        $this->filter->reset('reset');
        $this->filter->build();
       
        $this->grid = \DataGrid::source($this->filter);
        $this->grid->link('panel/'.$entity.'/edit',"New Article", "TR");
        $this->grid->add('id','ID', true)->style("width:100px");
        $this->grid->add('name', 'Name', true);
        $this->grid->add('small_desc', '122', true);
        $this->grid->add('desc', 'Description', true);
        $this->grid->add('url', 'Url', true);
		$this->grid->add('start_date', 'Project Start Date', 'start_date');
		
		
        $this->grid->row(function ($row) {
        	//print_r($row->cell('id')->value);exit;
        	if ($row->cell('id')->value == 7) {
        
        		$row->style("background-color:#CCFF66");
        	} elseif ($row->cell('id')->value > 5) {
        		$row->cell('small_desc')->style("font-weight:bold");
        		$row->style("color:#f00");
        	}
        });
        	$this->addStylesToGrid('id', 4);
//\Session::put("import_message", 'good');
        return $this->returnView();
    }

    public function edit($entity) {
// print_r($_GET);exit;
        parent::edit($entity);

        $this->edit = \DataEdit::source(new \App\Project());

	$teamMembers = array(1 => "1 - 5", 2 => "6 - 10", 3 => "11 - 30", 4 => "more than 30");

        $this->edit->label('Edit Project');
        $this->edit->link("panel/".$entity."/all","Articles", "TR")->back();
        $this->edit->add('name', 'Name', 'text')->rule('required|min:2');
        $this->edit->add('desc', 'Description', 'textarea')->rule('required|min:5');
        $this->edit->add('url', 'Url', 'text');
		$this->edit->add('active', 'Active', 'checkboxgroup')->option(1, '111');
		$this->edit->add('duration', 'Duration', 'radiogroup')->option(1, 'Less than 6 months')->option(2, 'More than 6 months');
		$this->edit->add('team_number', 'Number of Team Members', 'select')->options($teamMembers);
		$this->edit->add('start_date', 'Project Start Date', 'date')->format('Y-m-d');
        $this->edit->add('pic1', 'Photo 1', 'image')->move('uploads/website/');
        $this->edit->add('pic2', 'Photo 2', 'image')->move('uploads/website/');
        $this->edit->add('pic3', 'Photo 3', 'image')->move('uploads/website/');
       

        return $this->returnEditView();
    }
}
