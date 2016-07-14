@extends('layout')
@section('title')资源单预览 - @stop
@section('content')
<div class="container-grid clearfix">
	<div class="grid-12 mt15">
			<span class="color-666">您的当前位置：</span>
			&nbsp;&gt;&nbsp;
			<a href="" class="color-666 hover-orange">找铝网</a>
			&nbsp;&gt;&nbsp;
			<a href="" class="color-666 hover-orange">资源单</a>
			&nbsp;&gt;&nbsp;
			<span class="color-666">预览报价单</span>
		</div>
		<div class="grid-12 mt15">
			<div class="preview-top rel">
				<h3 class="font-s-20 yahei font-w-n color-orange mb10">{{ $resource->cmpy }}</h3>
				<p class="color-999 mb05">
					<span class="mr30">联系人：{{ $resource->contact }}</span>
					<span>上传时间：{{ $resource->created_at }}</span>
				</p>
				<p class="color-999">
					<span class="mr30">报价单共下载<font class="color-orange">{{ $resource->dlTms }}</font>次</span>
				</p>
				<a href="/public/uploads/resource/{{ $resource->uid }}/{{ $resource->annex }}" target="_blank" class="resource-download hover-light">点击下载资源单</a>
			</div>
			<table width="100%" border="0" cellspacing="0" cellspadding="0" class="preview-table">
				<tr>
					@foreach($result[0] as $v)
					<td align="center" valign="middle">{{ $v }}</td>
					@endforeach
				</tr>
				@foreach($result as $k=>$v)
					@if($k != 0)
					<tr>
					@foreach($v as $v1)
						<td align="center" valign="middle" >{{ $v1 }}</td>
					@endforeach
					</tr>
					@endif
				@endforeach
				
				
			</table>
		</div>
		<script type="text/javascript">
		$(".preview-table tr:even").addClass("even");
	</script>	
</div>

@stop
