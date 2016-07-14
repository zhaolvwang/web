<!DOCTYPE html>
<html>
<head>
   @include('head')
</head>

<body class="@yield('bodyClass')" @yield('bodyAttr')>
	@include('header')
    @yield('content')
    <!-- /#wrapper -->

    
    
    {!! Rapyd::scripts() !!} 
    @include('footer')
    <script type="text/javascript">
    $(".pagination").children('li:first').css({'width':'72px'});
	$(".pagination").children('li:first').children('a').css({'width':'72px'});
	$(".pagination").children('li:last').css({'margin-left':'10px'});
	$(".pagination").children('li:last').children('a').css({'width':'72px'});
	function trim(str) { //删除左右两端的空格
	    return str.replace(/(^\s*)|(\s*$)/g, ""); //把空格替换为空
	}
   </script>
</body>
</html>
