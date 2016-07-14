@extends('member.member')
@section('title')订单详情 - 我的商场订单 - @stop
@section('content')
<link rel="stylesheet" href="{{ asset('packages/member/images/theme.css') }}">
<script type="text/javascript" src="{{ asset('packages/member/images/theme.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.theme-mail-click').click(function(){
		$('.theme-popover-mask').fadeIn(100);
		$('.theme-mail').slideDown(200);
	});
	$('.mail-close').click(function(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-mail').slideUp(200);
	});

});
</script>
<div class="theme-popover theme-mail">
     <div class="theme-poptit">
          <a href="javascript:;" title="关闭" class="close mail-close">×</a>
          <h3 class="font-s-14 font-w-n color-666">申请开票</h3>
     </div>
     <div class="theme-popbod dform pt40">
		{!! Form::open(['url'=>url('member/trade/applyInvoice'), 'onSubmit'=>'if($("#letterHeader").val() == ""){$("#letterHeader").focus();return false;}']) !!}
			<input type="hidden" name="id" value="{{ $data['info']['id'] }}">
			<div class="member-common-form">
				<dl class="clearfix">
					<dt class="zz color-666">
						<font class="color-orange">*</font>开票抬头：
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="in-txt" name="letterHeader" id="letterHeader" value="{{ $data['info']['letterHeader'] }}">
					</dd>
				</dl>
				<dl class="clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<span class="color-light-red">请填写开票抬头</span>
					</dd>
				</dl>
				<dl class="mt30 clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<input type="submit" class="btn-submit hover-light" value="提交申请">
					</dd>
				</dl>
			</div>
		</form>
     </div>
</div>
<div class="theme-popover-mask"></div>
<div class="member-tab">
	<span class="current"><a href="order_detail.shtml">订单详情<i></i></a></span>
	<a href="{{ url('member/trade/delivery_goods?id='.$data['info']['id']) }}" target="_blank" class="btn-orange hover-light">查看发货单</a>
</div>
<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-order-table mt10">
	<tr class="bg">
		<td colspan="2">订单概况</td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">订单编号：</td>
		<td align="left" valign="middle" width="778">
			<p class="color-blue">{{ $data['info']['oinum'] }}</p>
		</td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">订单状态：</td>
		<td align="left" valign="middle" width="778">
			<p class="color-light-red">{{ $data['info']['oiStatus'] }}</p>
			<div class="piao">
				@if($data['invoice']['status'] == 0)
				<i class="no"></i>未开票
				@elseif($data['invoice']['status'] == 1)
				<i class="no"></i>已申请开票
				@else
				<i class="ok"></i>已开票
				@endif
			</div>
			@if($data['invoice']['status'] == 0 && $data['info']['oiStatus'] == '交易成功')
			<a href="javascript:;" class="btn-piao hover-light theme-mail-click">申请开票</a>
			@endif
		</td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">订单总计：</td>
		<td align="left" valign="middle" width="778">
			<p class="color-orange font-s-16 yahei">¥ {{ $data['info']['amount'] }}</p>
		</td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">开票抬头：</td>
		<td align="left" valign="middle" width="778">
			{{ !empty(trim($data['info']['letterHeader'])) ? $data['info']['letterHeader'] : '-' }}
		</td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">支付方式：</td>
		<td align="left" valign="middle" width="778">
			{{ $data['info']['payMode'] }}
		</td>
	</tr>
	<tr class="bg">
		<td align="left" valign="middle" width="100">提货函</td>
		<td align="left" valign="middle" width="778"></td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">提货方式：</td>
		<td align="left" valign="middle" width="778">{{ $data['pgb']['pgbType'] }}</td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">提货证明：</td>
		<td align="left" valign="middle" width="778">{!! !empty(trim($data['pgb']['voucher'])) ? $data['pgb']['voucher'] : '-' !!}</td>
	</tr>
	<tr>
		<td align="right" valign="middle" width="100">备注信息：</td>
		<td align="left" valign="middle" width="778">{!! !empty(trim($data['pgb']['remarks'])) ? $data['pgb']['remarks'] : '-' !!}</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-order-table last-line mt10">
	<tr class="bg">
		<td width="100%">订单明细</td>
	</tr>
	<tr>
		<td>
			<ul class="detail-info">
				@foreach($data['details'] as $v)
				<li><span class="time">{{ $v['created_at'] }}</span><span class="txt">{!! $v['details'] !!}</span></li>
				@endforeach
			</ul>
		</td>
	</tr>
</table>
@if(count($data['products']['info']))
<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-order-table">
	<tr class="bg">
		<td colspan="9">
			<font class="color-blue">{{ $data['products']['storehouse']['storehouse'] }}</font> 仓库地址：{{ !empty(trim($data['products']['storehouse']['storeAddr'])) ? $data['products']['storehouse']['storeAddr'] : '-' }} &nbsp;联系电话：{!! !empty(trim($data['products']['storehouse']['telephone'])) ? $data['products']['storehouse']['telephone'] : '-' !!}
		</td>
	</tr>
	<tr class="bg">
		<td align="left" valign="middle" width="20%">产品信息</td>
		<td align="center" valign="middle" width="10%">单价/吨</td>
		<td align="center" valign="middle" width="8%">数量</td>
		<td align="center" valign="middle" width="8%">件重</td>
		<td align="center" valign="middle" width="8%">重量</td>
		<td align="center" valign="middle" width="12%">订单金额</td>
	</tr>
	@foreach($data['products']['info'] as $v)
	<tr>
		<td align="left" valign="middle" width="20%">{{ $v['pname'] }}  {{ $v['alloyNum'] }}  {{ $v['processStatus'] }}  {{ $v['standard'] }}</td>
		<td align="center" valign="middle" width="10%">
			<p class="color-orange font-s-16 yahei">¥ {{ $v['oUnitPrc'] }}</p>
		</td>
		<td align="center" valign="middle" width="8%">{{ $v['orderQty'] }}</td>
		<td align="center" valign="middle" width="8%">{{ rtrim(rtrim($v['unitWeight'], '0'), '.') }}</td>
		<td align="center" valign="middle" width="8%">{{ rtrim(rtrim($v['weight'], '0'), '.') }}</td>
		<td align="center" valign="middle" width="12%">
			<p class="color-orange font-s-16 yahei">¥ {{ $v['amount'] }}</p>
		</td>
	</tr>
	@endforeach
</table>
<!-- <table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-order-table mt10">
	<tr class="bg">
		<td colspan="2">提货函状态</td>
	</tr>
	<tr>
		<td>
			<p class="detail-info clearfix">
				<span class="zz"><font class="font-w-b">货权转至</font> 苏LF9858 皖KF0217 沪B55753</span>
				<span class="yy color-light-red">已提货</span>
			</p>
		</td>
	</tr>
</table> -->
@endif
@stop
