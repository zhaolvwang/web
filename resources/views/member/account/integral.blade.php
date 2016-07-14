@extends('member.member')

@section('content')
<div class="member-tab">
						<span><a href="{{ url('member/account/index') }}">帐户信息<i></i></a></span>
						<span class="line-ver"></span>
						<span class="current"><a href="{{ url('member/account/integration') }}">我的积分<i></i></a></span>
						<span class="line-ver"></span>
						<span><a href="{{ url('member/account/security') }}">帐户安全<i></i></a></span>
					</div>
					<div class="mt10 mb10">
						<span class="color-666">当前积分：<font class="color-orange font-s-18 yahei">{{ $data['integral'] }}</font>（1 元等于 1 积分）</span>
					</div>
					{!! $grid !!}
					
					<script type="text/javascript">
						$(".member-data-table tr:eq(0)").addClass("bg");
						$(".member-data-table tr:not(:first)").hover(function(){
							$(this).addClass("hover");
						},function(){
							$(this).removeClass("hover");
						});
					</script>
@stop
