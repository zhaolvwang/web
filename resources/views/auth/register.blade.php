@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="mobile" value="{{ \Session::get('mobile') }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('panel::fields.mobile') }}</label>
							<label class="col-md-6 control-label" style="text-align: left;">
								{{ \Session::get('mobile') }}
							</label>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('auth.uname') }}</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="uname" value="{{ old('uname') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('auth.cmpy') }}</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="cmpy" value="{{ old('cmpy') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('panel::fields.password') }}</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('auth.rePassword') }}</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									{{ trans('panel::fields.register') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
