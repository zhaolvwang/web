@extends('layout2')

@section('title')资源单上传@stop
@section('content')
<style>
.member-common-form dd.fail, .member-common-form dd.tip{display:none; }
</style>
@include('tool.select2')
<script src="{{asset("packages/serverfireteam/panel/css/multi-select.css")}}"></script>
<script src="{{asset("packages/serverfireteam/panel/js/jquery.multi-select.js")}}"></script>
<script type="text/javascript">
function coopManuSelect2() {
	$(".s2example-2").select2({
		placeholder: '请选择主营厂商',
		allowClear: true
	}).on('change', function(e){
		$("[name='coopManu']").val(e.val);
	});
}
jQuery(document).ready(function($)
{
	coopManuSelect2();
	$(".s2example-22").select2({
		placeholder: '请选择主营品种',
		allowClear: true
	}).on('change', function(e){
		$("[name='variety']").val(e.val);
	}).on('select2-selecting', function(e){
		@foreach($coopManu as $k=>$v)
		if(e.val == '{{ $k }}') {	//<option value="{{ $k }}">{{ $k }}</option>
			$(".s2example-2").append('<optgroup label="{{ $k }}" style="display:none;"></optgroup>');
			@foreach($v as $v1)
			$(".s2example-2 optgroup").last().append('<option value="{{ $v1 }}">{{ $v1 }}</option>');
			@endforeach
		}
		@endforeach
		//coopManuSelect2();
	}).on('select2-removed', function(e)
	{
		$(".s2example-2 optgroup").each(function(){
			if($(this).attr('label') == e.val) {
				$(this).remove();
			}
		});
	});
	
});
</script>
<style>
.malitple-select{width:430px;padding:1px 0px;}
</style>
<div class="register-main clearfix">
	<div class="register-left zz">
		{!! Form::model(new \App\ResourceList(), array('url'=>url('resource/upload'), 'id'=>'form1')) !!}
			<input type="hidden" name="uid" value="{{ Auth::id() }}">
			<div class="mt60 mb60">
				<h3 class="font-s-16 yahei font-w-n mt60 mb20">填写资源单信息</h3>
				<dl class="member-common-form clearfix mb20">
					<dt class="zz">
						<span class="color-orange">*</span>&nbsp;<font class="color-999">公司名称：</font>
					</dt>
					<dd class="zz">
						{!! Form::text('cmpy', null, ['class'=>'in-txt required']) !!}
					</dd>
					<dd class="fail zz"><i class="sign_icon"></i>请输入公司名称</dd>
				</dl>
				<dl class="member-common-form clearfix mb20">
					<dt class="zz">
						<span class="color-orange">*</span>&nbsp;<font class="color-999">联系人：</font>
					</dt>
					<dd class="zz">
						{!! Form::text('contact', null, ['class'=>'in-txt required']) !!}
					</dd>
					<dd class="fail zz"><i class="sign_icon"></i>请输入联系人</dd>
				</dl>
				<dl class="member-common-form clearfix mb20">
					<dt class="zz">
						<span class="color-orange">*</span>&nbsp;<font class="color-999">主营品种：</font>
					</dt>
					<dd class="zz">
						<select class="malitple-select s2example-22" multiple>
							@foreach($coopManu as $k=>$v)
							<option value="{{ $k }}">{{ $k }}</option>
							@endforeach
						</select>
						<input type="hidden" name="variety" class="required">
					</dd>
					<dd class="fail zz" style="margin: 10px 0 0 100px;"><i class="sign_icon"></i>请输入主营品种</dd>
				</dl>
				<dl class="member-common-form clearfix mb20">
					<dt class="zz">
						<span class="color-orange">*</span>&nbsp;<font class="color-999">主营厂商：</font>
					</dt>
					<dd class="zz">
						<select class="malitple-select s2example-2" multiple>
						</select>
						<input type="hidden" name="coopManu" class="required">
					</dd>
					<dd class="fail zz" style="margin: 10px 0 0 100px;"><i class="sign_icon"></i>请输入主营厂商</dd>
				</dl>
				<dl class="member-common-form clearfix mb20">
					<dt class="zz color-666">
						<span class="color-orange">*</span>&nbsp;<font class="color-999">公司所在地：</font>
					</dt>
					<dd class="zz">
						<select name="province" id="s_province" class="in-txt required" style="width: 150px;">
						</select>
					</dd>
					<dd class="zz ml10">
						<select name="city" id="s_city" class="in-txt required" style="width: 190px;">
						</select>
					</dd>
					<dd class="zz ml10">
						<select name="county" id="s_county" class="in-txt required" style="width: 150px;">
						</select>
					</dd>
					<dd class="fail zz" style="margin: 10px 0 0 100px;"><i class="sign_icon"></i>请选择公司所在地</dd>
				</dl>
				<dl class="member-common-form clearfix mb20">
					<dt class="zz">
						<font class="color-999">资源单描述：</font>
					</dt>
					<dd class="zz">
						<textarea name="remarks" id="" cols="30" rows="10" class="in-area line-h-22" placeholder="对你今天的价格变化、优势资源、货物状态等用文字补充说明，可增加你资源单的吸引力。字数限定在80字以内。" style="width: 430px;">{{ old('remarks') }}</textarea>
					</dd>
				</dl>
				<dl class="member-common-form clearfix mb05">
					<dt class="zz">
						<span class="color-orange">*</span>&nbsp;<font class="color-999">上传资源单：</font>
					</dt>
					<dd class="zz">
					</dd>
				</dl>
				<div class="clearfix">
					<div class="zz up-load-layer rel">
						<input type="file" class="in-file" onchange="document.getElementById('upfileResult').innerHTML=this.value" name="file" id="upload_file">
						<div class="tip yahei font-s-14 txt-over" id="upfileResult">文件名</div>
					</div>
					<div class="zz ml10"><input type="button" class="btn-file hover-light font-s-16 upload_btn" value="上  传"></div>
					<dl class="member-common-form clearfix mb05">
						<span>
							<input type="hidden" class="required" name="annex" id="fileName" value="{{ old('annex') }}">
							<input type="hidden" class="required" name="annexName" id="originalName" value="{{ old('annexName') }}">
						</span>
						<dd class="fail zz"><i class="sign_icon"></i>请上传资源单</dd>
					</dl>
				</div>
				<p class="color-light-red mt10 uploadSuccess" style="display: none;">文件上传成功，等待审核！</p>
				<dl class="member-common-form clearfix mt40">
					<dt class="zz">
						&nbsp;
					</dt>
					<dd class="zz ml80">
						<input type="submit" class="btn-submit hover-light" style="background-color: #E4393C;" value="提交">
					</dd>
				</dl>
			</div>
		{!! Form::close() !!}
	</div>
	<div class="register-right zz mt60">
		<p class="line-h-26 color-999">1、您上传的资源单会在<a href="{{ url('resource') }}" target="_blank" class="color-light-red hover-line">【资源单下载】</a>栏目，有助于其他用户快速搜索到您的资源单。</p>
		<p class="line-h-26 color-999">2、请准确填写品种的分类，分类会出现在<a href="{{ url('resource') }}" target="_blank" class="color-light-red hover-line">【资源单下载】</a>栏目展示。同时网站会自动解析您上传的资源单。</p>
		<p class="line-h-26 color-999">3、网站根据某些规则解析资源单里面的内容，参考下面的资源样式，有助于其他用户在【现货搜索】栏目搜索到您完整的资源。</p>
		<div class="clearfix mt15">
			<span class="zz"><a href="/public/uploads/resource/resource_template.xlsx" target="_blank" class="hover-orange">资源单推荐样式.xlsx</a></span>
			<span class="yy"><a href="/public/uploads/resource/resource_template.xlsx" target="_blank" class="color-blue hover-line">下载查看</a></span>
		</div>
		<h3 class="font-s-14 color-666 line-dashed-top mt40 pt40">有任何问题请联系客服</h3>
		<div class="clearfix">
			<p class="mt15 font-s-14 zz">电话：<font class="color-light-red font-w-b">0511-87059936</font></p>
			<p class="yy">
				<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2385847714&amp;site=qq&amp;menu=yes" title="点击在线沟通" target="_blank" class="c qq-service"></a>
				<!-- <a href="###" target="_blank" class="qq-service"></a> -->
			</p>
		</div>
	</div>
