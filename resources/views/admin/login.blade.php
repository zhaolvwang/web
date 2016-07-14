<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>后台登录</title>
<meta name="author" content="DeathGhost" />

    <link href="{{asset("packages/admin/css/style.css")}}" rel="stylesheet" type="text/css">
    <!--<script src="js/html5.js"></script>-->
    <!--[if lt IE 9]>
    <script src="{{asset("packages/admin/js/html5.js")}}"></script>
    <![endif]-->
    {{--<script src="js/jquery.js"></script>--}}
    {{--<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>--}}
    <script src="{{asset("packages/admin/js/jquery.js")}}"></script>
    <script src="{{asset("packages/admin/js/jquery.mCustomScrollbar.concat.min.js")}}"></script>
    {{--<script src="{{asset("packages/admin/js/verificationNumbers.js")}}"></script>--}}
    <script src="{{asset("packages/admin/js/Particleground.js")}}"></script>
    <script src="{{asset("packages/admin/js/jquery.form.js")}}"></script>
<style>
body{height:100%;background:#16a085;overflow:hidden;}
canvas{z-index:-1;position:absolute;}
.admin_login dd .checkcode{float:left;width:210px;height:42px;}
.admin_login dd .checkcode input{width:100%;height:36px;line-height:36px;padding:3px;color:white;outline:none;border:none;text-indent:2.8em;}


.admin_login dd.val_icon img{
    width: 80px;
    height: 42px;
    /* padding: 3px; */
    z-index: 1000;
    border: 0;
    float: right;
    margin-left: 10px;
}
.admin_login .error{
    position: relative;
    background-color: #e74c3c;
    color: #fff;
    border: 2px solid #e74c3c;
    padding: 7px;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.4);
    border-radius: 5px;
    font-size: 14px;
}
.admin_login .error .icon_error{
    height: 20px;
    width: 20px;
    display: inline-block;
    border-radius: 10px;
    background-color: #ffffff;
    line-height: 20px;
    font-size: 12px;
    font-weight: normal;
    margin-right: 10px;
    color: red;
}
.hide_err{
    display: none;
}
</style>
<script>
$(document).ready(function() {
  //粒子背景特效
  $('body').particleground({
    dotColor: '#5cbdaa',
    lineColor: '#5cbdaa'
  });

  //测试提交，对接程序删除即可
  $(".submit_btn").click(function(){
      $('.submit_btn').val("登陆中...").attr('disabled', 'disabled').css({'cursor':'default'});
      $('#loginForm').ajaxSubmit({
          dataType:'json',
          success:function(data){
              if(data.status == 1){
                  $('.error').html('').addClass('hide_err');
                  window.location.href = '{{url('admin/index')}}';
              }else{
                  $('.submit_btn').val("立即登陆").removeAttr('disabled').css({'cursor':'pointer'});
                  $('.error').html('<b class="icon_error">X</b><span>'+data.msg+'</span>').removeClass('hide_err');
              }
          },
          error:function(){
              $('.error').html('<b class="icon_error">X</b><span>网络请求发生错误，请联系管理员！</span>').removeClass('hide_err');
          }
      });

	  });
});
</script>
</head>
<body>
<dl class="admin_login">
 <dt>
  <strong>找铝网后台管理系统</strong>
  <em>Management System</em>
 </dt>
    <dt class="error hide_err">
        <b class="icon_error">X</b><span>错误提示 用户名错误</span>
    </dt>
 <form action="{{url('admin/login')}}" method="post" id="loginForm">
 <dd class="user_icon">
  <input type="text" placeholder="账号" class="login_txtbx" name="account"/>
 </dd>
 <dd class="pwd_icon">
  <input type="password" placeholder="密码" class="login_txtbx" name="pwd"/>
 </dd>
 <dd class="val_icon">
  <div class="checkcode">
    <input type="text" id="J_codetext" placeholder="验证码" name="checkcode" maxlength="4" class="login_txtbx">
    {{--<canvas class="J_codeimg" id="myCanvas" onclick="createCode()">对不起，您的浏览器不支持canvas，请下载最新版浏览器!</canvas>--}}
  </div>
  {{--<input type="button" value="验证码核验" class="ver_btn" onClick="validate();">--}}
     <img src="/auth/validation?width=80&height=42" alt="" style="cursor: pointer;" width="80" height="42" onclick="this.src='/auth/validation?width=80&height=42&num='+Math.random();">

 </dd>
 <dd>
     <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="button" value="立即登陆" class="submit_btn" />
 </dd>
 </form>
 <dd>
  <p>© 2015-2016 找铝网 版权所有</p>
  <p>陕B2-20080224-1</p>
 </dd>
</dl>
</body>
</html>
