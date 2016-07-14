@extends('member.member')

@section('content')
<link rel="stylesheet" href="{{ asset('packages/member/images/theme.css') }}">
<script type="text/javascript" src="{{ asset('packages/member/images/theme.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.theme-mail-click').click(function(){
		$('.theme-popover-mask').fadeIn(100);
		$('.theme-mail').slideDown(200);
	});
	$('.mail-close').click(function(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-mail').slideUp(200);
	});

	$('.theme-phone-click').click(function(){
		$('.theme-popover-mask').fadeIn(100);
		$('.theme-phone').slideDown(200);
	});
	$('.phone-close').click(function(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-phone').slideUp(200);
	});
})
</script>
<div class="member-tab">
	<span><a href="{{ url('member/account/index') }}">帐户信息<i></i></a></span>
	<span class="line-ver"></span>
	<span><a href="{{ url('member/account/integral') }}">我的积分<i></i></a></span>
	<span class="line-ver"></span>
	<span class="current"><a href="{{ url('member/account/security') }}">帐户安全<i></i></a></span>
</div>
<div class="security-level-box mt15">
	<div class="clearfix">
		<span class="level-txt font-w-b font-s-14 yahei mr10 zz">您的账户安全等级为</span>
		<div class="level-bar zz">
			<i class="level-m"></i><!-- 低：level-l 中：level-m 高：level-h -->
		</div>
		<div class="level-txt font-w-b font-s-14 yahei mr10 zz">中</div>
	</div>
	<div class="color-999 yahei font-s-14 mt15">建议根据账户安全进行操作。</div>
</div>
<div class="security-account mt15">
	<div class="top font-w-b font-s-14 yahei">帐户安全</div>
	<div class="account-state pt15 pb15 clearfix line-solid-bottom">
		<div class="state-info no-check zz">
			<span class="icon-info ml20 zz"></span>
			<span class="txt-info yahei font-s-14 zz">未开启</span>
		</div>
		<div class="zz font-s-18 yahei color-666 mr60">邮箱验证</div>
		<div class="zz font-s-14 yahei color-666 line-h-26">未验证邮箱</div>
		<div class="yy mr20">
			<a href="javascript:;" class="btn-account-orange hover-light color-FFF txt-center yahei theme-mail-click">验证</a>
		</div>
	</div>
	<div class="account-state pt15 pb15 clearfix line-solid-bottom">
		<div class="state-info check zz">
			<span class="icon-info ml20 zz"></span>
			<span class="txt-info yahei font-s-14 zz">建议修改</span>
		</div>
		<div class="zz font-s-18 yahei color-666 mr60">修改密码</div>
		<div class="zz font-s-14 yahei color-666 line-h-26">为确保账号安全，建议经常修改密码</div>
		<div class="yy mr20">
			<a href="javascript:;" class="btn-account-orange hover-light color-FFF txt-center yahei theme-click" data-theme="theme-pwd">修改</a>
		</div>
	</div>
	<div class="account-state pt15 pb15 clearfix line-solid-bottom">
		<div class="state-info check zz">
			<span class="icon-info ml20 zz"></span>
			<span class="txt-info yahei font-s-14 zz">已开启</span>
		</div>
		<div class="zz font-s-18 yahei color-666 mr60">验证手机</div>
		<div class="zz font-s-14 yahei color-666 line-h-26">验证通过</div>
		<div class="yy mr20">
			<a href="javascript:;" class="btn-account-gry hover-light txt-center yahei theme-phone-click">修改手机号</a>
		</div>
	</div>
</div>
<div class="theme-popover theme-mail">
     <div class="theme-poptit">
          <a href="javascript:;" title="关闭" class="close mail-close">×</a>
          <h3 class="font-s-14 font-w-n color-666">修改邮箱</h3>
     </div>
     <div class="theme-popbod dform pt40">
		<form action="">
			<div class="member-common-form">
				<dl class="clearfix">
					<dt class="zz color-666">
						邮箱地址：
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="in-txt">
					</dd>
				</dl>
				<dl class="mt40 clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="btn-submit hover-light" value="修改">
					</dd>
				</dl>
			</div>
		</form>
     </div>
