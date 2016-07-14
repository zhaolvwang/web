<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>登录 - {{ $title }}</title>

@include('head')



</head>

<body>

	<div class="container-grid">

		<div class="purchase-header pt20 pb20 clearfix">

			<div class="zz logo"><a href="/"><img src="{{ asset('packages/fore/images/logo.gif') }}" alt="" /></a></div>

			<div class="purchase-tit zz mt30 ml40 yahei">欢迎登录</div>

		</div>

		<div class="login-layer clearfix">

			<div class="left zz"><img src="{{ asset('packages/fore/images/login_bg.jpg') }}" alt="" width="790" height="350" /></div>

			<div class="right yy">

				<h3 class="font-s-18 font-w-n yahei">用户登录</h3>

				

				@if (count($errors) > 0)

					@foreach ($errors->all() as $error)

						<div class="msg-error mt10"><b></b>{{ $error }}</div>

					@endforeach

				@else

					<div class="msg-warn mt10"><b></b>公共场所不建议自动登录，以防账号丢失</div>

				@endif

				<form role="form" method="POST" action="{{ url('/auth/login') }}">

					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<input type="hidden" name="redirectPath" value="{{ old('redirectPath') ? old('redirectPath') : collect(Input::all())->toJson() }}">

					<div class="form-login mt10">

						<div class="item item-fore1 mb20 @if ($errors->has('mobile'))item-error @endif" id="div_mobile"><!-- item-focus:蓝色 item-error:红色 -->

		                    <label for="loginname" class="login-label name-label"></label>

		                    <input id="loginname" type="text" class="itxt" name="mobile" placeholder="请输入手机号码" value="{{ old('mobile') }}" 

		                    	onfocus="$('#div_mobile').addClass('item-focus');" onblur="$('#div_mobile').removeClass('item-focus');">

		                    <span class="clear-btn" style="display: none;"></span>

		                </div>

		                <div class="item item-fore1 mb20 @if ($errors->has('password'))item-error @endif" id="div_password">

		                    <label for="loginname" class="login-label pwd-label"></label>

		                    <input id="loginname" type="password" class="itxt" name="password" placeholder="请输入密码" value="{{ old('password') }}" 

		                    	onfocus="$('#div_password').addClass('item-focus');" onblur="$('#div_password').removeClass('item-focus');" >

		                    <span class="clear-btn" style="display: none;"></span>

		                </div>

		                @if ($errors->has('password'))

		                <script type="text/javascript">

		                $(document).ready(function(){

		                	$("[name='password']").select();

		                	});

						

		                </script>

		                @endif

		                <div class="item item-fore3 mb20">

                            <div class="safe clearfix">

                                <span class="zz">

                                    <label class="color-666"><input id="autoLogin" name="remember" type="checkbox" class="jdcheckbox" tabindex="3">

                                    自动登录</label>

                                </span>

                                 <span class="yy">

                                    <a href="{{ url('findPwd1') }}" class="color-666 hover-orange" target="_blank">忘记密码?</a>

                                </span>

                            </div>

                        </div>

                        <div class="item item-fore5 mb20">

	                        <input type="submit" class="btn-submit hover-light" value="登&nbsp;&nbsp;录" />

	                    </div>

	                    <div class="item item-fore6 txt-center">

	                        <font class="color-999">还没有找铝网账号？</font><a href="{{ url('auth/register') }}" class="color-light-red hover-line">马上注册！</a>

	                    </div>

		            </div>

                </form>

			</div>

		</div>

		@include('footer_common')

	</div>

</body>

</html>

