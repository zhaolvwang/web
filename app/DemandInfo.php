<?php namespace App;

class DemandInfo extends BaseModel {

    protected $table = 'demand_info';

    public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    }
    public function coopManuItem()
    {
    	return $this->belongsTo('App\CoopManu', 'coopManu');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	DemandInfo::updating(function ($demand){
    		//self::operateContract($demand);
    	});
    	
    	DemandInfo::created(function ($demand){
    		//self::operateContract($demand);
    	});
    }
    
    private static function operateContract($demand)
    {
    	$count = ContractInfo::where('diid', $demand->getAttribute('id'))->count();
    	if ($count != 1 )
    	{
    		if ($count != 0)
    			ContractInfo::where('diid', $demand->getAttribute('id'))->delete();
    		$contract = new ContractInfo();
    		$contract->diid = $demand->getAttribute('id');
    		$contract->annex = $demand->getAttribute('contract');
    		$contract->annexName = $demand->getAttribute('contractName');
    		$contract->regBy = auth()->user()->id;
    		$contract->save();
    	}
    	else {
    		$contractModel = ContractInfo::where('diid', $demand->getAttribute('id'))->first();
    		$annex = $contractModel->annex;
    		if ($annex != $demand->getAttribute('contract'))
    		{
    			ContractInfo::where('diid', $demand->getAttribute('id'))->update(['annex'=>$demand->getAttribute('contract'), 'annexName'=>$demand->getAttribute('contractName')]);
    		}
    	}
    }
    
}
