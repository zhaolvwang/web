@extends('member.member')

@section('title')发货单 - 我的商场订单 - @stop
@section('content')
<div class="layer-invoice clearfix" style="background-color: #FFF; border: solid #D5D9E2; border-width: 0 1px;">
	<div class="member-body" style="width: 1198px; padding: 20px 40px;">
		<div class="member-tab">
			<span class="current"><a href="javascript:;">发货单<i></i></a></span>
		</div>
		@if(count($data['info']) > 0)
		<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-order-table mt20">
			<tr class="bg">
				<td colspan="11">
					<font>订单编号：</font><font class="color-blue">{{ $data['info']['oinum'] }}</font>
				</td>
			</tr>
			<tr class="bg">
				<td colspan="11">
					<font class="color-blue">{{ $data['info']['storehouse']['storehouse'] }}</font>&nbsp; 仓库地址：{{ !empty(trim($data['info']['storehouse']['storeAddr'])) ? $data['info']['storehouse']['storeAddr'] : '-' }}&nbsp; 联系电话：{{ !empty(trim($data['info']['storehouse']['telephone'])) ? $data['info']['storehouse']['telephone'] : '-' }}
				</td>
			</tr>
			<tr class="bg">
				<td align="left" valign="middle" width="7%">产品信息</td>
				<td align="center" valign="middle" width="10%">铝锭均价</td>
				<td align="center" valign="middle" width="8%">加工费</td>
				<td align="center" valign="middle" width="5%">数量</td>
				<td align="center" valign="middle" width="5%">件重</td>
				<td align="center" valign="middle" width="8%">重量</td>
				<td align="center" valign="middle" width="6%">实提数量</td>
				<td align="center" valign="middle" width="8%">实提重量</td>
				<td align="center" valign="middle" width="10%">发货单金额</td>
				<td align="center" valign="middle" width="10%">逾期容差</td>
				<td align="center" valign="middle" width="10%">实提应收</td>
			</tr>
			<!-- <tr class="bg">
				<td align="left" valign="middle" width="7%">&nbsp;</td>
				<td align="center" valign="middle" width="10%">单价/吨</td>
				<td align="center" valign="middle" width="5%">数量</td>
				<td align="center" valign="middle" width="5%">件重</td>
				<td align="center" valign="middle" width="8%">数量*件重</td>
				<td align="center" valign="middle" width="6%">实提数量</td>
				<td align="center" valign="middle" width="8%">实提数量*件重</td>
				<td align="center" valign="middle" width="10%">重量*单价</td>
				<td align="left" valign="middle" width="18%">计算方法：逾期差价*实提重量 如：原单价12600.00，现在价格12800.00，逾期容差就是200*实提重量，如未逾期则填“0”</td>
				<td align="center" valign="middle" width="10%">实提重量*单价+逾期容差</td>
			</tr> -->
			@foreach($data['products'] as $v)
			<tr>
				<td align="left" valign="middle" width="7%">
					<p>{{ $v->product->pname }}</p>
					<p class="color-999">{{ $v->product->alloyNum }} {{ $v->product->processStatus }} {{ $v->product->thickness }}*{{ $v->product->width }}*{{ $v->product->long }}</p>
				</td>
				<td align="center" valign="middle" width="10%">
					<p class="color-orange font-s-16 yahei">¥ {{ $v->alIngotPrc }}</p>
				</td>
				<td align="center" valign="middle" width="8%">{{ $v->processFee }}</td>
				<td align="center" valign="middle" width="5%">{{ $v->orderQty }}</td>
				<td align="center" valign="middle" width="5%">{{ $v->prodQty->weight }}</td>
				<td align="center" valign="middle" width="8%">{{ $v->orderQty*$v->prodQty->weight }}</td>
				<td align="center" valign="middle" width="6%">{{ $v->actualQty }}</td>
				<td align="center" valign="middle" width="8%">{{ $v->actualWeight }}</td>
				<td align="center" valign="middle" width="10%">
					<p class="color-orange font-s-16 yahei">¥ {{ $v->deliGBillAmt }}</p>
				</td>
				<td align="center" valign="middle" width="10%">
					<p class="color-orange font-s-16 yahei">¥ {{ $v->tolerance }}</p>
				</td>
				<td align="center" valign="middle" width="10%">
					<p class="color-orange font-s-16 yahei">¥ {{ $v->receAmount }}</p>
				</td>
			</tr>
			@endforeach
		</table>
		@else
		<!-- 没有数据时显示 -->
	<div class="member-data-none">
		暂无发货单数据
	</div>
	@endif
	</div>
</div>
@stop
