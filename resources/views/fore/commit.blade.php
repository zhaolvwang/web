@extends('layout2')

@section('title')发布采购需求@stop
@section('content')
<style>
.member-common-form dd.fail, .member-common-form dd.tip{display:none; margin-top: 10px; margin-left: 100px;}
</style>
<div class="purchase-main">
	<div class="txt-center mt40 mb40"><img src="{{ asset('packages/fore/images/purchase.jpg') }}" alt="" class="margin-auto" /></div>
	<form role="form" method="POST" action="{{ url('demand/commit') }}" id="commit_form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="margin-auto" style="width: 610px;">
			
			<dl class="member-common-form clearfix mb20">
				<dt class="zz">
					<span class="color-orange">*</span>&nbsp;手机号码：
				</dt>
				<dd class="zz">
					<input type="text" class="in-txt" style="width: 490px; " placeholder="请输入正确的手机号码" name="mobile" @if(auth()->check()) value="{{ auth()->user()->mobile }}" readonly="readonly" @endif value="{{ old('mobile') }}">
				</dd>
			</dl>
			@if (!auth()->check())
			<!-- <dl class="member-common-form clearfix mb20">
				<dt class="zz">
					<span class="color-orange">*</span>&nbsp;<font>短信验证码：</font>
				</dt>
				<dd class="zz">
					<input type="text" class="codeWidth in-txt required" name="msgCode">
				</dd>
				<dd class="zz">
					<a href="javascript:;" class="code">获取验证码</a>
				</dd>
				<dd class="fail zz"><i class="sign_icon"></i>请输入短信验证码</dd>
				<dd class="tip zz"><i class="sign_icon"></i>已发送，验证码10分钟内有效</dd>
			</dl> -->
			@endif
			<dl class="member-common-form clearfix">
				<dt class="zz">
					<span class="color-orange">*</span>&nbsp;需求内容：
				</dt>
				<dd class="zz">
					<textarea name="content" id="" class="in-area line-h-24" style="width: 490px; height: 250px;" placeholder="写下您的真实需求，包括牌号、品类和数量等，收到后我们会立即给您回电确认，剩下就交给我们吧">{{ $content ? $content : old('content') }}</textarea>
				</dd>
			</dl>
		</div>
		<div class="margin-auto mt60 mb60" style="width: 200px;">
			 <input type="submit" class="btn-submit" value="提交需求" />
		</div>
	</form>
</div>
<script type="text/javascript">
@if ($errors->has('mobile'))alert('请填写正确的手机号码！');$("[name='mobile']").focus(); 
@elseif ($errors->has('content'))alert('请填写需求内容！');$("[name='content']").focus(); @endif
$("#commit_form").submit(function(){
	if($("[name='content']").val() == "") {
		//alert('请输入需要内容');
		//$("[name='content']").focus();
		//return false;
	}
});
</script>
@stop
