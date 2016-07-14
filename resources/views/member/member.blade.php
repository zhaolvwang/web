<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{asset('packages/fore/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('packages/fore/favicon.ico')}}" type="image/x-icon">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title'){{ $member_title }}</title>
    <!-- compiled styles -->

    <link href="{{asset("packages/fore/images/basic.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("packages/member/images/member.css")}}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="{{asset("packages/fore/html5shiv.js")}}"></script>
        <script src="{{asset("packages/fore/respond.min.js")}}"></script>
    <![endif]-->
    <!-- <link href='{{asset("packages/fore/css.css")}}' rel='stylesheet' type='text/css'> -->

    <!-- jQuery Version 1.11.0 -->
    <script src="{{asset("packages/serverfireteam/panel/js/jquery-1.11.0.js")}}"></script>
    <script src="{{asset("packages/fore/images/superslide.2.1.js")}}"></script>
    
</head>

<body class="@yield('bodyClass')" @yield('bodyAttr')>
	@include('member.member_header')
	<div class="member-layer">
		<div class="container-grid">
			@if(empty($data['no_member_menu']))
			<div class="layer-container clearfix">
				<div class="member-menu zz">
					@include('member.member_menu')
				</div>
				<div class="member-body zz">
					@yield('content')
				</div>
			</div>
			@else
			@yield('content')
			@endif
		</div>
	</div>
    
    <!-- /#wrapper -->

    
    
    {!! Rapyd::scripts() !!} 
    @include('footer')
    <script type="text/javascript">
    $(".pagination").children('li:first').css({'width':'72px'});
	$(".pagination").children('li:first').children('a').css({'width':'72px'});
	$(".pagination").children('li:last').css({'margin-left':'10px'});
	$(".pagination").children('li:last').children('a').css({'width':'72px'});
	
   </script>
</body>
</html>
