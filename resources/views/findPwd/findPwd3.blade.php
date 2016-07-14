@extends('layout2')

@section('title')找回密码@stop
@section('content')
<div class="register-main clearfix">
<div class="findpwd-box">
		<div class="step step-3 mt30"></div>
		<form action="">
			<div style="margin: 100px 0 100px 250px;">
				<div class="findpwd-finish">
					<i class="sign-icon"></i>
					<h3 class="yahei">修改密码成功</h3>
					<p>请牢记您的用户名和密码</p>
					<p>
                        <a href="{{ url('member') }}" class="color-blue hover-line">进入会员中心</a>
					</p>
				</div>
			</div>
		</form>
</div>
</div>
@stop
