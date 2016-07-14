@extends('layout')
@section('title')现货供应 - @stop
@section('content')
@include('tool.updateLockSessionAmount')
<link href="{{asset("packages/serverfireteam/panel/css/select2.css")}}" rel="stylesheet" type="text/css">
<link href="{{asset("packages/serverfireteam/panel/css/select2-bootstrap.css")}}" rel="stylesheet" type="text/css">
<script src="{{asset("packages/serverfireteam/panel/js/select2.min.js")}}"></script>
<script src="{{asset("packages/serverfireteam/panel/js/select2_locale_zh-CN.js")}}"></script>
<script type="text/javascript">
jQuery(document).ready(function()
{
	$(".s2example").each(function(){
		var name = $(this).parent().parent().children('label').text();
		if(name == '') {
			name = $(this).parent().parent().children().children('label').text();
		}
		var placeholder = '请选择'+name.replace('*', '').trim();
		if(name.replace('*', '').trim() == '父栏目') {
			placeholder += '；若不选择，则为顶级栏目';
		}
		$(this).select2({
			placeholder: placeholder,
			allowClear: true
		}).on('select2-selecting', function(e)
		{
			$("#mallBtnLock").attr('href', "{{ url('member/trade/insertNewOrder') }}"+"?regBy="+e.val).css({'background-color':'#B61D1D'});
		}).on('select2-removed', function(e)
		{
			$("#mallBtnLock").attr('href', "javascript:;").css({'background-color':'#ccc'});
		})
	});
	
});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$(document).on("click",".btn-del",function(){
		$(this).parent().parent().next(".delete-box").addClass("show");
	});
});
function appendToLock(id, qty, _this){
	@if(!auth()->check())
	location.href = "{{ url('auth/login?redirectPath=mall') }}";
	@endif
	$(_this).attr('disabled', true);
	if(id) {
		if($('#lock-list-'+id).attr('id') == undefined) {
			//$(this).attr('disabled', true);
		/**
		 *	qty:现货供应的数量，应该是现存数量(入库数量-出库数量)-锁货数量
		 */
		$.ajax({
			type:'GET',
			url:'{{ url("ajax/set-lock-session") }}',
			data:'id='+id+'&amount=1&type=&qty='+qty,
			success:function(msg) {
				switch (msg.status) {
					case -1:
						location.reload();
						break;
					case 1:
						
						break;
						
					default:
						updateGoods(msg, '');
						$("#btn-mall-"+id).css({'background-color':'#ccc'});
						$('.no_lock_goods').hide();
						$('.mall-lock .main').show();
						$(".mall-lock .lock-main").append('<div class="lock-list pt10 pb10" id="lock-list-'+id+'">'
								+'<div class="clearfix">'
									+'<div class="zz tit">'+msg.coopManu+' '+msg.pname+'</div>'
									+'<div class="yy font-s-20 yahei color-orange">￥'+msg.oUnitPrc+'</div>'
								+'</div>'
								+'<div class="clearfix rel">'
									+'<div class="zz color-999">'+msg.alloyNum+' '+msg.processStatus+' '+msg.standard+'</div>'
									+'<div class="yy">'
									+'	<div class="spinner quantity" id="'+id+'" qty="'+qty+'"></div>'
									+'</div>'
								+'</div>'
								+'<div class="clearfix rel">'
									+'<div class="zz color-999">'+msg.storehouse+'  /  '+msg.weight+''+msg.unit+'</div>'
									+'<div class="yy">'
										+'<div class="yy"><a href="javascript:void(0);" class="btn-del color-blue hover-line">删除</a></div>'
										+'<div class="yy mr10">可供货数量:<span class="color-red">'+qty+'</span></div>'
									+'</div>'
									+'<div class="delete-box">'
										+'<i class="icon-arrow"></i>'
										+'<p class="mb10">确定要删除吗？</p>'
										+'<input type="button" class="btn-sure" onclick="deleteLockedGoods('+id+')" value="确定"><input type="button" class="btn-cancel" value="取消">'
									+'</div>'
								+'</div>'
							+'</div>'
						);
						$("#"+id).Spinner({value:1, min:1, len:3, max:msg.qty});
						break;
				}
			}
		});
		}
	}
	
}		
function deleteLockedGoods(id) {
	$.ajax({
		type:'GET',
		url:'{{ url("ajax/delete-lock-session") }}',
		data:'id='+id,
		success:function(msg) {
			if(!id) {
				$('.no_lock_goods').show();
				$('.mall-lock .main').hide();
				$(".mall-lock .lock-main .lock-list").remove();
				$('.btn-mall').css({'background-color':'#B61D1D'});
			}
			else {
				$("#lock-list-"+id).remove();
				$('#btn-mall-'+id).css({'background-color':'#B61D1D'});
			}
			updateGoods(msg, '');
		}
	});
}

