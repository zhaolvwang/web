@extends('member.member')

@section('title')我的购物车 - @stop
@section('content')
<link rel="stylesheet" href="{{ asset('packages/member/images/theme.css') }}">
<script type="text/javascript" src="{{ asset('packages/member/images/theme.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.theme-clean-all').click(function(){
		$('.theme-popover-mask').fadeIn(100);
		$('.theme-popover').slideDown(200);
	});
	$('.layer-close').click(function(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-popover').slideUp(200);
	});
});
function deleteLockedGoods(id) {
	$.ajax({
		type:'GET',
		url:'{{ url("ajax/delete-lock-session") }}',
		data:'id='+id,
		success:function(msg) {
			location.reload();
		}
	});
}
</script>
@include('tool.updateLockSessionAmount')
<link href="{{asset("packages/serverfireteam/panel/css/select2.css")}}" rel="stylesheet" type="text/css">
<link href="{{asset("packages/serverfireteam/panel/css/select2-bootstrap.css")}}" rel="stylesheet" type="text/css">
<script src="{{asset("packages/serverfireteam/panel/js/select2.min.js")}}"></script>
<script src="{{asset("packages/serverfireteam/panel/js/select2_locale_zh-CN.js")}}"></script>
<script type="text/javascript">
jQuery(document).ready(function()
{
	$(".s2example").each(function(){
		var name = $(this).parent().parent().children('label').text();
		if(name == '') {
			name = $(this).parent().parent().children().children('label').text();
		}
		var placeholder = '请选择'+name.replace('*', '').trim();
		if(name.replace('*', '').trim() == '父栏目') {
			placeholder += '；若不选择，则为顶级栏目';
		}
		$(this).select2({
			placeholder: placeholder,
			allowClear: true
		}).on('select2-selecting', function(e)
		{
			$("[name='regBy']").val(e.val);
			$("#mallBtnLock").css({'background-color':'#B61D1D'}).attr('disabled', false);
		}).on('select2-removed', function(e)
		{
			$("[name='regBy']").val("");
			$("#mallBtnLock").css({'background-color':'#ccc'}).attr('disabled', true);
		});
	});
	
});
</script>
<div class="member-tab">
	<span class="current"><a href="###">我的购物车</a><i></i></span>
</div>
<!-- <div class="member-search mt20 mb20 clearfix">
</div> -->
<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-data-table-head" ><!--style="border-top: solid #EAEAEA 1px;"  -->
	<tr class="bg">
		<td align="left" valign="middle" width="18%">货物信息</td>
		<td align="center" valign="middle" width="8%">件重/吨</td>
		<td align="center" valign="middle" width="12%">单价</td>
		<td align="center" valign="middle" width="10%">可供量</td>
		<td align="center" valign="middle" width="12%">数量</td>
		<td align="center" valign="middle" width="10%">重量</td>
		<td align="center" valign="middle" width="12%">金额合计</td>
		<td align="center" valign="middle" width="8%">是否有货</td>
		<td align="center" valign="middle" width="10%">操作</td>
	</tr>
</table>
@if(!count(session('user_locked_goods')))
<!-- 没有数据时显示-->
	<div class="member-data-none">
		购物车内没有商品。<a href="{{ url('mall') }}" class="color-blue hover-line">去商城选购</a>
	</div> 