</div>
<div class="theme-popover theme-pwd" style="height: 285px;">
     <div class="theme-poptit">
          <a href="javascript:;" title="关闭" class="close pwd-close theme-close">×</a>
          <h3 class="font-s-14 font-w-n color-666">修改密码</h3>
     </div>
     <div class="theme-popbod dform pt20 pb20">
		{!! Form::model(null, array('url'=>url('home/update-pwd'), 'id'=>'updatePwd')) !!}
		<input type="hidden" name="token" value="{{ csrf_token() }}">
			<div class="member-common-form">
				<dl class="clearfix">
					<dt class="zz color-666">
						旧密码：
					</dt>
					<dd class="zz dd-txt">
						<input type="password" class="in-txt" name="password" required="required">
					</dd>
				</dl>
				<dl class="clearfix mt10">
					<dt class="zz color-666">
						新密码：
					</dt>
					<dd class="zz dd-txt">
						<input type="password" class="in-txt" name="password_confirmation" placeholder="请输入6-18位密码" required="required">
					</dd>
				</dl>
				<dl class="clearfix mt10">
					<dt class="zz color-666">
						再次输入：
					</dt>
					<dd class="zz dd-txt">
						<input type="password" class="in-txt" name="verifyPwd" placeholder="请输入6-18位密码" required="required">
					</dd>
				</dl>
				<dl class="mt20 clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<input type="submit" class="btn-submit hover-light" value="修改">
					</dd>
				</dl>
			</div>
		{!! Form::close() !!}
     </div>
</div>
<div class="theme-popover theme-phone">
     <div class="theme-poptit">
          <a href="javascript:;" title="关闭" class="close phone-close">×</a>
          <h3 class="font-s-14 font-w-n color-666">修改手机号</h3>
     </div>
     <div class="theme-popbod dform pt20 pb30">
		<form action="">
			<div class="member-common-form">
				<dl class="clearfix">
					<dt class="zz color-666">
						手机号：
					</dt>
					<dd class="zz dd-txt">
						13356564606
					</dd>
				</dl>
				<dl class="clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<a href="javascript:;" class="btn-bg-blue hover-light font-s-12">获取短信验证码</a>
					</dd>
				</dl>
				<dl class="clearfix mt10">
					<dt class="zz color-666">
						验证码：
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="in-txt">
					</dd>
				</dl>
				<dl class="mt20 clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<input type="submit" class="btn-submit hover-light" value="修改">
					</dd>
				</dl>
			</div>
		</form>
     </div>
</div>
<div class="theme-popover-mask"></div>
<script type="text/javascript">
@if (!empty($import_message))
	@if ($import_message == 1)
		$('.theme-popover-mask').show();
		$('.theme-pwd').show();
		alert('输入的旧密码不正确');
	@elseif ($import_message == 2)
		alert('修改密码成功！');
	@endif
@endif
$("#updatePwd").submit(function(){
	if($("[name='password']").val() == '') {
		alert('旧密码不能为空');
		$("[name='password']").select();
		return false;
	}
	if($("[name='password_confirmation']").val() == '') {
		$("[name='password_confirmation']").select();
		alert('请输入6-18位密码');
		return false;
	}
	else {
		if($("[name='password_confirmation']").val().length < 6 || $("[name='password_confirmation']").val().length > 18) {
			$("[name='password_confirmation']").select();
			alert('请输入6-18位密码');
			return false;
		}
	}
	if($("[name='verifyPwd']").val() != $("[name='password_confirmation']").val()) {
		$("[name='verifyPwd']").select();
		alert('密码不一致');
		return false;
	}
});
</script>
@stop
