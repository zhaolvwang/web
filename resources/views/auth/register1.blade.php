<!DOCTYPE html>
<html>
<head>
<title>注册 - {{ $title }}</title>
@include('head')
<style>
 .tip, .fail{display: none;color: red;}
.member-common-form dd .codeWidth{width: 120px;}
</style>
<!-- Jquery InputMask Core JavaScript -->
<script src="{{asset("packages/serverfireteam/panel/js/jquery.inputmask.bundle.js")}}"></script>
<script src="{{asset("packages/serverfireteam/panel/js/xenon-custom.js")}}"></script>
<script language="javascript" type="text/javascript">
	var countdown = "{{ old('countdown') }}" != ""  ? parseInt("{{ old('countdown') }}") : 0;
function sendMsg(_this) {
mobileValidate(document.getElementById("reg_mobile"));
valiCodeValidate(document.getElementById("reg_validate"));
	if($("[name='mobile']").val() != '') {
		if(!$(".register_mobile dd.fail").is(':hidden') && ($(".register_mobile dd.success").is(':hidden') || $(".register_mobile dd.success").length == 0)) {
			alert($(".register_mobile dd.fail").text()); 
		}
		else {
			if(!$(".register_validation dd.fail").is(':hidden') && ($(".register_validation dd.success").is(':hidden') || $(".register_validation dd.success").length == 0)) {
				alert($(".register_validation dd.fail").text()); 
			}
			else {
				$(_this).parent().siblings(".fail").hide();
				$.ajax({
					type:'GET',
					url:'{{ url("ajax/send-message") }}',
					data:'mobile='+$("[name='mobile']").val(),
					success:function(msg) {
						if(msg == 101) {
							alert('验证失败，请检查手机号码是否正确');
						}
						else if(msg == 100) {
							countdown = 120;
							$(_this).parent().siblings(".tip").show();
							settime(_this);
						}
						countdown = 120;
							$(_this).parent().siblings(".tip").show();
							settime(_this);
					}
					
				});  
			}
		}
	}
	else {
		alert('请输入手机号码！');
	}
}

function settime(_this) {
	var val = document.getElementById('divdown');
	
if (countdown == 0) {
val.removeAttribute("disabled");
val.innerHTML="获取验证码";
countdown = 0;
$(_this).parent().siblings(".tip").hide();
} else {
val.setAttribute("disabled", "disabled");
val.innerHTML="重新发送(" + countdown + ")";
countdown--;
$("[name='countdown']").val(countdown);
}
setTimeout(function() {
settime(val)
},1000)
}


$(document).ready(function(){
	
	if(countdown != 0) {
		var val = document.getElementById('divdown');
		
		if (countdown == 0) {
		val.removeAttribute("disabled");
		val.innerHTML="获取验证码";
		countdown = 0;
		$(_this).parent().siblings(".tip").hide();
		} else {
		val.setAttribute("disabled", "disabled");
		val.innerHTML="重新发送(" + countdown + ")";
		countdown--;
		$("[name='countdown']").val(countdown);
		}
		setTimeout(function() {
		settime(val)
		},1000)
	
	}
});

