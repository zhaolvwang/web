@extends('panelViews::master')
@section('bodyClass')
dashboard
@stop
@section('body')


{{--*/     
      
 /*--}}         
       
    <div class="loading">
        <h1> LOADING </h1>
        <div class="spinner">
          <div class="rect1"></div>
          <div class="rect2"></div>
          <div class="rect3"></div>
          <div class="rect4"></div>
          <div class="rect5"></div>
        </div>
    </div>

    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top " role="navigation" style="margin-bottom: 0">
            
            <!-- /.navbar-header -->
             <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed btn-resp-sidebar" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                
              </div>

            
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar " role="navigation">
                <div class="sidebar-nav navbar-collapse collapse " id="bs-example-navbar-collapse-1">
                      <div class="grav center"><img src="{{ auth()->user()->pic1 ? '/uploads/website/avatar/'.auth()->user()->pic1 : asset("packages/serverfireteam/panel/img/04fa25a4a8bd128c54303d99b4fbb66a.png") }}" width="110"><a href="#"><span> {{ \Lang::get('panel::fields.change') }}</span></a></div>
                      <div class="user-info" style="font-size: 16px;">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</div>
                      <div class="user-info">{{\Session::get('levelGroup')}}</div>
                      <a class="visit-site" href="{{$app['url']->to('/')}}" target="_blank">{{ \Lang::get('panel::fields.visiteSite') }}  </a>
                      <ul class="nav" id="side-menu">
                              <!-- <li class="{{ (Request::url() === url('panel')) ? 'active' : '' }}">
                                  <a  href="{{ url('panel') }}" ><i class="fa fa-dashboard fa-fw"></i> {{ \Lang::get('panel::fields.dashboard') }}</a>
                              </li> -->
                              
                              {{--*/ $links  = \Serverfireteam\Panel\Link::all(); /*--}}

                               @foreach($links as $key => $value )
@if ($value['main'] == -2 && auth()->user()->groupId <= 9 && auth()->user()->groupId != 0)
	@include('panelViews::links')
@endif                        
@if ($value['main'] == -2 && auth()->user()->groupId == 0)
	@include('panelViews::links')
@endif                        
@if ($value['main'] >= 0)
	@if (auth()->user()->groupId == 0)
		@include('panelViews::links')
	@else 
		@if (str_contains($value['main'].'', auth()->user()->aauthId.''))
			@include('panelViews::links')
		@endif
	@endif
@endif
                               @endforeach
                      </ul>     
                      
                        </li>
                    </ul>
                </div>
               
             
            </div>
            <!-- /.navbar-static-side -->
        </nav>
       <!--  <div class="powered-by"><a href="https://laravelpanel.com">Thank you for using LaravelPanel.</a></div>  -->
        <div id="page-wrapper">
            

            <!-- Menu Bar -->
            <div class="row">
                <div class="col-xs-12 text-a top-icon-bar">
                    <div class="btn-group" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                            <a  type="button" class="btn btn-default dropdown-toggle main-link" data-toggle="dropdown" aria-expanded="false">
                                {{ Lang::get('panel::fields.settings') }} 
                                <span class="caret fl-right"></span>
                            </a>
                          <ul class="dropdown-menu" role="menu">
                            <li><a href="{{url('panel/edit?modify=').\Auth::user()->id}}"><span class="icon  ic-users "></span>{{ Lang::get('panel::fields.ProfileEdit') }}</a></li>
                           <!--  <li><a href="{{url('panel/changePassword')}}"><span class="icon ic-cog"></span>{{ Lang::get('panel::fields.ResetPassword') }}</a></li> -->
                          </ul>
                        </div>
                        <a href="{{url('panel/logout')}}" type="button" class="btn btn-default main-link">{{ Lang::get('panel::fields.logout') }}<span class="icon  ic-switch fl-right"></span></a>
                      </div>
                </div>
            </div>
            
            @yield('page-wrapper')
            
        </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
@stop

