<div class="zz top-link">
	<span class="color-666"><a href="/" class="hover-orange">找铝网首页</a></span>
	<span class="top-line">|</span>
	<span>您好，<font class="color-red">{{ auth()->user()->uname }}</font></span>
	<span class="top-line">|</span>
	<span><a href="{{ url('member') }}" class="hover-red">会员中心</a> <a href="{{ url('auth/logout') }}" class="color-blue hover-line">[退出]</a></span>
</div>