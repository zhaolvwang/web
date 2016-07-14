@extends('panelViews::master')
@section('bodyClass')
login
@stop
@section('body')
    <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    
                    <div class="mes-box ">
                    @if($mesType=='error')<div class="error-box animated fadeInDown"><span class="ic-caution"></span> {{$message}}</div>@endif  
                    @if($mesType=='info') <div class="info-box animated fadeInDown">{!!$message!!}</div>@endif
                    </div>
                    
                    <div class="login-panel panel panel-default animated fadeInDown">
                     
                        <div class="panel-heading">
                            <!-- @if($mesType=='message') <h3 class="panel-title">{{$message}}</h3>  @endif -->
                            <h3 class="panel-title">@lang('panel::fields.signIn')</h3>
                        </div>
                        <div class="panel-body">
                            <div class="logo-holder">
                                <img src="{{asset(Config::get('panel.logo'))}}" width="178"/>
                            </div>
                            {!! Form::open(array('url' => 'panel/login')) !!}
                                <fieldset>
                                    <div class="form-group">
					@if (\Config::get('panel.demo') == true)
						<p><i>Demo Username: admin@change.me</i></p>
					@endif
                                        <input class="form-control" placeholder="{{ \Lang::get('panel::fields.account') }}/{{ \Lang::get('panel::fields.email') }}" name="email" type="text" value="{{ \Cookie::get('account') }}" @if (\Cookie::get('rememberMe') != 1) autofocus
                                            @endif>
                                    </div>
                                    <div class="form-group">
					@if (\Config::get('panel.demo') == true)
						<p><i>Demo Password: 12345</i></p>
					@endif
                                        <input class="form-control" placeholder="{{ \Lang::get('panel::fields.password') }}" name="password" type="password" value="" @if (\Cookie::get('rememberMe') == 1) autofocus
                                            @endif>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="remember" type="checkbox" value="1" @if (\Cookie::get('rememberMe') == 1) checked="checked"
                                            @endif
                                            >
                                            {{ \Lang::get('panel::fields.rememberMe') }}
                                        </label>
                                        <label class="pull-right">
                                            <a href="remind"> {{ \Lang::get('panel::fields.forgetPassword') }} </a>
                                        </label>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <input type="submit"  class="btn btn-lg btn-success btn-block btn-login" value="{{ \Lang::get('panel::fields.login') }} ">
                                </fieldset>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <script type="text/javascript">
	$(document).ready(function(){
		$(".btn-login").click(function(){
			$(this).prop('disabled', true);
			$(this).parents("form").submit();
		});
	});
	
	</script>
@stop

