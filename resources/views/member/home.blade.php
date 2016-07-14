@extends('member.member')

@section('content')
<div class="mb40 font-s-16 yahei">个人主页</div>
					<div class="clearfix">
						<div class="member-ava zz">
							<div class="tit txt-center color-FFF">采购商</div>
							<img src="{{ $headPic }}" alt="" width="75" height="75" />
						</div>
						<div class="member-company zz mt10 ml20 mr40">
							<p class="font-s-16 yahei line-h-30">{{ auth()->user()->uname }}</p>
							<p class="font-s-16 yahei line-h-30">{{ auth()->user()->cmpy }}</p>
						</div>
						<div class="member-count mt20 zz">
							<p class="font-s-16 yahei line-h-30 color-orange">{{ $demandCount }}</p>
							<p class="font-s-14 yahei line-h-30 color-999">需求数（条）</p>
						</div>
						<div class="member-count mt20 zz">
							<p class="font-s-16 yahei line-h-30 color-orange">{{ $orderCount }}</p>
							<p class="font-s-14 yahei line-h-30 color-999">订单数（条）</p>
						</div>
						<div class="member-count mt20 zz">
							<p class="font-s-16 yahei line-h-30 color-orange">0.0</p>
							<p class="font-s-14 yahei line-h-30 color-999">积分</p>
						</div>
						<div class="member-opt yy">
							<a href="{{ url('member/purchase/release') }}" class="color-FFF hover-light">发布采购</a>
						</div>
					</div>
					<div class="memeber-idn-line mt40 mb40"></div>
					<div class="member-tab">
						<span class="current"><a href="###">最近订单提醒<i></i></a></span>
					</div>
					
					@if(count($data) > 0)
					<table width="100%" border="0" cellspacing="0" cellspadding="0" class="member-data-table mt10">
						<tr>
							<td align="center" valign="middle" width="20%">订单号</td>
							<td align="center" valign="middle" width="10%">数量</td>
							<td align="center" valign="middle" width="10%">重量</td>
							<td align="center" valign="middle" width="20%">金额合计</td>
							<td align="center" valign="middle" width="10%">状态</td>
							<td align="center" valign="middle" width="20%">下单时间</td>
							<td align="center" valign="middle" width="10%">操作</td>
						</tr>
						@foreach ($data as $v)
						<tr>
							<td align="center" valign="middle" width="20%">
								<a href="{{ url('member/trade/order_detail?id='.$v['id']) }}" target="_blank" class="color-blue hover-line">{{ $v['oinum'] }}</a>
							</td>
							<td align="center" valign="middle" width="10%">{{ $v['qty'] }}件</td>
							<td align="center" valign="middle" width="10%">{{ $v['weight'] }}{{ $v['unit'] }}</td>
							<td align="center" valign="middle" width="20%">
								<p class="color-orange font-s-16 yahei">¥ {{ $v['amount'] }}</p>
							</td>
							<td align="center" valign="middle" width="10%">{{ $v['oiStatus'] }}</td>
							<td align="center" valign="middle" width="20%">{{ $v['created_at'] }}</td>
							<td align="center" valign="middle" width="10%">
								<a href="{{ url('member/trade/order_detail?id='.$v['id']) }}" target="_blank" class="color-blue hover-line">订单详情</a>
							</td>
						</tr>
						@endforeach
					</table>
					<script type="text/javascript">
						$(".member-data-table tr:eq(0)").addClass("bg");
						$(".member-data-table tr:not(:first)").hover(function(){
							$(this).addClass("hover");
						},function(){
							$(this).removeClass("hover");
						});
					</script>
					@else
					<!-- 没有数据时显示-->
						<div class="member-data-none">
							暂无匹配数据
						</div>
					 
					@endif
@stop
