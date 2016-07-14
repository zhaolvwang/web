<?php namespace App;

class ProdOrderRel extends BaseModel {

    protected $table = 'product_order_rel';

	public function product()
    {
    	return $this->belongsTo('App\Product', 'pid');
    }
	public function prodQty()
    {
    	return $this->belongsTo('App\ProdQty', 'pqid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	ProdOrderRel::created(function ($prodOrderRel){
    		self::updateLockedQty($prodOrderRel);
    	});
    	
    	ProdOrderRel::deleting(function ($prodOrderRel){
    		self::updateLockedQty($prodOrderRel);
    	});
    	
    	ProdOrderRel::updated(function ($prodOrderRel){ 
    		if ($prodOrderRel->isPay != 0) {
    			self::updateLockedQty($prodOrderRel);
    		}
    	});
    	
    }
    
    private static function updateLockedQty($prodOrderRel) {
    	ProdQty::where('id', $prodOrderRel->pqid)->update(['lockedQty'=>ProdOrderRel::where('pqid', $prodOrderRel->pqid)->where('isPay', 0)->sum('orderQty')]);
    }
}
