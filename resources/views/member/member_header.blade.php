<div class="yeehim-top">
		@include('header_common')
	</div>
<div class="member-nav">
	<div class="container-grid clearfix">
		<div class="left zz txt-center font-s-18 yahei color-FFF">我是采购商</div>
		<ul class="right zz">
			<li @if(!str_contains(Request::url(), 'shopcart'))class="current"@endif><a href="{{ url('member') }}">个人主页</a></li>
			<li @if(str_contains(Request::url(), 'shopcart'))class="current"@endif><a href="{{ url('member/trade/shopcart') }}">购物车</a></li>
			<li><a href="{{ url('mall') }}" target="_blank">找铝商城</a></li>
			<li><a href="{{ url('purchase') }}" target="_blank">采购报价</a></li>
			<li><a href="{{ url('resource') }}" target="_blank">资源单</a></li>
		</ul>
	</div>
</div>