<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Product;

class ProductPackController extends CrudController {

    public function all($entity) {
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');

        $this->productCommonAll();
        $this->addGrid('packForm');
        $this->addGrid('moistureproof');
        $this->addGrid('inDiameter');
        $this->addGrid('outDiameter');
        $this->addGrid('mothProffing');
        $this->addGrid('packPaper');
        return $this->returnView(null, null, 'modify');
    }

    public function edit($entity) {
    	$this->edit = \DataEdit::source(new Product());
       	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->productCommonEdit();
        return $this->returnEditView();
    }
}