</script> 
</head>
<body style="background-color: #FAFAFA;"> 
	<div class="container-grid">
		<div class="purchase-header pt20 pb20 clearfix">
			<div class="zz logo"><a href="/"><img src="{{ asset('packages/fore/images/logo.png') }}" alt="" /></a></div>
			<div class="purchase-tit zz mt30 ml40 yahei">欢迎注册</div>
		</div>
		<!-- @if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif -->
		<div class="register-main clearfix">
			<div class="register-left zz">
				<form role="form" method="POST" action="{{ url('/auth/register') }}" id="form1">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="countdown" value="{{ old('countdown') }}">
					<div class="mt60 mb60">
						<dl class="member-common-form clearfix mb20 register_mobile">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">手机号码：</font>
							</dt>
							<dd class="zz">
								<input type="text" class="in-txt" name="mobile" value="{{ old('mobile') }}" id="reg_mobile"  data-mask="fdecimal" data-dec="" data-rad="" maxlength="11" onblur="mobileValidate(this);">
							</dd>
							<dd class="fail zz"><i class="sign_icon"></i>请输入手机号码</dd>
						</dl>
						<dl class="member-common-form clearfix mb20 register_validation">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">图片验证码：</font>
							</dt>
							<dd class="zz">
								<input type="text" class="codeWidth in-txt" name="validateCode" id="reg_validate" onblur="valiCodeValidate(this);">
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
								<button type="button" class="code font-s-14" id="divdown" onclick="sendMsg(this);">获取验证码</button>
							</dd> 
							<dd class="fail zz"><i class="sign_icon"></i>请输入短信验证码</dd>
							<dd class="tip zz"><i class="sign_icon"></i>已发送，验证码10分钟内有效</dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">真实姓名：</font>
							</dt>
							<dd class="zz">
								<input type="text" class="in-txt required" name="uname" value="{{ old('uname') }}">
							</dd>
							<dd class="fail zz"><i class="sign_icon"></i>请输入真实姓名</dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">设置密码：</font>
							</dt>
							<dd class="zz">
								<input type="password" class="in-txt" name="password" id="password" onkeyup="passwordValidate(this);" onblur="passwordValidate(this);" value="{{ old('password') }}">
							</dd>
							<dd class="fail zz"><i class="sign_icon"></i>请输入6-18位密码</dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">重复密码：</font>
							</dt>
							<dd class="zz">
								<input type="password" class="in-txt" name="password_confirmation" id="verifyPwd" value="{{ old('password_confirmation') }}" onkeyup="verifyPwdValidate(this);" onblur="verifyPwdValidate(this);">
							</dd>
							<dd class="fail zz"><i class="sign_icon"></i>请输入6-18位密码</dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								<span class="color-orange">*</span>&nbsp;<font class="color-999">公司名称：</font>
							</dt>
							<dd class="zz">
								<input type="text" class="in-txt required" name="cmpy" value="{{ old('cmpy') }}">
							</dd>
							<dd class="fail zz"><i class="sign_icon"></i>请输入公司名称</dd>
						</dl>
						<dl class="member-common-form clearfix mb20">
							<dt class="zz">
								&nbsp;
							</dt>
							<dd class="zz">
								<span class="zz mr10">
									<input type="checkbox" name="policy" class="checkbox" checked="true" value="1" onclick="if(this.value == 1 ){this.value=0;}else{this.value=1;}">
								</span>
								<span class="zz">
									<a target="_blank" href="{{ url('agreement') }}" class="color-blue hover-line">阅读并同意《找铝材网用户服务协议》</a>
								</span>
							</dd>
						</dl>
						<dl class="member-common-form clearfix">
							<dt class="zz">
								&nbsp;
							</dt>
							<dd class="zz">
								<input type="submit" class="btn-submit hover-light" value="提交注册" />
							</dd>
						</dl>
					</div>
				</form>
			</div>
			<div class="register-right zz mt60">
				<h3 class="font-s-14 mt10 mb10 color-666">已有找铝网账号？</h3>
				<p class="line-h-26 color-999">登录找铝材网，即可免费查看或在线购买全国最便宜的铝材资源，也可以免费将您的资源发布到找铝材网。</p>
				<a href="{{ url('auth/login') }}" class="btn-login color-FFF hover-light">登录</a>
				<h3 class="font-s-14 color-666 line-dashed-top mt40 pt40">有任何问题请联系客服</h3>
				<div class="clearfix">
					<p class="mt15 font-s-14 zz">电话：<font class="color-light-red font-w-b">0511-84515328</font></p>
					<p class="yy">
						<a href="###" target="_blank" class="qq-service"></a>
					</p>
				</div>
			</div>
		</div>
		@include('footer_common')
	</div>
<script type="text/javascript">
$(".required").change('blur', function(){
	requiredValidate(this);
});
function trim(str) { //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, ""); //把空格替换为空
}
function showSuccess(_this) {
	var parent = $(_this).removeAttr('style').removeClass('error').parent().siblings('.fail').hide();
	if(parent.siblings('.success').length == 0) 
		parent.parent().append('<dd class="success zz"><i class="sign_icon"></i></dd>');
	return parent;
}
function showFail(_this) {
	$(_this).parent().siblings('.success').remove();
	return $(_this).css({'border-color':'red'}).addClass('error').parent().siblings('.fail').show();
}

