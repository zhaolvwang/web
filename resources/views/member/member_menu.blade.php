@foreach ($member_menu as $k=>$v)
<dl>
	<dt class="font-s-18 yahei mt20 mb10 pl30">{{ $k }}</dt>
	@foreach ($v as $k1=>$v1)
		<dd @if(str_contains(Request::url(), $k1))class="current"@endif
		><a href="{{ url('member/'.$k1) }}">{{ $v1 }}<i class="arrow"></i></a></dd>
		@endforeach
</dl>
@endforeach
<script type="text/javascript">
	$(".member-menu dl:last").addClass("last");
</script>