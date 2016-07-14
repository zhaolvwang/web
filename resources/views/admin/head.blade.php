<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8"/>
 <title>找钢网后台管理系统</title>
 <meta name="author" content="DeathGhost" />
 <!--<script src="js/html5.js"></script>-->
 <!--[if lt IE 9]>
 <script src="{{asset("packages/admin/js/html5.js")}}"></script>
 <![endif]-->
 {{--<script src="js/jquery.js"></script>--}}
 {{--<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>--}}
 <script src="{{asset("packages/admin/js/jquery.js")}}"></script>
 <script src="{{asset("packages/admin/js/jquery.mCustomScrollbar.concat.min.js")}}"></script>
 <link href="{{asset("packages/admin/css/style.css")}}" rel="stylesheet" type="text/css">


 <script>
  (function($){
   $(window).load(function(){

    $("a[rel='load-content']").click(function(e){
     e.preventDefault();
     var url=$(this).attr("href");
     $.get(url,function(data){
      $(".content .mCSB_container").append(data); //load new content inside .mCSB_container
      //scroll-to appended content
      $(".content").mCustomScrollbar("scrollTo","h2:last");
     });
    });

    $(".content").delegate("a[href='top']","click",function(e){
     e.preventDefault();
     $(".content").mCustomScrollbar("scrollTo",$(this).attr("href"));
    });

   });
  })(jQuery);
 </script>

 <style>
  .start_admin{
   display: inline-block;
   width: 45px;
   height: 45px;
   background: url(/packages/admin/images/sidebar-toggler.jpg) no-repeat;
   float: right;
  }
 </style>
</head>
<body>
<!--header-->
<header>
 <h1><img src="{{asset('packages/admin/images/admin_logo.png')}}"/></h1>
 <ul class="rt_nav">
  <li><a href="/" target="_blank" class="website_icon">网站首页</a></li>
  <li><a href="#" class="admin_icon">DeathGhost</a></li>
  <li><a href="#" class="set_icon">账号设置</a></li>
  <li><a href="{{url('admin/loginout')}}" class="quit_icon">安全退出</a></li>
 </ul>
</header>
<style>
 #toggle_menu{
  color:#fff;
 }
</style>
<script>

</script>