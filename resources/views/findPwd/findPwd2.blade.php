@extends('layout2')

@section('title')找回密码@stop
@section('content')
<div class="register-main clearfix">
<div class="findpwd-box">
				<div class="step step-2 mt30"></div>
				<form role="form" method="POST" action="{{ url('findPwd3') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div style="margin: 100px 0 100px 250px;">
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">重置密码：</font>
							</dt>
							<dd class="zz">
								<input type="password" class="in-txt">
							</dd>
							<dd class="success zz"><i class="sign_icon"></i></dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">确认密码：</font>
							</dt>
							<dd class="zz">
								<input type="password" class="in-txt">
							</dd>
							<dd class="fail zz"><i class="sign_icon"></i>两次输入的密码不相同</dd>
						</dl>
						<dl class="member-common-form clearfix">
							<dt class="zz">
								&nbsp;
							</dt>
							<dd class="zz">
								<input type="submit" class="btn-submit hover-light" value="提交修改" />
							</dd>
						</dl>
					</div>
				</form>
			</div>

			</div>
@stop
