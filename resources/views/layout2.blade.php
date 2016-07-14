<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>@yield('title') - 找铝网</title>
@include('head')
</head>
<body style="background-color: #FAFAFA;">
	<div class="container-grid">
		<div class="purchase-header pt20 pb20 clearfix">
			<div class="zz logo"><a href="/"><img src="{{ asset('packages/fore/images/logo.png') }}" alt="" /></a></div>
			<div class="purchase-tit zz mt30 ml40 yahei">@yield('title')</div>
		</div>
		@yield('content')
		@include('footer_common')
	</div>
</body>
</html>