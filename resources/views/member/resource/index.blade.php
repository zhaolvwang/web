@extends('member.member')

@section('content')
<div class="member-tab">
	<span class="current"><a href="">我的资源单<i></i></a></span>
	<span class="line-ver"></span>
	<span><a href="{{ url('member/resource/attention') }}">我的关注<i></i></a></span>
</div>
					{!! $filter !!}
@if (count($grid->rows))
 {!! $grid !!}
@else
<div class="member-data-none">
		暂无匹配数据
	</div>	
@endif
<script type="text/javascript">
	$(".member-data-table tr:eq(0)").addClass("bg");
	$(".member-data-table tr:not(:first)").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});
</script>
@stop
