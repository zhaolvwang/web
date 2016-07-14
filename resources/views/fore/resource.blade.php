@extends('layout')
@section('title')资源单 - @stop
@section('content')
<div class="container-grid clearfix">
		<div class="grid-12">
			<dl class="change-ing clearfix pt15 pb15">
				<dt class="zz color-666" style="margin-top: 3px;">资源单搜索 > </dt>
				<dd class="zz ml15">
					@if(Input::get('city') != '')
					<a href="javascript:;" rel="nofollow" class="now-select" title="点击可删除" onclick="deleteSearch('city')">地区：{{ Input::get('city') }}<i></i></a>
					@endif
					@if(Input::get('variety') != '')
					<a href="javascript:;" rel="nofollow" class="now-select" title="点击可删除" onclick="deleteSearch('variety')">分类：{{ Input::get('variety') }}<i></i></a>
					@endif
					@if(Input::get('city') != '' || Input::get('variety') != '')
					<a href="javascript:;" class="btn-anew hover-light" onclick="deleteSearch('')">重新筛选</a>
					@endif
					
				</dd>
			</dl>
			<div class="change-box">
				<dl class="clearfix line-solid-bottom pb05">
					<dt class="zz color-666 mt05">地 区</dt>
					<dd class="zz pl10 mt10 search_box">
						<a href="javascript:;" @if(!Input::get('city'))class="on" @endif onclick="deleteSearch('city')">全部</a>
						@foreach($city as $c)
						<a href="javascript:;" class="city @if(Input::get('city') == $c)on @endif">{{ $c }}</a>
						@endforeach
					</dd>
				</dl>
				<dl class="clearfix line-solid-bottom pb05">
					<dt class="zz color-666 mt05">分 类</dt>
					<dd class="zz pl10 mt10 search_box">
						<a href="javascript:;" @if(!Input::get('variety'))class="on" @endif onclick="deleteSearch('variety')">全部</a>
						@foreach($coopManu as $k=>$c)
						<a href="javascript:;" class="variety @if(Input::get('variety') == $k)on @endif">{{ $k }}</a>
						@endforeach
					</dd>
				</dl>
				<form method="GET" action="" accept-charset="UTF-8" class="" role="form" id="search_form">
					<input type="hidden" name="city" value="{{ Input::get('city') }}" class="select_search">
					<input type="hidden" name="variety" value="{{ Input::get('variety') }}" class="select_search">
					<input type="hidden" name="dlTms" value="{{ Input::get('dlTms') }}">
					<input type="hidden" name="action" value="{{ Input::get('action') }}">
					<div class="search-box line-solid-bottom">
						<div class="zz ml80">
							<div class="dv-tt color-666 zz">铝厂：</div>
							<div class="dv-txt ml10 zz"><input type="text" class="in-txt" name="coopManu" value="{{ Input::get('coopManu') }}"/></div>
						</div>
<!-- 						<div class="zz ml20">
							<div class="dv-tt color-666 zz">品种：</div>
							<div class="dv-txt ml10 zz"><input type="text" class="in-txt" name="variety" value="{{ Input::get('variety') }}"/></div>
						</div>
 -->						<div class="zz ml20">
							<div class="dv-tt color-666 zz">公司名称：</div>
							<div class="dv-txt ml10 zz"><input type="text" class="in-txt" name="cmpy" value="{{ Input::get('cmpy') }}"/></div>
						</div>
						<div class="zz ml20" style="margin-top: 13px;">
							<input type="submit" class="btn-search hover-light" value="" />
						</div>
						<div class="zz ml20" style="margin-top: 13px;">
							<a href="{{ url('resource/upload') }}" target="_blank" class="hover-light btn-upload"></a>
						</div>
					</div>
					<input name="search" type="hidden" value="1">
				</form>
			</div>
			@if (count($grid->rows))
			<div class="resource-tab-tit mt20 clearfix">
				<span @if(Input::get('action') != 'attention')class="current"@endif><a href="?">全部资源单</a></span>
				<span @if(Input::get('action') == 'attention')class="current"@endif>
					@if(auth()->check())
					<a href="?action=attention">我关注的（{{ \App\ResourceList::leftJoin('res_attention_rel', 'resource_list.id', '=', 'res_attention_rel.rlid')->where('res_attention_rel.uid', auth()->user()->id)->where('resource_list.reStatus', '审核通过')->count() }}）</a>
					@else
					<a href="{{ url('auth/login?redirectPath=resource&action=attention') }}">我关注的（0）</a>
					@endif
				</span>
				<span><a href="javascript:;" class="dlTmsOrd">下载数<i class="@if(Input::get('dlTms') == 'asc')up @elseif(Input::get('dlTms') == 'desc')down @endif"></i><!-- 升序时添加类名 up --><!-- 降序时添加类名 down --></a></span>
				
			</div>
			{!! $grid !!}
			@else
			<div class="font-s-18 yahei txt-center" style="border: 1px solid #EAEAEA; padding: 100px 0; color: #666;">
							暂时没有找到匹配的商品
						</div>
			@endif
		</div>
	</div>
<script type="text/javascript">
/**
 * 删除筛选选项
 */
function deleteSearch(type) {
	if(type) {
		$("[name='"+type+"']").val('');
	}
	else {
		$(".select_search").val('');
	}
	$("#search_form").submit();
}
$(".resource-tab-list").hover(function(){
	$(this).addClass("resource-tab-hover");
},function(){
	$(this).removeClass("resource-tab-hover");
});
$(".dlTmsOrd").click(function(){
	var dlTms = $("[name='dlTms']").val();
	if(dlTms == '' || dlTms == 'desc') {
		$("[name='dlTms']").val('asc');
	}
	else if(dlTms == 'asc') {
		$("[name='dlTms']").val('desc');
	}
	$("#search_form").submit();
});
$('.search_box a').click(function(){
	var _this = $(this);
	if(_this.index() == 0) {
		
	}
	else {
		if(!_this.hasClass('on')) {
			$("[name='"+trim(_this.attr('class'))+"']").val(_this.text());
			$("#search_form").submit();
		}
	}
});
</script>
@stop
