<?php namespace App;

class Product extends BaseModel {

    protected $table = 'product_info';

    public function storehouse()
    {//$this->()
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
    
    public function coopManu()
    {
    	return $this->belongsTo('App\CoopManu', 'coopid');
    }
      
    public static function boot()
    {
    	parent::boot();
    	/*
    	 * 数据库模型监听事件
    	 */
    	Product::updated(function ($product){
    		/*
    		 * 当产品update时的单位重量全部一致，要在表prduct_qty中修改指定数据的qty
    		 */
    		if ($product->isUnitSame == 1)
    		{
    			$count = ProdQty::where('pid', $product->id)->whereNull('inid')->count();
    			if ($count == 0 || $count > 1)
    			{
    				ProdQty::where('pid', $product->id)->whereNull('inid')->delete();
    				self::insertProdQty($product);
    			}
    			else {
    				if ($product->getOriginal('qty') != $product->getAttribute('qty'))
    				{
    					ProdQty::where('pid', $product->id)->whereNull('inid')->update(['qty'=>$product->qty, 'weight'=>$product->weight, 'siid'=>$product->siid]);
    				}
    				
    			}
    		}
    		
    		/*
    		 * 更新现存数量(extantQty)
    		 */
    		$outSumQty = ProdQty::where('pid', $product->id)->whereNotNull('inid')->sum('qty');
    		//var_dump($outSumQty);exit;
    		$extantQty = $product->qty - $outSumQty;
    		if ($product->extantQty != $extantQty) {
    			$product->extantQty = $extantQty;
    			$product->save();
    		}
    		/*
    		 * END
    		 */
    	});
    	Product::created(function ($product){
    		/*
    		 * 当产品的单位重量全部一致，要在表prduct_qty中插入一条数据
    		 */
    		if ($product->isUnitSame == 1)
    		{
    			self::insertProdQty($product);
    		}
    		/*
    		 * END
    		 */
    	});
    	
    }
    
    private static function insertProdQty($product)
    {
    	$product_qty = new ProdQty();
    	$product_qty->pid = $product->getAttribute('id');
    	$product_qty->siid = $product->getAttribute('siid');
    	$product_qty->qty = $product->getAttribute('qty');
    	$product_qty->enterInBy = $product->getAttribute('enterInBy');
    	$product_qty->weight = $product->getAttribute('weight');
    	$product_qty->inRegDt = date('Y-m-d H:i:s');
    	$product_qty->inBy = auth()->user()->id;
    	$product_qty->save();
    }
}
