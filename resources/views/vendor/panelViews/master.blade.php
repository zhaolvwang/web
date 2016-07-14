<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{asset('packages/serverfireteam/panel/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('packages/serverfireteam/panel/favicon.ico')}}" type="image/x-icon">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("packages/zofe/rapyd/assets/demo/style.css")}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("packages/zofe/rapyd/assets/redactor/css/redactor.css")}}"
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("packages/zofe/rapyd/assets/datepicker/datepicker3.css")}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("packages/zofe/rapyd/assets/autocomplete/autocomplete.css")}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("packages/zofe/rapyd/assets/autocomplete/bootstrap-tagsinput.css")}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("packages/zofe/rapyd/assets/colorpicker/css/bootstrap-colorpicker.min.css")}}">
    <!--link media="all" type="text/css" rel="stylesheet" href="{{asset("packages/serverfireteam/rapyd-laravel/assets/colorpicker/css/bootstrap-colorpicker.min.css")}}" -->

    <!-- <title>@yield('title')</title> -->
    <title>铝材贸易公司内部销售管理网站后台管理</title>
    <!-- compiled styles -->

    <link href="{{asset("packages/serverfireteam/panel/css/styles.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("packages/serverfireteam/panel/font-icon/icomoon/style.css")}}" rel="stylesheet" type="text/css">
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="{{asset("packages/fore/html5shiv.js")}}"></script>
        <script src="{{asset("packages/fore/respond.min.js")}}"></script>
    <![endif]-->
    <!-- <link href='{{asset("packages/fore/css.css")}}' rel='stylesheet' type='text/css'> -->

    <!-- jQuery Version 1.11.0 -->
    <script src="{{asset("packages/serverfireteam/panel/js/jquery-1.11.0.js")}}"></script>
	<script type="text/javascript">
	function deleteShopcartQty(_this, id, pnum)
	{
		if (confirm('您确定要删除吗？'))
		{
			$.ajax({
				url:"{{ url('admin/ajax/deleteShopCartSession') }}",
				data:'id='+id,
				type:'GET',
				success: function(msg) {
					$('.shopcart_tbl .'+id).parent().remove();
					$('#shopcart_inputQty'+id).val('');
			   	}
			});
		}
	}
	$(document).ready(function(){
		$("input[type='submit']").click(function(){
			$(this).prop('disabled', true);
			$(this).parents("form").submit();
		});
	});
	
	</script>
</head>

<body class="@yield('bodyClass')">
    @yield('body')
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset("packages/serverfireteam/panel/js/bootstrap.min.js")}}"></script>

    <!-- Jquery InputMask Core JavaScript -->
    <script src="{{asset("packages/serverfireteam/panel/js/jquery.inputmask.bundle.js")}}"></script>
    
    
    <!-- Custom Theme JavaScript -->
    <script src="{{asset("packages/serverfireteam/panel/js/sb-admin-2.js")}}"></script>
    
    <!-- Select2 Elements JavaScript -->
    @include('tool.select2')
    <script src="{{asset("packages/serverfireteam/panel/css/multi-select.css")}}"></script>
	<script src="{{asset("packages/serverfireteam/panel/js/jquery.multi-select.js")}}"></script>
	@if(!empty($coopManu))
	<script type="text/javascript">
	function coopManuSelect2() {
		$(".s2example-2").select2({
			placeholder: '请选择主营厂商',
			allowClear: true
		}).on('change', function(e){
			$("[name='coopManu']").val(e.val);
		});
	}
	jQuery(document).ready(function($)
	{
		coopManuSelect2();
		$(".s2example-22").select2({
			placeholder: '请选择主营品种',
			allowClear: true
		}).on('change', function(e){
			$("[name='variety']").val(e.val);
		}).on('select2-selecting', function(e){
			@foreach($coopManu as $k=>$v)
			if(e.val == '{{ $k }}') {	//<option value="{{ $k }}">{{ $k }}</option>
				$(".s2example-2").append('<optgroup label="{{ $k }}" style="display:none;"></optgroup>');
				@foreach($v as $v1)
				$(".s2example-2 optgroup").last().append('<option value="{{ $v1 }}">{{ $v1 }}</option>');
				@endforeach
			}
			@endforeach
			//coopManuSelect2();
		}).on('select2-removed', function(e) {
				$(".s2example-2 optgroup").each(function(){
					if($(this).attr('label') == e.val) {
						$(this).remove();
					}
				});
			});
		});
	</script>	
	@else
	<script type="text/javascript">
	var name = $(".s2example-22").parent().parent().children('label').text();
	if(name == '') {
		name = $(".s2example-22").parent().parent().children().children('label').text();
	}
	$(".s2example-22").select2({
		placeholder: '请选择'+name.replace('*', '').trim(),
		allowClear: true
	}).on('change', function(e){
		$("[name='"+$(this).attr('id')+"']").val(e.val);
	});
	</script>								
	@endif							

    <!--script src="{{asset("packages/zofe/rapyd/assets/redactor/jquery.browser.min.js")}}"></script-->
    <!--script src="{{asset("packages/zofe/rapyd/assets/redactor/redactor.min.js")}}"></script-->
    <script src="{{asset("packages/zofe/rapyd/assets/datepicker/bootstrap-datepicker.js")}}"></script>
    <script src="{{asset("packages/zofe/rapyd/assets/datepicker/locales/bootstrap-datepicker.it.js")}}"></script>
    <script src="{{asset("packages/zofe/rapyd/assets/autocomplete/typeahead.bundle.min.js")}}"></script>
    <script src="{{asset("packages/zofe/rapyd/assets/template/handlebars.js")}}"></script>
    <script src="{{asset("packages/zofe/rapyd/assets/autocomplete/bootstrap-tagsinput.min.js")}}"></script>
    <script src="{{asset("packages/zofe/rapyd/assets/colorpicker/js/bootstrap-colorpicker.min.js")}}"></script>
    <!--script src="{{asset("packages/serverfireteam/rapyd-laravel/assets/colorpicker/js/bootstrap-colorpicker.min.js")}}"></script-->

    <script src="{{asset("packages/serverfireteam/panel/js/xenon-custom.js")}}"></script>
    
    <!-- Province City County -->
     @include('panelViews::area')
     <script type="text/javascript">_init_area();</script>
    
    {!! Rapyd::scripts() !!} 
    
</body>

</html>
