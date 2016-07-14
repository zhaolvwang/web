@extends('member.member')
@section('title')合同查询 - 我的合同发票 - @stop
@section('content')
<div class="member-tab">
						<span><a href="{{ url('member/trade/invoice') }}">发票查询<i></i></a></span>
						<span class="line-ver"></span>
						<span class="current"><a href="{{ url('member/trade/contract') }}">合同查询<i></i></a></span>
					</div>
					{!! $filter !!}
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
