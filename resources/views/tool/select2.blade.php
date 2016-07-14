<link href="{{asset("packages/serverfireteam/panel/css/select2.css")}}" rel="stylesheet" type="text/css">
<link href="{{asset("packages/serverfireteam/panel/css/select2-bootstrap.css")}}" rel="stylesheet" type="text/css">
<script src="{{asset("packages/serverfireteam/panel/js/select2.min.js")}}"></script>
<script src="{{asset("packages/serverfireteam/panel/js/select2_locale_zh-CN.js")}}"></script>
<script type="text/javascript">
jQuery(document).ready(function()
{
	$(".s2example").each(function(){
		var name = $(this).parent().parent().children('label').text();
		if(name == '') {
			name = $(this).parent().parent().children().children('label').text();
		}
		var placeholder = '请选择'+name.replace('*', '').trim();
		if(name.replace('*', '').trim() == '父栏目') {
			placeholder += '；若不选择，则为顶级栏目';
		}
		$(this).select2({
			placeholder: placeholder,
			allowClear: true
		}).on('select2-open', function()
		{
			// Adding Custom Scrollbar
			//$(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
		});
	});
	
});
</script>