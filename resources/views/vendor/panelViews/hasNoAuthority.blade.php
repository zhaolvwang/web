@extends('panelViews::mainTemplate')
@section('page-wrapper')

<div class="alert-box success">
        <h2>{{ \Lang::get('panel::fields.hasNoAuthority') }}</h2>
    </div>

<div class="row">
    <div class="col-xs-4" >
<h4></h4>
<button class="btn btn-primary" onclick="window.history.go(-1);">@lang("panel::fields.back")</button>

  </div>    
</div>
    
@stop   
