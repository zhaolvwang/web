<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Product;

class ProductPerfController extends CrudController {

    public function all($entity) {
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');

        $this->productCommonAll();
        $this->addGrid('alloyNum');
        $this->addGrid('processStatus');
        $this->addGrid('matType');
        $this->addGrid('surface');
        $this->addGrid('coating');
        $this->addGrid('surPerp');
        $this->addGrid('pastMembrane');
        $this->addGrid('prodUsage');
        return $this->returnView(null, null, 'modify');
    }

    public function edit($entity) {
       $this->edit = \DataEdit::source(new Product());
       	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->productCommonEdit();
        return $this->returnEditView();
    }
}
