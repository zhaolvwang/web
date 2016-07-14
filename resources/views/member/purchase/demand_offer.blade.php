@extends('member.member')

@section('content')
<div class="member-tab">
						<span class="current"><a href="###">收到的报价</a><i></i></span>
					</div>
					{!! $filter !!}
					{!! $grid !!}
					
@stop
