@extends('member.member')

@section('content')
<link rel="stylesheet" href="{{ asset('packages/member/images/theme.css') }}">
<script type="text/javascript" src="{{ asset('packages/member/images/theme.js') }}"></script>
<style>
.alert {
  padding: 15px;
  margin-bottom: 20px;
  border: 1px solid transparent;
  border-radius: 4px;
}
.alert h4 {
  margin-top: 0;
  color: inherit;
}
.alert .alert-link {
  font-weight: bold;
}
.alert > p + p {
  margin-top: 5px;
}
.alert-success {
  background-color: #dff0d8;
  border-color: #d6e9c6;
  color: #3c763d;
}
.alert-success hr {
  border-top-color: #c9e2b3;
}
.alert-success .alert-link {
  color: #2b542c;
}
.alert > p,
.alert > ul {
  margin-bottom: 0;
  list-style-type: disc;
}
.alert > ul {
  margin-left: 20px;
}
.alert > p + p {
  margin-top: 5px;
}
.alert-danger {
  background-color: #f2dede;
  border-color: #ebccd1;
  color: #a94442;
}
.alert-danger hr {
  border-top-color: #e4b9c0;
}
.alert-danger .alert-link {
  color: #843534;
}
</style>
<div class="member-tab">
	<span class="current"><a href="{{ url('member/account/index') }}">帐户信息<i></i></a></span>
	<span class="line-ver"></span>
	<span><a href="{{ url('member/account/integral') }}">我的积分<i></i></a></span>
	<span class="line-ver"></span>
	<span><a href="{{ url('member/account/security') }}">帐户安全<i></i></a></span>
</div>
<div class="account-main pt40 pb40 pl40 pr40 clearfix">
@if (count($errors) > 0)
						<div class="alert alert-danger">
							<ul class="common-txt-list-28 font-s-14">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
	@elseif (!empty($import_message))
	<div class="alert alert-success font-s-14">修改成功</div>
@else
<br>
@endif
	<div class="account-left zz">
		<a href="{{ url('member/account/avatar') }}"><div class="tit"><span href="javascript:;" class="color-FFF hover-line">编辑头像</span></div>
		<div class="img"><img src="{{ $headPic }}" alt="" width="150" height="150" /></div>
		<div class="cover"></div></a>
	</div>
	<div class="account-right zz">
		{!! Form::model(Auth::user(), array('url'=>url('home'), 'class'=>'form-horizontal', 'files' => true)) !!}
			{!! Form::hidden('cmpy') !!}
			<div class="member-common-form clearfix">
				<dl class="zz mb15">
					<dt class="zz">
						手机：
					</dt>
					<dd class="zz dd-txt">
						<p class="pl10">{{ $data->mobile }}</p>
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						公司：
					</dt>
					<dd class="zz dd-txt">
						<p class="pl10">{{ $data->cmpy }}</p>
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.uname')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('uname', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						邮箱：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('email', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						固话：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('tel', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.sex')：
					</dt>
					<dd class="zz dd-txt">
						<label style="padding-top: 6px;">{!! Form::radio('sex', '男') !!}&nbsp;男</label>&nbsp;&nbsp;
						<label style="padding-top: 6px;">{!! Form::radio('sex', '女') !!}&nbsp;女</label>
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.idenNum')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('idenNum', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.fax')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('fax', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.postalcode')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('postalcode', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						地址：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('address', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						QQ：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('qq', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.taxNum')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('taxNum', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.orgCode')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('orgCode', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.regPic')：
					</dt>
					<dd class="zz dd-txt">
						<img src="@if(Auth::user()->regPic){{ '/public/uploads/images/'.Auth::user()->id.'/'.Auth::user()->regPic }} @endif">
						{!! Form::file('regPic', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.taxPic')：
					</dt>
					<dd class="zz dd-txt">
						<img src="@if(Auth::user()->regPic){{ '/public/uploads/images/'.Auth::user()->id.'/'.Auth::user()->taxPic }} @endif">
						{!! Form::file('taxPic', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.orgPic')：
					</dt>
					<dd class="zz dd-txt">
						<img src="@if(Auth::user()->regPic){{ '/public/uploads/images/'.Auth::user()->id.'/'.Auth::user()->orgPic }} @endif">
						{!! Form::file('orgPic', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.bank')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('bank', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.bcNum')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('bcNum', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mb15">
					<dt class="zz">
						@lang('auth.bankAddr')：
					</dt>
					<dd class="zz dd-txt">
						{!! Form::text('bankAddr', null, array('class' => 'in-txt')) !!}
					</dd>
				</dl>
				<dl class="zz mt25">
					<dt class="zz">
						&nbsp;
					</dt>
					<dd class="zz">
						<input type="submit" class="btn-submit hover-light" value="保  存">
					</dd>
				</dl>
			</div>
		{!! Form::close() !!}
	</div>
</div>

<div class="theme-popover theme-mail">
     <div class="theme-poptit">
          <a href="javascript:;" title="关闭" class="close theme-close">×</a>
          <h3 class="font-s-14 font-w-n color-666">修改邮箱</h3>
     </div>
     <div class="theme-popbod dform pt40">
		<form action="">
			<div class="member-common-form">
				<dl class="clearfix">
					<dt class="zz color-666">
						邮箱地址：
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="in-txt">
					</dd>
				</dl>
				<dl class="mt40 clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="btn-submit hover-light" value="修改">
					</dd>
				</dl>
			</div>
		</form>
     </div>
</div>
<div class="theme-popover theme-phone">
     <div class="theme-poptit">
          <a href="javascript:;" title="关闭" class="close phone-close">×</a>
          <h3 class="font-s-14 font-w-n color-666">修改手机号</h3>
     </div>
     <div class="theme-popbod dform pt40">
		<form action="">
			<div class="member-common-form">
				<dl class="clearfix">
					<dt class="zz color-666">
						手机号：
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="in-txt">
					</dd>
				</dl>
				<dl class="mt40 clearfix">
					<dt class="zz color-666">
						&nbsp;
					</dt>
					<dd class="zz dd-txt">
						<input type="text" class="btn-submit hover-light" value="修改">
					</dd>
				</dl>
			</div>
		</form>
     </div>
</div>
<div class="theme-popover-mask"></div>
@stop
