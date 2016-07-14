<div class="member-tab">
	<span @if(str_contains(Request::url(), 'index') && !str_contains(Request::url(), 'index_'))class="current"@endif><a href="{{ url('member/purchase/index') }}">全部<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::url(), 'index_check'))class="current"@endif><a href="{{ url('member/purchase/index_check') }}?verifyStatus=审核中&search=1">审核中<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::url(), 'index_pass'))class="current"@endif><a href="{{ url('member/purchase/index_pass') }}?verifyStatus=审核通过&search=1">审核通过<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::url(), 'index_nopass'))class="current"@endif><a href="{{ url('member/purchase/index_nopass') }}?verifyStatus=审核未通过&search=1">审核未通过<i></i></a></span>
	<span class="line-ver"></span>
	<span @if(str_contains(Request::url(), 'index_deal'))class="current"@endif><a href="{{ url('member/purchase/index_deal') }}?verifyStatus=已成交&search=1">已成交<i></i></a></span>
</div>
{!! $filter !!}


@if (count($grid->rows))
	 {!! $grid !!}
@else
<div class="member-data-none">
		暂无匹配数据
	</div>	
@endif
 