</div>
<script type="text/javascript">
$(".required").change('blur', function(){
	requiredValidate(this);
});
function trim(str) { //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, ""); //把空格替换为空
}
function showFail(_this) {
	return $(_this).css({'border-color':'red'}).addClass('error').parent().siblings('.fail').show();
}
function showSuccess(_this) {
	return $(_this).removeAttr('style').removeClass('error').parent().siblings('.fail').hide();
}
function requiredValidate(_this) {
	var val = $(_this).val();
	if(val == '') {
		showFail(_this);
		return false;
	}
	else {
		return true;
	}
}
$("#form1").submit(function(){
	var flag = true;
	$(".required").each(function(){
		if(!requiredValidate(this)) {
			flag = false;
			return false;
		} else {
			flag = true;
			showSuccess(this);
		}
	});
	if(!flag)
		return false;
});
@if (count($errors) > 0)
	@foreach ($errors->getMessages() as $k=>$error)
	showFail($("[name='{{ $k }}']"));
	@endforeach
@endif
</script>
<script src="{{ asset('packages/ajaxfileupload/ajaxfileupload.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(".upload_btn").click(function(){
	var _this=this;
	var fileid="upload_file";
	var name='resource';
	var id='{{ auth()->user()->id }}';
	$.ajaxFileUpload ({
		url:"{{ asset('public/uploads/doajaxfileupload.php') }}?id="+id+"&name="+name,
		secureuri:false,
		fileElementId:fileid,
		dataType: 'json',
		success: function (data, status)
		{
			if(data) {
	           	$("#fileName").val(data.fileName);
	           	$("#originalName").val(data.originalName);
	           	$(".uploadSuccess").show();
            }else{
				alert("系统错误，请重试！");
            } 
		},
		error: function (data, status, e)
		{
			alert(e);
		}
	});

	//return false;
});
</script>
<!-- Province City County -->
     @include('panelViews::area')
     <script type="text/javascript">_init_area();</script>
@stop
