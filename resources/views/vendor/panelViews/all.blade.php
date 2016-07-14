@extends('panelViews::mainTemplate')
@section('page-wrapper')

<div class="row">
    <div class="col-xs-4" >
<h3>
@if ($current_entity == 'Product')
	产品基本信息库
@elseif ($current_entity == 'ProductPerf')	
	产品性能参数库
@elseif ($current_entity == 'ProductPrice')	
	产品价格库
@elseif ($current_entity == 'ProductPack')	
	产品包装库
@elseif ($current_entity == 'ProductIn')	
	产品入库记录
@elseif ($current_entity == 'ProductOut')	
	产品出库记录
@elseif ($current_entity == 'ProductStatus')	
	产品状态
@endif

@if ($method == 'addProduct')
	添加订单产品
@endif
@if (!empty($label_title))
	{{ $label_title }}
	@if (!empty($orderInfo))
	@if ($orderInfo->exists)
	<div>&nbsp;</div>
	<div class="alert alert-success" style="margin-bottom: 0;">{{ $orderInfo->oinum }}</div>
	@else
	<div>&nbsp;</div>
	<div class="alert alert-danger" style="margin-bottom: 0;">没有匹配的订单编号：{{ $orderInfo->oinum }}</div>
	@endif
	@endif
@endif
</h3>
</div>    
</div> 
@if (!empty($filter))
{!! $filter !!}

<br>
	@endif

<!-- <a href="{!! url('panel/'.$current_entity.'/export/excel') !!}" class="btn btn-primary">{!! \Lang::get('panel::fields.exportAsExcel') !!}</a>
<button class="btn btn-primary" data-toggle="modal" data-target="#import_modal">{!! \Lang::get('panel::fields.importData') !!}</button> -->

@if ($current_entity == 'Admin' && \Auth::user()->groupId == 0)
	<div class="btn-toolbar" role="toolbar">
	<div class="pull-left">
	@foreach ($data as $c)
	<a href="?aauthId={{ $c['id'] }}&search=1" class="btn btn-primary">{{ $c['name'] }}</a>
	@endforeach
	</div>
    </div>
@endif
@if (stripos($current_entity, 'Product') !== false)
	<div class="btn-toolbar" role="toolbar">
	<div class="pull-left">
        <a href="{{ url('panel/Product/all') }}" class="btn btn-primary">产品基本信息库</a>
		<a href="{{ url('panel/ProductPerf/all') }}" class="btn btn-primary">产品性能参数库</a>
		<a href="{{ url('panel/ProductPrice/all') }}" class="btn btn-primary">产品价格库</a>
		<a href="{{ url('panel/ProductPack/all') }}" class="btn btn-primary">产品包装库</a>
		<a href="{{ url('panel/ProductIn/all') }}" class="btn btn-primary">产品入库记录</a>
		<a href="{{ url('panel/ProductOut/all') }}" class="btn btn-primary">产品出库记录</a>
		<a href="{{ url('panel/ProductStatus/all') }}" class="btn btn-primary">产品状态</a>
    </div>
    </div>
	
@endif

@if ($method == 'addProduct')
	已选购货品
	<table class="shopcart_tbl table" border="0" style="width: 60%;">
	<thead>
        			<tr>
        				<th>{{ trans('panel::fields.pnum') }}</th>
        				<th>{{ trans('panel::fields.batchNum') }}</th>
        				<th>{{ trans('panel::fields.pname') }}</th>
        				<th>{{ trans('panel::fields.standard') }}</th>
        				<th>{{ trans('panel::fields.storehouse') }}</th>
        				<th>{{ trans('panel::fields.unitWeight') }}</th>
        				<th>{{ trans('panel::fields.price') }}</th>
        				<th>{{ trans('panel::fields.quantity') }}</th>
        				<th></th>
        			</tr>
        			</thead>
		@if (!empty(session('shopcart_admin')))
		@foreach (session('shopcart_admin') as $k=>$v)
		@if (isset($v['pnum']))
		<tr>
			<td>{{ $v['pnum'] }}</td>
			<td>{{ $v['batchNum'] }}</td>
			<td>{{ $v['pname'] }}</td>
			<td>{{ $v['standard'] }}</td>
			<td>{{ $v['storehouse'] }}</td>
			<td>{{ $v['weight'] }}</td>
			<td>{{ $v['oUnitPrc'] }}</td>
			<td class="{{ $k }}">{{ $v['inputQty'] }}</td>
			<td><input type="button" value="删除" onclick='deleteShopcartQty(this, {{ $k }}, {{ $v['pnum'] }})'></td>
		</tr>
		@endif
		@endforeach
		@endif
	</table>
@endif

<!-- Modal -->
<div class="modal fade" id="import_modal" tabindex="-1" role="dialog" aria-labelledby="import_modal_label" aria-hidden="true">
	<div class="modal-dialog">
	        <div class="modal-content">
	                <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="import_modal_label">{!! \Lang::get('panel::fields.importData') !!}</h4>
                        </div>
			<form method="post" action="{!! url('panel/'.$current_entity.'/import') !!}" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	                        <div class="modal-body">
					<div><input type="file" name="import_file" /></div>
					<br />
					<div>
						<input type="radio" name="status" id="status_1" value="1" checked="checked" />&nbsp;
						<label for="status_1">{!! \Lang::get('panel::fields.deletePreviousData') !!}</label><br />
						<input type="radio" name="status" id="status_2" value="2" />&nbsp;
						<label for="status_2">{!! \Lang::get('panel::fields.keepOverwriteData') !!}</label><br />
						<input type="radio" name="status" id="status_3" value="3" />&nbsp;
						<label for="status_3">{!! \Lang::get('panel::fields.keepNotOverwriteData') !!}</label><br />
					</div>
                                </div>
                                <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{!! \Lang::get('panel::fields.close') !!}</button>
                                            <button type="submit" class="btn btn-primary">{!! \Lang::get('panel::fields.importData') !!}</button>
                                </div>
			</form>
		</div>
	</div>
</div>

@if ($import_message)
	<div>&nbsp;</div>
	<div class="alert alert-success">{!! $import_message !!}</div>
@endif

{!! $grid !!}

@stop   
