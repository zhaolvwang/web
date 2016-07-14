@extends('member.member')

@section('content')
@include('member.trade.order_common')
<!-- <div class="member-search mt20 mb20 clearfix">
	<div class="dv-tit zz">订单号：</div>
	<div class="dv-txt zz ml05">
		<input type="text" class="in-txt" placeholder="请输入订单号">
	</div>
	<div class="dv-btn zz ml10">
		<input type="submit" class="btn-search" value="查询">
	</div>
</div> -->

<!-- 没有数据时显示
	<div class="member-data-none">
		暂无匹配数据
	</div>
 -->

@stop
