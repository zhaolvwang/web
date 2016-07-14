@extends('member.member')
@section('title')发票查询 - 我的合同发票 - @stop
@section('content')
<div class="member-tab">
	<span class="current"><a href="{{ url('member/trade/invoice') }}">发票查询<i></i></a></span>
	<span class="line-ver"></span>
	<span><a href="{{ url('member/trade/invoice_contract') }}">合同查询<i></i></a></span>
</div>
{!! $filter !!}
{!! $grid !!}					
@stop
