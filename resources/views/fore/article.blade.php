@extends('layout')
@section('title')@if(!empty($article)){{ $article->title }} - @endif资讯动态 - @stop
@section('content')
<div class="container-grid">
	@if(!empty($article))
		<h3 class="txt-center font-s-20 font-w-n mt30 yahei">{{ $article->title }}</h3>
		<div class="txt-center font-s-14 mt10 color-999 line-solid-bottom pb20">
			发布者：{{ $article->writer }}&nbsp;&nbsp;&nbsp;&nbsp;发布时间：{{ substr($article->created_at, 0, 10) }}&nbsp;&nbsp;&nbsp;&nbsp;点击量：{{ $article->click }}
		</div>
		<div class="container-grid mt30">
			<div class="arc-content line-h-28 font-s-14 color-666">
				{!! $article->content !!}
			</div>
			<div class="share margin-auto mt40 mb40" style="width: 145px;">
				<div class="bdsharebuttonbox bdshare-button-style1-16" data-bd-bind="1444361638943"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
	<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
			</div>
		</div>
	</div>
	<div class="container-grid clearfix mt60 pt20 line-solid-top mb60">
		<div class="grid-6">
			<div class="news-else font-s-14">
			@if($prev->exists)<a href="{{ url('news/article/'.$prev->id) }}" target="_blank" class="hover-orange">上一篇：{{ $prev->title }}</a>
			@else
			<a href="javascript:;" target="_blank" class="hover-orange">上一篇：没有了</a>
			@endif
			</div>
		</div>
		<div class="grid-6">
			<div class="news-else font-s-14">
			@if($next->exists)<a href="{{ url('news/article/'.$next->id) }}" target="_blank" class="hover-orange">下一篇：{{ $next->title }}</a>
			@else
			<a href="javascript:;" target="_blank" class="hover-orange">下一篇：没有了</a>
			@endif
			</div>
		</div>
	</div>
	@endif
</div>
@stop
