<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Product;

class ProductPriceController extends CrudController {

    public function all($entity) {
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');

        $this->productCommonAll();
        $this->addGrid('iAlIngotPrc');
        $this->addGrid('iProcessFee');
        $this->addGrid('iUnitPrc');
        $this->addGrid('iTotalPrc');
        $this->addGrid('oAlIngotPrc');
        $this->addGrid('oProcessFee');
        $this->addGrid('oUnitPrc');
        $this->addGrid('oTotalPrc');
        return $this->returnView(null, null, 'modify');
    }

    public function edit($entity) {
        $this->edit = \DataEdit::source(new Product());
       	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->productCommonEdit();
        return $this->returnEditView();
    }
}
