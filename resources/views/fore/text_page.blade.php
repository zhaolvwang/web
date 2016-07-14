@extends('layout')
@section('title')@if(!empty($article)){{ $article->title }} - @endif资讯动态 - @stop
@section('content')
<div class="container-grid clearfix">
@if(is_numeric($id))
		<div class="grid-12 mt15">
			<span class="color-666">您的当前位置：</span>
			&nbsp;&gt;&nbsp;
			<a href="/" class="color-666 hover-orange">找铝网</a>
			&nbsp;&gt;&nbsp;
			<a href="" class="color-666 hover-orange">帮助中心</a>
		</div>
		<div class="content-left zz mt15">
			<ul class="side-nav">
				@foreach($footer_column as $k=>$v)
				<li class="font-s-16 yahei top">{{ $k }}</li>
				@foreach($v as $k1=>$v1)
					<li class="@if($id == $k1)current @endif sub" lang="0"><a href="{{ url('text/page/'.$k1) }}">{{ $v1 }}</a></li>
				@endforeach
				@endforeach
				<li class="font-s-16 yahei top">关于找铝网</li>
				<li class="@if($id == 20)current @endif sub" lang="0"><a href="{{ url('text/page/20') }}">关于我们</a></li>
				<li class="@if($id == 21)current @endif sub" lang="0"><a href="{{ url('text/page/21') }}">法律声明</a></li>
				<li class="@if($id == 22)current @endif sub" lang="0"><a href="{{ url('text/page/22') }}">诚聘英才</a></li>
				<li class="@if($id == 23)current @endif sub" lang="0"><a href="{{ url('text/page/23') }}">投资洽谈</a></li>
				<li class="@if($id == 24)current @endif sub" lang="0"><a href="{{ url('text/page/24') }}">友情链接</a></li>
			</ul>
		</div>
		<div class="content-right zz mt15 ml20">
			<div class="conetnt-list">
				<h3 class="font-s-20 yahei txt-center line-solid-bottom pb15">{{ $text->title }}</h3>
				<div class="main line-h-28 font-s-14 mt15 color-666">{!! $text->content !!}</div>
			</div>
		</div>
	</div>
	@endif
@stop
