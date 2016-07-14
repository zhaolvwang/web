@extends('layout2')

@section('title')找回密码@stop
@section('content')
<div class="register-main clearfix">
<div class="findpwd-box">
				<div class="step step-1 mt30"></div>
				<form role="form" method="POST" action="{{ url('findPwd2') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div style="margin: 100px 0 100px 250px;">
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">手机号码：</font>
							</dt>
							<dd class="zz">
								<input type="text" class="in-txt">
							</dd>
							<dd class="success zz"><i class="sign_icon"></i></dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">图片验证码：</font>
							</dt>
							<dd class="zz">
								<input type="text" class="in-txt" style="width: 120px;">
							</dd>
							<dd class="zz ml10"><img src="{{ url('auth/validation') }}" alt="" style="cursor: pointer;" 
	                                onclick="this.src='{{ url("auth/validation") }}?num='+Math.random();"/></dd>
							<dd class="fail zz"><i class="sign_icon"></i>请输入图片验证码</dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">短信验证码：</font>
							</dt>
							<dd class="zz">
								<input type="text" class="codeWidth in-txt required" name="msgCode">
							</dd>
							<dd class="zz">
								<a href="javascript:;" class="code">获取验证码</a>
							</dd>
							<dd class="tip zz"><i class="sign_icon"></i>已发送，验证码10分钟内有效</dd>
						</dl>
						<dl class="member-common-form clearfix">
							<dt class="zz">
								&nbsp;
							</dt>
							<dd class="zz">
								<input type="submit" class="btn-submit hover-light" value="下一步" />
							</dd>
						</dl>
					</div>
				</form>
			</div>
			</div>
@stop