@else
<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-data-table">
	@foreach(session('user_locked_goods') as $k=>$v)
	<tr>
		<td align="left" valign="middle" width="18%">
			<p>{{ $v['coopManu'] }} {{ $v['pname'] }}</p>
			<p class="color-999">{{ $v['alloyNum'] }} {{ $v['processStatus'] }} {{ $v['standard'] }}</p>
			<p class="color-999">{{ $v['storehouse'] }}</p>
		</td>
		<td align="center" valign="middle" width="8%">{{ $v['weight'] }}{{ $v['unit'] }}</td>
		<td align="center" valign="middle" width="12%">
			<p class="color-orange font-s-16 yahei">¥ {{ $v['oUnitPrc'] }}</p>
		</td>
		<td align="center" valign="middle" width="10%">{{ $v['qty']*$v['weight'] }}{{ $v['unit'] }}</td>
		<td align="center" valign="middle" width="12%">
			<div class="spinner" id="{{ $k }}" qty="{{ $v['qty'] }}"></div>
			<div class="mt05">可供货数量:<span class="color-red">{{ $v['qty'] }}</span></div>
			<script type="text/javascript">
			$("#{{ $k }}").Spinner({value:{{ $v['amount'] }}, min:1, len:5, max:{{ $v['qty'] }}});
			</script>
		</td>
		<td align="center" valign="middle" width="10%" id="tb_goods_weight_{{ $k }}"><span>{{ $v['weight']*$v['amount'] }}</span>{{ $v['unit'] }}</td>
		<td align="center" valign="middle" width="12%">
			<p class="color-orange font-s-16 yahei" id="tb_goods_sums_{{ $k }}">¥ <span>{{ $v['oUnitPrc']*$v['weight']*$v['amount'] }}</span></p>
		</td>
		<td align="center" valign="middle" width="8%">@if($v['qty']*$v['weight'] <= 0)否 @else是@endif</td>
		<td align="center" valign="middle" width="10%">
			<a href="javascript:;" class="color-blue hover-line" onclick="if(confirm('您确定要该货物吗？')) deleteLockedGoods({{ $k }});">删除</a>
		</td>
	</tr>
	@endforeach
</table>
<div class="shopping-count clearfix pt20 pb20">
	<div class="total zz">
		<div class="zz ml30 mb10">
			<select class="s2example" style="width:250px; font-size:12px;"> 
				<option></option>
				@foreach($data['staff'] as $k=>$v)
				<option value="{{ $k }}">{{ $v }}</option>
				@endforeach
			</select>
			<label style="display: none;">交易员</label>
		</div>
		<dl class="zz">
			<dt class="zz">件数小计：</dt>
			<dd class="zz"><span class="goods_count">{{ session('user_locked_goods_count') }}</span> 件</dd>
		</dl>
		<dl class="zz">
			<dt class="zz">重量小计：</dt>
			<dd class="zz"><span class="goods_all_weight">{{ session('user_locked_goods_all_weight') }}</span>吨</dd>
		</dl>
		<dl class="zz">
			<dt class="zz">商品总价：</dt>
			<dd class="zz">¥ <span class="goods_total">{{ session('user_locked_goods_total') }}</span></dd>
		</dl>
	</div>
	<div class="operation yy mt40 mr40">
		{!! Form::open(['method'=>'GET', 'url'=>url('member/trade/insertNewOrder')]) !!}
			<input type="hidden" name="regBy">
			<span class="zz ml15"><input type="submit" class="btn-order hover-light" id="mallBtnLock" value="去下单" style="background-color:#ccc" disabled="disabled"/></span>
			<span class="zz ml15"><a href="{{ url('mall') }}" class="btn-buy hover-light">返回继续买货</a></span>
			<span class="zz ml15"><a href="javascript:;" class="btn-clean hover-light theme-clean-all"></a></span>
		{!! Form::close() !!}
	</div>
</div>
					
					
					<div class="theme-popover">
	     <div class="theme-poptit">
	          <a href="javascript:;" title="关闭" class="close layer-close">×</a>
	          <h3 class="font-s-14 font-w-n color-666">清空购物车</h3>
	     </div>
	     <div class="theme-popbod dform">
			<form action="">
				<div class="tips margin-auto" style="width: 300px;">
					<i class="tp-icon"></i>
					<p class="font-s-16 yahei" style=" padding-left: 50px;">清空购物车？</p>
					<p class="font-s-14 color-999 yahei" style=" padding-left: 50px;">购物车中的商品会全部消失</p>
					<input type="button" class="button-orange mt30" value="清空" onclick="deleteLockedGoods('')">
					<input type="button" class="button-gry mt30 layer-close" value="不清空">
				</div>
			</form>
	     </div>
	</div>
	<div class="theme-popover-mask"></div>
	@endif
@stop
