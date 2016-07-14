@extends('layout')
@section('title')采购报价 - @stop
@section('content')
<link rel="stylesheet" href="{{ asset('packages/member/images/theme.css') }}">
<script type="text/javascript" src="{{ asset('packages/member/images/theme.js') }}"></script>
<script type="text/javascript">
	$(function() {
		//Tooltips
		$(".tip-trigger").hover(function(){
			tip = $(this).find(".tip");
			tip.show();
		}, function() {
			tip.hide();	  
		}).mousemove(function(e) {
			var mousex = e.pageX + 20;
			var mousey = e.pageY + 20;
			var tipWidth = tip.width();
			var tipHeight = tip.height();
			
			var tipVisX = $(window).width() - (mousex + tipWidth);
			var tipVisY = $(window).height() - (mousey + tipHeight);
			  
			if ( tipVisX < 20 ) { 
				mousex = e.pageX - tipWidth - 20;
			} if ( tipVisY < 20 ) {
				mousey = e.pageY - tipHeight - 20;
			} 
			tip.css({  top: mousey, left: mousex });
		});

	});
</script>
<div class="container-grid clearfix mt20">
		<div class="grid-9 purchase-left">
			<!-- 列表 -->
			<!-- <div class="purchase-list clearfix mb20">
				<div class="goods zz">
					<div class="goods-name color-FFF font-s-20 yahei txt-center">铝卷</div>
					<p class="color-FFF font-s-20 yahei txt-center">4A13</p>
					<p class="color-FFF font-s-20 yahei txt-center mt10">合金牌号</p>
				</div>
				<div class="info zz">
					<div class="clearfix">
						<div class="info-p zz">
							<p>求购：<font class="font-s-16 color-orange yahei">49.5吨</font></p>
							<p>买家自提</p>
							<p>付款时间：当天</p>
						</div>
						<div class="info-p zz">
							<p>价格意向：<font class="font-s-16 color-orange yahei">￥ 9000.0</font></p>
							<p>收货地：华北</p>
							<p>最晚交货：2015-09-01</p>
						</div>
						<div class="info-p zz">
							<p class="service"><i></i>经纪人：黄先生</p>
							<p class="qq"><i></i>362674132</p>
							<p class="telephone"><i></i>18158112203</p>
						</div>
					</div>
					<div class="color-999 ml20 mt10">更新时间：2015-08-31 09:16:51</div>
					<div class="info-price"><a href="javascript:;" class="hover-light theme-price-click theme-price-click">我要报价</a></div>
				</div>
			</div> -->
			{!! $grid !!}
		</div>
		<div class="grid-3 purchase-right">
			<div class="send-info">
				<h3 class="font-s-16 font-w-n yahei txt-center pt15 pb15">发布采购需求</h3>
				<form action="{{ url('demand/commit') }}" method="get">
					<div class="dv-txt pb15">
						<textarea name="content" id="" class="in-area" placeholder="写下您的真实需求，包括规格、材质等，收到后我们会立即给您回电确认，剩下的就交给我们吧。"></textarea>
					</div>
					<div class="dv-txt pb15">
						<input type="submit" class="btn-submit hover-light" value="确认发布" />
					</div>
				</form>
			</div>
			<div class="common-layer-right yeehim-common-layer mt20">
				<div class="tt font-s-16 txt-center" style="background-color: #F5F5F5;">交易记录</div>
				<div class="main">
					<table border="0" cellspacing="0" cellpadding="0" width="100%" class="table-right">
						<tbody><tr>
							<td valign="middle" align="center" width="25%">采购内容</td>
							<td valign="middle" align="center" width="35%">价格（元/吨）</td>
							<td valign="middle" align="center" width="25%">交易状态</td>
							<td valign="middle" align="center" width="15%">时间</td>
						</tr>
					</tbody></table>
					<ul class="record-data">
						@foreach($data as $v)
						<li class="tip-trigger">
							{{--<div class="tip" style="display: none; top: 1827px; left: 1324px;">--}}
								{{--<div class="clearfix">--}}
									{{--<div class="zz">采购内容</div><div class="yy">{{ $v->oiStatus }}</div>--}}
								{{--</div>--}}
								{{--<p class="color-666">{{ $v->demand->pname }}【{{ $v->demand->variety }}】 {{ $v->demand->alloyNum }} {{ $v->demand->processStatus }} {{ $v->demand->standard }} {{ $v->demand->disMode }} {{ $v->demand->qty*$v->demand->unitWeight }}吨&nbsp;￥{{ $v->demand->intentPrc }}&nbsp;</p>--}}
							{{--</div>--}}
							<span class="buy-tit">{{ $v->demand->alloyNum }}</span>
							<span class="buy-price">
								<p class="yahei font-s-14 color-orange">￥{{ $v->demand->intentPrc }}</p>
							</span>
							<span class="buy-status">
								@if($v->oiStatus == '待付款')
								<p>待付款</p>
								@else
								<p class="color-orange">交易成功</p>
								@endif
							</span>
							<span class="buy-time">
								<p class="color-999">{{ substr($v->created_at, 11, 5) }}</p>
							</span>
						</li>
						@endforeach
						
					</ul>
				</div>
			</div>
		</div>
	</div>
