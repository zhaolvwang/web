<?php namespace App;

class ContractInfo extends BaseModel {

    protected $table = 'contract_info';

    public function order()
    {
    	return $this->belongsTo('App\OrderInfo', 'oiid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	ContractInfo::created(function ($contractInfo){
    		$orderInfo = OrderInfo::findOrNew($contractInfo->oiid);
    		if ($orderInfo->contract == '') {
    			$orderInfo->contract = $contractInfo->annex;
    			$orderInfo->contractName = $contractInfo->annexName;
    			$orderInfo->save();
    		}
    	});
    }
    
    
}
