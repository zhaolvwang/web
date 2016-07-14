<div class="container-grid clearfix">
	<div class="grid-12">
		@if (auth()->check())
		@include('isLogin')
		@else
		<div class="zz top-link">
			<span class="color-666">欢迎光临铝材超市平台</span>
			<span class="top-line">|</span>
			<span><a href="{{ url('auth/login') }}" class="hover-red color-666">请登录</a></span>
			<span class="top-line">|</span>
			<span><a href="{{ url('auth/register') }}" class="hover-line color-red">免费注册</a></span>
		</div>
		@endif
		<div class="yy top-link">
			<span><a href="{{ url('demand/commit') }}" class="hover-red color-666">发布采购需求</a></span>
			<span class="top-line">|</span>
			<span><a href="{{ url('member/trade/order') }}" class="hover-red color-666">我的订单</a></span>
			<span class="top-line">|</span>
			<span><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2385847714&amp;site=qq&amp;menu=yes" target="_blank" class="hover-red color-666 pl20"><i class="qq"></i>在线客服</a></span>
			<span class="top-line">|</span>
			<span class="color-666">交易热线：0511-87059936</span>
		</div>
	</div>
</div>