</script>
<style>
.mall-table tr.even td{background-color: #f5f5f5;}
.mall-table tr.hover td{background-color: #FFFFCE;}
.mall-table tr.even th{background-color: #f5f5f5;}
.mall-table th{height: 44px;}
.mall-table .btn-mall {
    display: block;
    width: 60px;
    height: 24px;
    line-height: 24px;
    background-color: #B61D1D;
    color: #FFF;
    border:0;
    cursor: pointer;
}
</style>
<div class="container-grid clearfix">
		<div class="grid-12">
			<dl class="change-ing clearfix pt15 pb15">
				<dt class="zz color-666" style="margin-top: 3px;">现货供应搜索 > </dt>
				<dd class="zz ml15">
					@if(Input::get('product_matType') != '')
					<a href="javascript:;" rel="nofollow" class="now-select" title="点击可删除" onclick="deleteSearch('product_matType')">分类：{{ Input::get('product_matType') }}<i></i></a>
					@endif
					@if(Input::get('product_alloyNum') != '')
					<a href="javascript:;" rel="nofollow" class="now-select" title="点击可删除" onclick="deleteSearch('product_alloyNum')">合金牌号：{{ Input::get('product_alloyNum') }}<i></i></a>
					@endif
					@if(Input::get('product_processStatus') != '')
					<a href="javascript:;" rel="nofollow" class="now-select" title="点击可删除" onclick="deleteSearch('product_processStatus')">加工状态：{{ Input::get('product_processStatus') }}<i></i></a>
					@endif
					@if(Input::get('product_coopid') != '')
					<a href="javascript:;" rel="nofollow" class="now-select" title="点击可删除" onclick="deleteSearch('product_coopid')">供应厂商：{{ $data['coopManu'][Input::get('product_coopid')] }}<i></i></a>
					@endif
					@if(Input::get('product_matType') != '' || Input::get('product_alloyNum') != '' || Input::get('product_processStatus') != '' || Input::get('product_coopid') != '')
					<a href="javascript:;" class="btn-anew hover-light" onclick="deleteSearch('')">重新筛选</a>
					@endif
				</dd>
			</dl>
			<form method="GET" action="" accept-charset="UTF-8" class="" role="form" id="search_form">
				<input type="hidden" name="product_matType" value="{{ Input::get('product_matType') }}" class="select_search">
				<input type="hidden" name="product_alloyNum" value="{{ Input::get('product_alloyNum') }}" class="select_search">
				<input type="hidden" name="product_processStatus" value="{{ Input::get('product_processStatus') }}" class="select_search">
				<input type="hidden" name="product_coopid" value="{{ Input::get('product_coopid') }}" class="select_search">
				<input type="hidden" name="search" value="1">
			</form>
			<div class="change-box">
				<dl class="clearfix line-solid-bottom pb05">
					<dt class="zz color-666 mt05">分 类</dt>
					<dd class="zz pl10 mt10 search_box">
						<a href="javascript:;"  @if(!Input::get('product_matType'))class="on" @endif onclick="deleteSearch('product_matType')">全部</a>
						@foreach($data['matType'] as $v)
						<a href="javascript:;" class="product_matType @if(Input::get('product_matType') == $v)on @endif">{{ $v }}</a>
						@endforeach
					</dd>
				</dl>
				<dl class="clearfix line-solid-bottom pb05">
					<dt class="zz color-666 mt05">合金牌号</dt>
					<dd class="zz pl10 mt10 search_box">
						<a href="javascript:;" @if(!Input::get('product_alloyNum'))class="on" @endif onclick="deleteSearch('product_alloyNum')">全部</a>
						@foreach($data['alloyNum'] as $v)
						<a href="javascript:;" class="product_alloyNum @if(Input::get('product_alloyNum') == $v)on @endif">{{ $v }}</a>
						@endforeach
					</dd>
				</dl>
				<dl class="clearfix line-solid-bottom pb05">
					<dt class="zz color-666 mt05">加工状态</dt>
					<dd class="zz pl10 mt10 search_box">
						<a href="javascript:;" @if(!Input::get('product_processStatus'))class="on" @endif onclick="deleteSearch('product_product_processStatus')">全部</a>
						@foreach($data['processStatus'] as $v)
						<a href="javascript:;" class="product_processStatus @if(Input::get('product_processStatus') == $v)on @endif">{{ $v }}</a>
						@endforeach
					</dd>
				</dl>
				<dl class="clearfix line-solid-bottom pb05">
					<dt class="zz color-666 mt05">供应厂商</dt>
					<dd class="zz pl10 mt10 search_box">
						<a href="javascript:;" @if(!Input::get('product_coopid'))class="on" @endif onclick="deleteSearch('product_coopid')">全部</a>
						@foreach($data['coopManu'] as $k=>$v)
						<a href="javascript:;" class="product_coopid @if(Input::get('product_coopid') == $k)on @endif" lang="{{ $k }}">{{ $v }}</a>
						@endforeach
					</dd>
				</dl>
			</div>
		</div>
		<div class="grid-9 mall-left mt20">
			{!! $grid !!}
		</div>
		<div class="grid-3 mall-right mt20">
			<div class="mall-lock">
				<div class="tt font-s-16 txt-center yahei pl15 pr15">锁货列表</div>
				@if(auth()->check())
				<div class="font-s-16 txt-center pt80 pb80 yahei no_lock_goods" style="border: solid #EAEAEA; border-width: 1px 1px 1px 1px; @if(count(session('user_locked_goods')) != 0)display:none; @endif">
					您还没有选购任何货物
				</div>
				<div class="main pb20" @if(count(session('user_locked_goods')) == 0)style="display: none;" @endif>
					<div class="lock-main">
					@foreach(session('user_locked_goods') as $k=>$v)
						<div class="lock-list pt10 pb10" id="lock-list-{{ $k }}">
							<div class="clearfix">
								<div class="zz tit">{{ $v['coopManu'] }} {{ $v['pname'] }}</div>
								<div class="yy font-s-20 yahei color-orange">￥{{ $v['oUnitPrc'] }}</div>
							</div>
							<div class="clearfix rel">
								<div class="zz color-999">{{ $v['alloyNum'] }} {{ $v['processStatus'] }} {{ $v['standard'] }}</div>
								<div class="yy">
									<div class="spinner" id="{{ $k }}" qty="{{ $v['qty'] }}"></div>
								</div>
							</div>
							<div class="clearfix rel">
								<div class="zz color-999">{{ $v['storehouse'] }}  /  {{ $v['weight'] }}{{ $v['unit'] }}</div>
								<div class="yy">
									<div class="yy"><a href="javascript:void(0);" class="btn-del color-blue hover-line">删除</a></div>
									<div class="yy mr10">可供货数量:<span class="color-red">{{ $v['qty'] }}</span></div>
								</div>
								<div class="delete-box">
									<i class="icon-arrow"></i>
									<p class="mb10">确定要删除吗？</p>
									<input type="button" class="btn-sure" value="确定" onclick="deleteLockedGoods({{ $k }})"><input type="button" class="btn-cancel" value="取消">
								</div>
							</div>
						</div>
						<script type="text/javascript">
						$("#{{ $k }}").Spinner({value:{{ $v['amount'] }}, min:1, len:5, max:{{ $v['qty'] }}});
						</script>
					@endforeach
					</div>
					<div class="clear-all txt-center yahei font-s-14"><a id="mallBtnClearAll" href="javascript:void(0);" class="hover-line color-blue" onclick="if(confirm('您确定要清空所有货物吗？')) deleteLockedGoods('');">清空所有货物</a></div>
					<p class="txt-center pt15 pb10 yahei font-s-14">
						共计<font class="font-w-b color-orange goods_count"> {{ session('user_locked_goods_count') }} </font>件
							&nbsp;&nbsp;<font class="font-w-b color-orange goods_all_weight">{{ session('user_locked_goods_all_weight') }}</font>吨
							&nbsp;&nbsp;<font class="font-w-b color-orange goods_total">{{ session('user_locked_goods_total') }}</font>元
					</p>
					<p class="txt-center pt10 pb15 yahei font-s-14">
						<select class="s2example" style="width:250px; font-size:12px;"> 
							<option></option>
							@foreach($data['staff'] as $k=>$v)
							<option value="{{ $k }}">{{ $v }}</option>
							@endforeach
						</select>
					</p>
					<label style="display: none;">交易员</label>
					<a href="javascript:;" id="mallBtnLock" class="btn-lock hover-light" style="background-color: #ccc;">立即锁货</a>
				</div>
				@else
				<!-- 没有登录时，显示 -->
				<div class="font-s-16 txt-center pt80 pb80 yahei" style="border: solid #EAEAEA; border-width: 1px 1px 1px 1px;">
					<a href="{{ url('auth/login').'?redirectPath=mall' }}" target="_blank" class="color-blue hover-line">登录</a> 并查看更多货物
				</div>
				@endif
				
				
				
			</div>
		</div>
	</div>
<script type="text/javascript">
$(".mall-table tr:even").addClass("even");
$(".mall-table tr:not(:first)").hover(function(){
	$(this).addClass("hover");
},function(){
	$(this).removeClass("hover");
});
function deleteSearch(type) {
	if(type) {
		$("[name='"+type+"']").val('');
	}
	else {
		$(".select_search").val('');
	}
	$("#search_form").submit();
}
$('.search_box a').click(function(){
	var _this = $(this);
	if(_this.index() == 0) {
		
	}
	else {
		if(!_this.hasClass('on')) {
			if(trim(_this.attr('class')) == 'product_coopid') {
				$("[name='"+trim(_this.attr('class'))+"']").val(_this.attr('lang'));
			}
			else {
				$("[name='"+trim(_this.attr('class'))+"']").val(_this.text());
			}
			$("#search_form").submit();
		}
	}
});
</script>
@stop