<!-- Jquery InputMask Core JavaScript -->
<script src="{{asset("packages/serverfireteam/panel/js/jquery.inputmask.bundle.js")}}"></script>
<script src="{{asset("packages/serverfireteam/panel/js/xenon-custom.js")}}"></script>	
<script type="text/javascript">
function offerAmountFnc() {
	var offerPrc = parseFloat($("[name='offerPrc']").val());
	var logFee = parseFloat($("[name='logFee']").val() ? $("[name='logFee']").val() : 0);
	var offerQty = parseFloat($("[name='offerQty']").val());
	
	$("[name='offerAmount']").val((offerPrc+logFee)*offerQty);
}
</script>
<div class="theme-popover theme-price" style="width: 618px; height: 340px; margin: -170px 0 0 -309px;">
	     <div class="theme-poptit clearfix" style="background-color: #FAFAFA;">
	          <a href="javascript:;" title="关闭" class="close price-close">×</a>
	          <h3 class="font-s-14 font-w-n color-666 zz">采购单号：<span class="de_dinum"></span></h3>
	          <span class="yy mr15 color-666">目前已有<font class="color-light-red de_offerCount">0</font>家报价</span>
	     </div>
	     <div class="theme-popbod dform pt10">
	     	<div class="line-solid-bottom pl10 pr10 pb10 color-666">采购内容：
	     		<span class="de_variety"></span>&nbsp;
	     		<span class="de_alloyNum"></span>&nbsp;
	     		<span class="de_processStatus"></span>&nbsp;
	     		<span class="de_standard"></span>&nbsp;
	     		<span class="de_disMode"></span>&nbsp;
	     		<span class="de_buyWeight"></span>&nbsp;
	     		<span class="de_city"></span>&nbsp;
	     		<font class="yahei font-s-16 color-orange de_intentPrc"></font>
	     	</div>
	     	@if(auth()->check())
	     	{!! Form::model(null, array('url'=>url('purchase/offer'), 'id'=>'offerForm')) !!}
	     	<input type="hidden" name="uid" value="{{ auth()->user()->id }}">
	     	<input type="hidden" name="diid" class="de_diid">
	     	@else
	     	{!! Form::model(null, array('url'=>url('auth/login'), 'method'=>'GET', 'id'=>'offerForm')) !!}
	     	<input type="hidden" name="redirectPath" value="purchase">
	     	@endif
				<div class="member-common-form clearfix mt20">
					<dl class="zz mr30 mb10" style="width: 275px;">
						<dt class="zz color-666">
							<span class="color-orange">*</span>采购价格：
						</dt>
						<dd class="zz">
							<input type="text" class="in-txt" style="width: 130px;" name="offerPrc" data-mask="fdecimal" data-dec="" data-rad="." maxlength="15" onblur="offerAmountFnc();">
						</dd>
						<dd class="zz font-s-12 color-999 ml10">元/吨</dd>
					</dl>
					<dl class="zz mr30 mb10" style="width: 275px;">
						<dt class="zz color-666">
							<span class="color-orange">*</span>采购数量：
						</dt>
						<dd class="zz">
							<input type="text" class="in-txt" style="width: 130px;" name="offerQty" data-mask="fdecimal" data-dec="" data-rad="." maxlength="15" onblur="offerAmountFnc();">
						</dd>
						<dd class="zz font-s-12 color-999 ml10">吨</dd>
					</dl>
					<dl class="zz mr30 mb10" style="width: 275px;">
						<dt class="zz color-666">
							<span class="color-orange">*</span>物流费用：
						</dt>
						<dd class="zz">
							<input type="text" class="in-txt" style="width: 130px;" name="logFee" data-mask="fdecimal" data-dec="" data-rad="." maxlength="15" onblur="offerAmountFnc();">
						</dd>
						<dd class="zz font-s-12 color-999 ml10">元/吨</dd>
					</dl>
					<dl class="zz mr30 mb10" style="width: 275px;">
						<dt class="zz color-666">
							采购总价：
						</dt>
						<dd class="zz">
							<input type="text" class="in-txt" style="width: 130px;background-color: #e0e0e0" name="offerAmount" data-mask="fdecimal" data-dec="" data-rad="." maxlength="15" readonly="readonly">
						</dd>
						<dd class="zz font-s-12 color-999 ml10">吨</dd>
					</dl>
					<dl class="zz mr30 mb30" style="">
						<dt class="zz color-666">
							<span class="color-orange">*</span>&nbsp;仓库：
						</dt>
						<dd class="zz">
							<select name="province" id="s_province" class="in-txt" style="width: 138px;">
							</select>
						</dd>
						<dd class="zz ml10">
							<select name="city" id="s_city" class="in-txt" style="width: 138px;">
							</select>
						</dd>
						<dd class="zz ml10">
							<select name="county" id="s_county" class="in-txt" style="width: 160px;">
							</select>
						</dd>
					</dl>
					<div class="margin-auto" style="width: 180px;">
						<input type="submit" class="btn-submit hover-light" value="提交报价" style="width: 180px;">
					</div>
				</div>
			</form>
	     </div>
	</div>
	<div class="theme-popover-mask"></div>
<!-- Province City County -->
     @include('panelViews::area')
     <script type="text/javascript">_init_area();
     $("#offerForm").submit(function(){
	if($("[name='offerPrc']").val() == '') {
		alert("采购价格不能为空！");
		return false;
	}
	if($("[name='offerQty']").val() == '') {
		alert("采购数量不能为空！");
		return false;
	}
	if($("[name='logFee']").val() == '') {
		alert("物流费用不能为空！");
		return false;
	}
	if($("[name='province']").val() == '' || $("[name='city']").val() == '' || $("[name='county']").val() == '') {
		alert("仓库不能为空！");
		return false;
	}
});</script>
@stop
