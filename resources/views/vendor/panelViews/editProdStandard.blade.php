
@extends('panelViews::mainTemplate')
@section('page-wrapper')

<div class="alert-box success">
        <h2>@lang('panel::fields.edit')@lang('panel::fields.prodStandard')</h2>
    </div>
@if (!empty($import_message))
	<div>&nbsp;</div>
	<div class="alert alert-success">{{ $import_message }}</div>
@endif
<div class="row">
    <div class="" >

{!!
 Form::open(['url' => url('panel/ProdStandard/update')])
!!}

@foreach ($data as $d)
{!! Form::label($d['label']) !!}
<input type="text" class="form-control" data-role="tagsinput" name="{{ $d['name'] }}" value="{{ $d['data'] }}" style="display: none;">
<br />
@endforeach


{!! Form::submit(Lang::get('panel::fields.update'), array('class' => 'btn btn-primary')) !!}

{!! Form::close() !!}

  </div>    
</div>
    
@stop   