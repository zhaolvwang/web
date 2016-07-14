<div class="yeehim-foot mt40 pt40 pb40">
		<div class="container-grid clearfix">
			@foreach($footer_column as $k=>$v)
			<div class="grid-2 foot-link">
				<dl>
					<dt class="font-w-b font-s-14 mb10">{{ $k }}</dt>
					@foreach($v as $k1=>$v1)
					<dd class="mt05"><a href="{{ url('text/page/'.$k1) }}" target="_blank" class="color-666 hover-line">{{ $v1 }}</a></dd>
					@endforeach
				</dl>
			</div>
			@endforeach
			<div class="yy">
				<div class="zz foot-contact">
					<div class="font-w-b font-s-14">联系我们</div>
					<p class="color-666"><i class="tel"></i>0511-87059936</p> 
					<p class="color-666"><i class="loc"></i>江苏镇江丹徒区谷阳大道18号</p>
				</div>
				<div class="code zz ml20"><img src="{{asset('packages/fore/images/code.gif')}}" alt="" /></div>
			</div>
			@include('footer_common')
		</div>
	</div>
