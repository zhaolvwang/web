<div class="yeehim-top">
		@include('header_common')
	</div>
	<div class="yeehim-header">
		<div class="container-grid clearfix">
			<div class="grid-12">
				<div class="logo zz"><a href="/"><img src="{{asset('packages/fore/images/logo.gif')}}" alt="" /></a></div>
				<div class="yeehim-send yy">
					<a href="{{ url('demand/commit') }}" class="color-FFF hover-light" target="_blank">发布采购需求</a>
				</div>
				<form action="{{ url('mall') }}" method="get">
					<div class="yeehim-search yy">
						<div class="dv-txt zz">
							<input type="text" class="in-txt" name="search_content" placeholder="请输入产品合金牌号/加工状态/品名/铝厂" value="{{ !empty(Input::get('search_content')) ? Input::get('search_content') : '' }}"/>
						</div>
						<div class="dv-btn zz">
							<input type="submit" class="btn-search" value="" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="yeehim-nav">
		<div class="container-grid clearfix">
			<div class="grid-12">
				<div class="nav-sub-title zz font-s-16 yahei">铝材大卖场</div>
				<ul class="nav-sub">
					@foreach ($header as $k=>$v)
					<li @if(str_contains(Request::url(), $k))class="current"@endif
					@if(Request::url() == 'http://localhost' && $k == '')class="current"@endif><a href="{{ url($k) }}">{{ $v }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>