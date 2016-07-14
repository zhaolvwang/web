@extends('member.member')

@section('content')
<script src="{{asset("packages/member/images/My97DatePicker/WdatePicker.js")}}"></script>
<!-- Jquery InputMask Core JavaScript -->
<script src="{{asset("packages/serverfireteam/panel/js/jquery.inputmask.bundle.js")}}"></script>
<script src="{{asset("packages/serverfireteam/panel/js/xenon-custom.js")}}"></script>
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
@include('tool.select2')
<div class="member-tab">
	<span class="current"><a href="javascript:;">发布采购</a><i></i></span>
</div>
@if (count($errors) > 0)
<div class="alert alert-danger mt15">
	<ul class="common-txt-list-28 font-s-14">
	@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>

@endif
{!! Form::model(new \App\DemandInfo(), array('url'=>url('demand/release'))) !!}
	<input type="hidden" name="dinum" value="{{ 'D'.time() }}">
	<input type="hidden" name="uid" value="{{ auth()->user()->id }}">
	<div class="send-layer mt20 clearfix">
		<dl class="zz mb15" style="width: 820px;">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;产品名称：
			</dt>
			<dd class="zz">
				<input type="text" class="in-txt" name="pname" value="{{ old('pname') }}"/>
			</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;品种：
			</dt>
			<dd class="zz">
				{!! Form::select('variety', $data['variety'], old('variety'), ['class'=>'in-txt']) !!}
			</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<label><span class="color-orange">*</span>&nbsp;合金</label>：
			</dt>
			<dd class="zz">
				<select name="alloyNum" id="alloyNum" class="in-txt s2example" style="padding: 0">
				@foreach($data['alloyNum'] as $k=>$v)
					<option value="{{ $v }}">{{ $v }}</option>
				@endforeach
				</select>
			</dd>
		</dl>
		@if (old('alloyNum'))
		<script>
		$("#alloyNum").val("{{ old('alloyNum') }}").trigger("change");
		</script>
		@endif
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;加工状态：
			</dt>
			<dd class="zz">
			{!! Form::select('processStatus', $data['processStatus'], old('processStatus'), ['class'=>'in-txt']) !!}
			</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;规格：
			</dt>
			<dd class="zz">
				<input type="text" class="in-txt" name="standard"/>
			</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;厂家：
			</dt>
			<dd class="zz">
				{!! Form::select('coopManu', $data['coopManu'], old('coopManu'), ['class'=>'in-txt']) !!}
			</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;单位件重：
			</dt>
			<dd class="zz">
				<input type="text" class="in-txt" name="unitWeight" data-mask="fdecimal" data-dec="" data-rad="." maxlength="15"/>
			</dd>
			<dd class="zz color-999">（吨）</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;意向价格：
			</dt>
			<dd class="zz">
				<input type="text" class="in-txt" data-mask="fdecimal" data-dec="" data-rad="." maxlength="15" name="intentPrc" value="{{ old('intentPrc') }}"/>
			</dd>
			<dd class="zz color-999">（元/吨）</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;数量：
			</dt>
			<dd class="zz">
				<input type="text" class="in-txt" data-mask="fdecimal" data-dec="" data-rad="." maxlength="15" name="qty" value="{{ old('qty') }}"/>
			</dd>
			<dd class="zz color-999">（吨）</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;配送方式：
			</dt>
			<dd class="zz">
			{!! Form::select('disMode', ['卖家送货'=>'卖家送货','买家自提'=>'买家自提'], old('disMode'), ['class'=>'in-txt']) !!}
			</dd>
		</dl>
		<dl class="zz mb15" style="width: 820px;">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;收货地：
			</dt>
			<dd class="zz">
				<select name="province" id="s_province" class="in-txt" style="width: 210px;">
				</select>
			</dd>
			<dd class="zz ml10">
				<select name="city" id="s_city" class="in-txt" style="width: 210px;">
				</select>
			</dd>
			<dd class="zz ml10">
				<select name="county" id="s_county" class="in-txt" style="width: 210px;">
				</select>
			</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;付款时间：
			</dt>
			<dd class="zz">
				<input class="in-txt" style="width: 240px;" name="payDays" data-mask="fdecimal" data-dec="" data-rad="" maxlength="15" value="{{ old('payDays') }}"/>
			</dd>
			<dd class="zz color-999">（天）</dd>
		</dl>
		<dl class="zz mb15">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;交货时间：
			</dt>
			<dd class="zz">
				<input id="d4311" class="Wdate in-txt" type="text" onClick="WdatePicker({minDate:'%y-%M-%d'})" style="width: 240px;" name="deliveryDt" readonly="readonly" value="{{ old('deliveryDt') }}"/>
			</dd>
		</dl>
		<dl class="zz mb15" style="width: 820px;">
			<dt class="zz">
				<span class="color-orange">*</span>&nbsp;需求内容：
			</dt>
			<dd class="zz">
				<textarea name="content" id="" cols="30" rows="10" class="in-area">{{ old('content') }}</textarea>
			</dd>
		</dl>
		<dl class="zz mt25" style="width: 820px;">
			<dt class="zz">
				<input type="submit" class="btn-submit hover-light" value="马上发布" />
			</dt>
		</dl>
	</div>
{!! Form::close() !!}
<!-- Province City County -->
     @include('panelViews::area')
     <script type="text/javascript">_init_area();</script>
@stop