/*
 * IE8不兼容，需要另外写
 */
@if(count($errors) > 0) 
@if ($errors->has('mobile'))
showFail($("[name='mobile']"));
@else
showSuccess($("[name='mobile']"));
@endif
@if ($errors->has('validateCode'))
showFail($("[name='validateCode']"));
@endif

@if ($errors->has('msgCode'))
showFail($("[name='msgCode']"));
$("[name='msgCode']").css({'border-color':'red'}).addClass('error').parent().siblings('.fail').html('<i class="sign_icon"></i>{{ $errors->getMessages()["msgCode"][0] }}');
@endif

@if ($errors->has('uname'))
showFail($("[name='uname']"));
@else
showSuccess($("[name='uname']"));
@endif

@if ($errors->has('cmpy'))
showFail($("[name='cmpy']"));
@else
showSuccess($("[name='cmpy']"));
@endif

@if ($errors->has('password'))
showFail($("[name='password']"));
@endif
@if ($errors->has('password_confirmation'))
showFail($("[name='password_confirmation']"));
@endif

@endif
/*
 * END
 */
function requiredValidate(_this) {
	var val = $(_this).val();
	if(val == '') {
		showFail(_this);
		return false;
	}
	else{
		showSuccess(_this);
		return true;
	}
}
function mobileValidate(_this) {
	var mobile = trim(_this.value);
	if (mobile != '') {
		var telReg = mobile.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/)
		if(!telReg) {
			showFail(_this).html('<i class="sign_icon"></i>手机号码格式不正确');
		}
		else {
			$.ajax({
				type:'GET',
				url:'/auth/account',
				data:'account='+mobile,
				success:function(data){
					if(data.status != 1) {
						showSuccess(_this);
					}
					else {
						showFail(_this).html('<i class="sign_icon"></i>手机号码已存在');
					}
				}
			});
			
		}
	}
	else {
		showFail(_this);
	}
}
function valiCodeValidate(_this) {
	var valiCode = trim(_this.value);
	if (valiCode != '') {
		$.ajax({
			type:'GET',
			url:'/auth/validation-code',
			success:function(data){
				if(data.validation_code != valiCode) {
					showFail(_this).html('<i class="sign_icon"></i>验证码不正确');
				}
				else {
					showSuccess(_this);
				}
			}
		});
	}
	else {
		showFail(_this);
	}
}
function verifyPwdValidate(_this) {
	var verifyPwd = trim(_this.value);
	if (verifyPwd != '') {
		if(verifyPwd != $("#password").val()) {
			showFail(_this).html('<i class="sign_icon"></i>输入的密码不一致');
		}
		else {
			if(verifyPwd.length < 6 || verifyPwd.length > 18) {
				showFail(_this);
			}
			else {
				showSuccess(_this);
			}
		}
	}
	else {
		showFail(_this);
	}
}
function passwordValidate(_this) {
	var password = trim(_this.value);
	if (password != '') {
		if(password != $("#verifyPwd").val()) {
			showFail("#verifyPwd").html('<i class="sign_icon"></i>输入的密码不一致');
		}
		if(password.length < 6 || password.length > 18) {
			showFail(_this);
		}
		else {
			showSuccess(_this);
		}
	}
	else {
		showFail(_this);
	}
}
$("#form1").submit(function(){
	if($("[name='policy']").val() == 0) {
		alert("请点击阅读并同意《找铝材网用户服务协议》");
		return false;
	}
	var flag = true;
	$(".in-txt").each(function(){
		var val = $(this).val();
		if(val == '') {
			showFail(this);
			return false;
		}
		/* if(!requiredValidate(this)) {
			flag = false;
		} */
		if(flag) {
			if($(this).hasClass('error')) {
				flag = false;
				return false;
			}
		}
	});
	if(!flag)
		return false;
		
	//return false;
});
</script>
</body>
</html>
