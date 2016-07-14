@extends('member.member')

@section('content')
<link rel="stylesheet" href="{{ asset('packages/member/images/theme.css') }}">
<script type="text/javascript" src="{{ asset('packages/member/images/theme.js') }}"></script>
<script type="text/javascript">
function cancelAttention(id) {
	$('.theme-popover-mask').fadeIn(100);
	$('.theme-popover').slideDown(200);
	$("[name='id']").val(id);
}
jQuery(document).ready(function($) {
	$('.layer-close').click(function(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-popover').slideUp(200);
	})

})
</script>
<div class="member-tab">
						<span><a href="{{ url('member/resource/index') }}">我的资源单<i></i></a></span>
						<span class="line-ver"></span>
						<span class="current"><a href="">我的关注<i></i></a></span>
					</div>
					{!! $filter !!}
					{!! $grid !!}
					<script type="text/javascript">
						$(".member-data-table tr:eq(0)").addClass("bg");
						$(".member-data-table tr:not(:first)").hover(function(){
							$(this).addClass("hover");
						},function(){
							$(this).removeClass("hover");
						});
					</script>
					<div class="theme-popover">
     <div class="theme-poptit">
          <a href="javascript:;" title="关闭" class="close layer-close">×</a>
          <h3 class="font-s-14 font-w-n color-666">取消关注</h3>
     </div>
     <div class="theme-popbod dform">
		{!! Form::open(['url'=>url('member/resource/cancelAttention')]) !!}
			<input type="hidden" name="id">
			<div class="tips margin-auto" style="width: 300px;">
				<i class="tp-icon"></i>
				<p class="font-s-16 yahei" style=" padding-left: 50px;">取消关注吗？</p>
				<p class="font-s-14 color-999 yahei" style=" padding-left: 50px;">取消后可到资源单下载中重新关注</p>
				<input type="submit" class="button-orange mt30" value="取消关注">
				<input type="button" class="button-gry mt30 layer-close" value="不取消">
			</div>
		</form>
     </div>
</div>
<div class="theme-popover-mask"></div>
@stop
