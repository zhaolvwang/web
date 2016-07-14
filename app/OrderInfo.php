<?php namespace App;

use App\Http\Controllers\ConstantTool;
class OrderInfo extends BaseModel {

    protected $table = 'order_info';

    /*
     * Relation
     */
    public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    }
    public function admin()
    {
    	return $this->belongsTo('App\Admin', 'regBy');
    }
    public function products()
    {
    	return $this->hasMany('App\ProdOrderRel', 'oiid');
    }
    public function demand()
    {
    	return $this->belongsTo('App\DemandInfo', 'diid');
    }
    
    /*
     * My Function
     */
    public function totalAmount($actual = null) {	//$actual = true表示返回实提应收总额
    	$prodOrderRel = $this->products->all();
    	$amount = 0;	//原本订单总额
    	$actualAmount = 0;	//实提应收总额
    	foreach ($prodOrderRel as $v)
    	{
    		$amount += $v->orderAmount;
    		$actualAmount += $v->receAmount;
    	}
    	return $actual ? $actualAmount : $amount;
    }
    public function totalWeight($actual = null) {	//$actual = true表示返回实提应收总量
    	$prodOrderRel = $this->products->all();
    	$weight = 0;	//原本订单总量
    	$actualWeight = 0;	//实提应收总量
    	foreach ($prodOrderRel as $v)
    	{
    		$weight += $v->prodQty->weight;
    		$actualWeight += $v->actualWeight;
    	}
    	return $actual ? $actualWeight : $weight;
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	OrderInfo::creating(function ($orderInfo){
    		$orderInfo->canceled_at = date('Y-m-d H:i:s', strtotime(ConstantTool::CANCEL_ORDER_TIME, time()));
    	});
    	OrderInfo::created(function ($orderInfo){
    		$pgbill = new PGBill();
    		$pgbill->oiid = $orderInfo->id;
    		$pgbill->save();
    		
    		$details = new OrderDetails();
    		$details->oiid = $orderInfo->id;
    		$details->details = trans('panel::fields.submitOrder');
    		if (auth()->user()->id < 1000)	//管理员的id是小于1000
    			$details->regBy = auth()->user()->id;
    		$details->save();
    		//self::operateContract($orderInfo);
    	});
    		
    	OrderInfo::updated(function ($orderInfo){
    		self::operateContract($orderInfo);
    		/*
    		 * 订单被撤销时，需要清空锁货数量
    		 */
    		if ($orderInfo->oiStatus == '交易关闭') {
    			foreach (ProdOrderRel::where('oiid', $orderInfo->id)->get()->all() as $v) {
    				$v->update(['isPay'=>2]);
    			}
    		}
    		/*
    		 * 交易成功后，1元1积分
    		 */
    		if ($orderInfo->oiStatus == '交易成功') {
    			$receAmount = (int)ProdOrderRel::where('oiid', $orderInfo->id)->sum('receAmount');
    			if ($receAmount) {
    				User::findOrNew($orderInfo->uid)->increment('integral', $receAmount);
    				$integralInfo = new IntegralInfo();
    				$integralInfo->uid = $orderInfo->uid;
    				$integralInfo->type = 1;
    				$integralInfo->val = $receAmount;
    				$integralInfo->msg = "订单：".$orderInfo->oinum."交易成功，获取积分数：".$receAmount;
    				$integralInfo->origIntegral = User::findOrNew($orderInfo->uid)->integral;
    				$integralInfo->save();
    			}
    			
    		}
    	});
    	OrderInfo::deleted(function ($orderInfo){
    		ProdOrderRel::where('oiid', $orderInfo->id)->delete();
    		PGBill::where('oiid', $orderInfo->id)->delete();
    		OrderDetails::where('oiid', $orderInfo->id)->delete();
    	});
    	
    }
    
    private static function operateContract($orderInfo)
    {
    	$count = ContractInfo::where('oiid', $orderInfo->getAttribute('id'))->count();
    	if ($count != 1 )
    	{
    		if ($count != 0) {
    			ContractInfo::where('oiid', $orderInfo->getAttribute('id'))->delete();
    		}
    		$contract = new ContractInfo();
    		$contract->oiid = $orderInfo->getAttribute('id');
    		$contract->annex = $orderInfo->getAttribute('contract');
    		$contract->annexName = $orderInfo->getAttribute('contractName');
    		$contract->regBy = auth()->user()->id;
    		$contract->save();
    	}
    	else {
    		$contractModel = ContractInfo::where('oiid', $orderInfo->getAttribute('id'))->first();
    		$annex = $contractModel->annex;
    		if (!$orderInfo->getAttribute('contract')) {
    			ContractInfo::where('oiid', $orderInfo->getAttribute('id'))->delete();
    		}
    		else {
    			if ($annex != $orderInfo->getAttribute('contract') && $orderInfo->getAttribute('contract')) {
    				ContractInfo::where('oiid', $orderInfo->getAttribute('id'))->update(['annex'=>$orderInfo->getAttribute('contract'), 'annexName'=>$orderInfo->getAttribute('contractName')]);
    			}
    		}
    	}
    }
    
}
