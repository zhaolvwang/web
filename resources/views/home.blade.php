@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>
@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
	@elseif (!empty($import_message))
	<div class="alert alert-success">{{ $import_message }}</div>
@else
<br>
@endif
				
				{!! Form::model(Auth::user(), array('class'=>'form-horizontal', 'files' => true)) !!}
				
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('panel::fields.mobile')</label>
						<label class="col-md-6 control-label" style="text-align: left;">
							{{ Auth::user()->mobile }}
						</label>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.uname')</label>
						<div class="col-md-6">
							{!! Form::text('uname', null, array('class' => 'form-control')) !!}
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.cmpy')</label>
						<div class="col-md-6">
							{!! Form::text('cmpy', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('panel::fields.email')</label>
						<div class="col-md-6">
							{!! Form::text('email', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.tel')</label>
						<div class="col-md-6">
							{!! Form::text('tel', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.sex')</label>
						<div class="col-md-6">
							<label style="padding-top: 6px;">{!! Form::radio('sex', '男') !!}男</label>
							<label style="padding-top: 6px;">{!! Form::radio('sex', '女') !!}女</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.idenNum')</label>
						<div class="col-md-6">
							{!! Form::text('idenNum', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.fax')</label>
						<div class="col-md-6">
							{!! Form::text('fax', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.postalcode')</label>
						<div class="col-md-6">
							{!! Form::text('postalcode', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.address')</label>
						<div class="col-md-6">
							{!! Form::text('address', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.qq')</label>
						<div class="col-md-6">
							{!! Form::text('qq', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.taxNum')</label>
						<div class="col-md-6">
							{!! Form::text('taxNum', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.orgCode')</label>
						<div class="col-md-6">
							{!! Form::text('orgCode', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.regPic')</label>
						<div class="col-md-6">
							<img src="{{ '/public/uploads/images/'.Auth::user()->id.'/'.Auth::user()->regPic }}">
							{!! Form::file('regPic', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.taxPic')</label>
						<div class="col-md-6">
							<img src="{{ '/public/uploads/images/'.Auth::user()->id.'/'.Auth::user()->taxPic }}">
							{!! Form::file('taxPic', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.orgPic')</label>
						<div class="col-md-6">
							<img src="{{ '/public/uploads/images/'.Auth::user()->id.'/'.Auth::user()->orgPic }}">
							{!! Form::file('orgPic', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.bank')</label>
						<div class="col-md-6">
							{!! Form::text('bank', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.bcNum')</label>
						<div class="col-md-6">
							{!! Form::text('bcNum', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">@lang('auth.bankAddr')</label>
						<div class="col-md-6">
							{!! Form::text('bankAddr', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									@lang('panel::fields.change')
								</button>
							</div>
						</div>
					{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection
