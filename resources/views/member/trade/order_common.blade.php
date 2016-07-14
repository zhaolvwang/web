@section('title')我的商场订单 - @stop
<div class="member-tab">
	<span @if(empty(Request::get('oiStatus')))class="current"@endif><a href="{{ url('member/trade/order') }}">全部<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::get('oiStatus'), '待付款'))class="current"@endif><a href="{{ url('member/trade/order_pay?oiStatus=待付款&search=1') }}">待付款<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::get('oiStatus'), '待提货'))class="current"@endif><a href="{{ url('member/trade/order_delivery') }}?oiStatus=待提货&search=1">待提货<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::get('oiStatus'), '待补款'))class="current"@endif><a href="{{ url('member/trade/order_replenishment') }}?oiStatus=待补款&search=1">待补款<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::get('oiStatus'), '待开票'))class="current"@endif><a href="{{ url('member/trade/order_invoice') }}?oiStatus=待开票&search=1">待开票<i></i></a></span>
</div>

{!! $filter !!}

@if (count($grid->rows))
<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-data-table-head">
		<tr class="bg">
			<td align="center" valign="middle" width="10%">产品信息</td>
			<td align="center" valign="middle" width="10%">铝厂</td>
			<td align="center" valign="middle" width="8%">合金牌号</td>
			<td align="center" valign="middle" width="8%">加工状态</td>
			<td align="center" valign="middle" width="16%">规格</td>
			<td align="center" valign="middle" width="10%">仓库</td>
			<td align="center" valign="middle" width="14%">单价/吨</td>
			<td align="center" valign="middle" width="8%">数量</td>
			<td align="center" valign="middle" width="8%">件重</td>
			<td align="center" valign="middle" width="8%">重量</td>
		</tr>
	</table>
	 {!! $grid !!}
@else
<div class="member-data-none">
		暂无匹配数据
	</div>	
@endif
 