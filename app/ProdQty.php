<?php namespace App;

class ProdQty extends BaseModel {

    protected $table = 'product_qty';

    public function product()
    {
    	return $this->belongsTo('App\Product', 'pid');
    }
    
    public function storehouse()
    {
    	return $this->belongsTo('App\Storehouse', 'siid');
    }
    
    public function inBy()
    {
    	return $this->belongsTo('App\Admin', 'inBy');
    }
    
    public function outBy()
    {
    	return $this->belongsTo('App\Admin', 'outBy');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	ProdQty::created(function ($prodQty) {
    		/*
    		 * 当新建出库时，需要updated产品的现存数量
    		 */
	    	if ($prodQty->getAttribute('inid')) {
	    		self::updateProduct($prodQty);
	    	}
	    	else {
	    		if ($prodQty->getOriginal('weight') != $prodQty->getAttribute('weight'))
	    		{
	    			$productModel = Product::find($prodQty->getAttribute('pid'));
	    			$productModel->weight = $prodQty->weight;
	    			$productModel->save();
	    		}
	    		
	    	}
    	});
    	ProdQty::updated(function ($prodQty) {
    		self::updateProduct($prodQty, 'update');
    	});
    	
    }
    
    private static function updateProduct($prodQty, $type = 'create')
    {
    	/*
    	 * 当inert或update入库或者出库完成时，需要同步更新product_info表中的weight(单位重量)
    	 *
    	 * 1、当修改入库时，更新qty(入库数量)
    	 * 2、当修改出库时，更新extantQty(现存数量)
    	 *
    	 */
    	$productModel = Product::find($prodQty->getAttribute('pid'));
    	$inSumQty = ProdQty::where('pid', $prodQty->pid)->whereNull('inid')->sum('qty');
    	$outSumQty = ProdQty::where('pid', $prodQty->pid)->whereNotNull('inid')->sum('qty');
    	if ($prodQty->getAttribute('inid'))
    	{
    		if ($type == 'create')
    			$outSumQty += $prodQty->getAttribute('qty');
    	}
    	$extantQty = $inSumQty - $outSumQty + ($type == 'update' ? ($prodQty->getAttribute('qty') - $prodQty->getOriginal('qty')) : 0);
    	if ($productModel->extantQty != $extantQty || $productModel->qty != $inSumQty || $productModel->weight != $prodQty->getAttribute('weight'))
    	{
    		$productModel->extantQty = $extantQty;
    		$productModel->qty = $inSumQty;
    		$productModel->weight = $prodQty->weight;
    		$productModel->save();
    	}
    	
    		
    }
}
