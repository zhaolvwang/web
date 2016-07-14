@extends('layout')
@section('title')资讯动态 - @stop
@section('content')
<div class="container-grid clearfix">
	<div class="grid-12 mt15">
		<a href="/" class="color-666 hover-orange">找铝网</a>
		&nbsp;>&nbsp;
		<a href="" class="color-666 hover-orange">资讯动态</a>
	</div>
	{!! $grid !!}
	<div class="grid-4 news-right mt15">
		<div class="top font-s-16 yahei">编辑推荐</div>
		<div class="main">
			<ul class="pt10 pb10 ul-txt-over">
				@foreach($data as $v)
				<li>
					<i class="i-dot"></i><a href="{{ url('news/article/'.$v->id) }}" target="_blank" title="{{ $v->title }}">{{ $v->title }}</a>
				</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>
@stop
