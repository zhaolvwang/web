@extends('layout') @section('content')

<script type="text/javascript">

	$(function() {

		//Tooltips

		$(".tip-trigger").hover(function(){

			tip = $(this).find(".tip");

			tip.show();

		}, function() {

			tip.hide();	  

		}).mousemove(function(e) {

			var mousex = e.pageX + 20;

			var mousey = e.pageY + 20;

			var tipWidth = tip.width();

			var tipHeight = tip.height();

			

			var tipVisX = $(window).width() - (mousex + tipWidth);

			var tipVisY = $(window).height() - (mousey + tipHeight);

			  

			if ( tipVisX < 20 ) { 

				mousex = e.pageX - tipWidth - 20;

			} if ( tipVisY < 20 ) {

				mousey = e.pageY - tipHeight - 20;

			} 

			tip.css({  top: mousey, left: mousex });

		});

	});

</script>

<div class="idn-top">

	<div class="container-grid clearfix">

		<div class="grid-12">

			<div class="yeehim-global-nav zz">

				<ul id="global_nav">

					<li class="global-main">

						<div class="bg">
							<a href="" target="_blank"></a>
						</div>

						<h3 class="tit yahei font-s-16 rel font-w-n">
							铝卷<i class="arrow"></i>
						</h3>

						<div class="pl15 yahei color-666">
							昨日成交 <font class="color-orange font-w-b">47972.946</font> 吨
						</div> <!-- 铝卷 -->

						<div class="global-sub global-sub-juan" style="display: none;">

							<div class="global-sub-links clearfix">

								<span><a href="{{ url('mall?search_content=铝单板') }}"
									target="_blank" class="color-999 hover-orange">铝单板</a></span> <span><a
									href="{{ url('mall?search_content=冷轧卷') }}" target="_blank"
									class="color-999 hover-orange">冷轧卷 </a></span>

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['alloyNum'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['processStatus'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								<h3 class="yahei font-s-16 rel font-w-n line-solid-bottom pb10">铝厂直达</h3>

								@foreach($data['lvjuan'] as $k=>$v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

						</div> <!-- 铝卷 -->

					</li>

					<li class="global-main">

						<div class="bg">
							<a href="" target="_blank"></a>
						</div>

						<h3 class="tit yahei font-s-16 rel font-w-n">
							<a href="" target="_blank" class="hover-orange">铝板</a><i
								class="arrow"></i>
						</h3>

						<div class="pl15 yahei color-666">
							昨日成交 <font class="color-orange font-w-b">47972.946</font> 吨
						</div> <!-- 铝板 -->

						<div class="global-sub global-sub-ban" style="display: none;">

							<div class="global-sub-links clearfix">

								<span><a href="{{ url('mall?search_content=铝单板') }}"
									target="_blank" class="color-999 hover-orange">铝单板</a></span> <span><a
									href="{{ url('mall?search_content=铝合金压花板') }}" target="_blank"
									class="color-999 hover-orange">铝合金压花板</a></span> <span><a
									href="{{ url('mall?search_content=箱式汽车外蒙铝板') }}"
									target="_blank" class="color-999 hover-orange">箱式汽车外蒙铝板</a></span>

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['alloyNum'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['processStatus'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								<h3 class="yahei font-s-16 rel font-w-n line-solid-bottom pb10">铝厂直达</h3>

								@foreach($data['lvban'] as $k=>$v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

						</div> <!-- 铝板 -->

					</li>

					<li class="global-main">

						<div class="bg">
							<a href="" target="_blank"></a>
						</div>

						<h3 class="tit yahei font-s-16 rel font-w-n">
							<a href="" target="_blank" class="hover-orange">铝箔</a><i
								class="arrow"></i>
						</h3>

						<div class="pl15 yahei color-666">
							昨日成交 <font class="color-orange font-w-b">47972.946</font> 吨
						</div> <!-- 铝箔 -->

						<div class="global-sub global-sub-bo" style="display: none;">

							<div class="global-sub-links clearfix">

								<span><a href="{{ url('mall?search_content=亲水铝箔') }}"
									target="_blank" class="color-999 hover-orange">亲水铝箔</a></span>

								<span><a href="{{ url('mall?search_content=空调铝箔') }}"
									target="_blank" class="color-999 hover-orange">空调铝箔 </a></span>

								<span><a href="{{ url('mall?search_content=汽车铝箔') }}"
									target="_blank" class="color-999 hover-orange">汽车铝箔</a></span>

								<span><a href="{{ url('mall?search_content=干式变压器铝箔') }}"
									target="_blank" class="color-999 hover-orange">干式变压器铝箔</a></span>

								<span><a href="{{ url('mall?search_content=防腐保温铝箔') }}"
									target="_blank" class="color-999 hover-orange">防腐保温铝箔</a></span>

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['alloyNum'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['processStatus'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								<h3 class="yahei font-s-16 rel font-w-n line-solid-bottom pb10">铝厂直达</h3>

								@foreach($data['lvbo'] as $k=>$v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

						</div> <!-- 铝箔 -->

					</li>

					<li class="global-main">

						<div class="bg">
							<a href="" target="_blank"></a>
						</div>

						<h3 class="tit yahei font-s-16 rel font-w-n">
							<a href="" target="_blank" class="hover-orange">型材</a><i
								class="arrow"></i>
						</h3>

						<div class="pl15 yahei color-666">
							昨日成交 <font class="color-orange font-w-b">47972.946</font> 吨
						</div> <!-- 型材 -->

						<div class="global-sub global-sub-cai" style="display: none;">

							<div class="global-sub-links clearfix">

								<span><a href="{{ url('mall?search_content=铝管') }}"
									target="_blank" class="color-999 hover-orange">铝管</a></span> <span><a
									href="{{ url('mall?search_content=铝排') }}" target="_blank"
									class="color-999 hover-orange">铝排</a></span> <span><a
									href="{{ url('mall?search_content=铝棒') }}" target="_blank"
									class="color-999 hover-orange">铝棒</a></span> <span><a
									href="{{ url('mall?search_content=铝圆片') }}" target="_blank"
									class="color-999 hover-orange">铝圆片</a></span> <span><a
									href="{{ url('mall?search_content=铝锻件') }}" target="_blank"
									class="color-999 hover-orange">铝锻件</a></span> <span><a
									href="{{ url('mall?search_content=铝铸件') }}" target="_blank"
									class="color-999 hover-orange">铝铸件</a></span> <span><a
									href="{{ url('mall?search_content=铝压铸件') }}" target="_blank"
									class="color-999 hover-orange">铝压铸件</a></span> <span><a
									href="{{ url('mall?search_content=铝杆') }}" target="_blank"
									class="color-999 hover-orange">铝杆</a></span> <span><a
									href="{{ url('mall?search_content=铝线') }}" target="_blank"
									class="color-999 hover-orange">铝线</a></span>

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['alloyNum'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								@foreach($data['processStatus'] as $v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

							<div class="global-sub-links clearfix">

								<h3 class="yahei font-s-16 rel font-w-n line-solid-bottom pb10">铝厂直达</h3>

								@foreach($data['xingcai'] as $k=>$v) <span><a
									href="{{ url('mall?search_content='.$v) }}" target="_blank"
									class="color-999 hover-orange">{{ $v }}</a></span> @endforeach

							</div>

						</div> <!-- 型材 -->

					</li>

					<li class="global-main">

						<div class="bg"></div>

						<h3 class="tit yahei font-s-16 rel font-w-n">
							<a href="" target="_blank" class="hover-orange">铝厂专区</a><i
								class="arrow"></i>
						</h3>

						<div class="links pl15 pr15">

							<a href="" target="_blank" class="color-999 hover-orange">苏州华盛</a><span
								class="color-999">&nbsp;|&nbsp;</span> <a href=""
								target="_blank" class="color-999 hover-orange">吴江万航</a>

						</div> <!-- 铝厂专区 -->

						<div class="global-sub global-sub-cang" style="display: none;">

							@foreach($data['coopManu'] as $k=>$v)

							<div class="global-sub-links clearfix">

								<h3 class="yahei font-s-16 rel font-w-n line-solid-bottom pb10">{{
									$k }}</h3>

								@foreach($v as $k1=>$v1) <span><a
									href="{{ url('mall?search_content='.$v1) }}" target="_blank"
									class="color-999 hover-orange">{{ $v1 }}</a></span> @endforeach

							</div>

							@endforeach

						</div> <!-- 铝厂专区 -->

					</li>

				</ul>

				<script type="text/javascript">

						$("#global_nav").slide({  type:"menu", titCell:".global-main", targetCell:".global-sub", delayTime:0, triggerTime:0, defaultPlay:false, returnDefault:true });

					</script>

			</div>

			<div class="yeehim-banner zz">{!! $data['indexImg']->content !!}</div>
			<div class="zz idn-hangqing">
					<div class="bd">
						<div class="hangqing-con">
							<div class="hangqing-layer">
								<div class="hangqing-main">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tb-list">
										<tr>
											<td valign="middle" align="center" width="19%">市场品种</td>
											<td valign="middle" align="center" width="25%">价格区间</td>
											<td valign="middle" align="center" width="11%">均价</td>
											<td valign="middle" align="center" width="11%">涨跌</td>
											<td valign="middle" align="center" width="20%">五日均价</td>
											<td valign="middle" align="center" width="14%">日期</td>
										</tr>
										@foreach($data['metalMarket'] as $metal)
										@if(!$metal->lowPrc)
										<tr>
											<td valign="middle" align="center" width="19%">{{ $metal->pname }}</td>
											<td valign="middle" align="center" width="25%">{{ $metal->topPrc1 }}</td>
											<td valign="middle" align="center" width="11%">{{ (int)$metal->avgPrc }}</td>
											<td valign="middle" align="center" width="11%">
												<p class="
												@if($metal->upDownType == 1)
												up 
												@else 
												down
												@endif">{{ (int)$metal->upDown }}</p>
											</td>
											<td valign="middle" align="center" width="20%">{{ (int)$metal->avgPrc5 }}</td>
											<td valign="middle" align="center" width="14%">
												<p class="date color-999">{{ substr($metal->dt, 5, 5) }}</p>
											</td>
										</tr>
										@endif
										@endforeach
									</table>
								</div>
							</div>
							<div class="hangqing-layer">
								<div class="hangqing-main">
									<h3 class="font-s-16 font-w-n yahei pt10 pb10 txt-center">{{ substr($data['metalMarket'][0]->dt, 5, 2) }}月{{ substr($data['metalMarket'][0]->dt, 8, 2) }}日长江有色金属行情</h3>
									<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tb-list">
										<tr>
											<td valign="middle" align="center" width="10%">品名</td>
											<td valign="middle" align="center" width="19%">最高价</td>
											<td valign="middle" align="center" width="19%">最低价</td>
											<td valign="middle" align="center" width="19%">均价</td>
											<td valign="middle" align="center" width="19%">涨跌</td>
											<td valign="middle" align="center" width="14%">日期</td>
										</tr>
										@foreach($data['metalMarket'] as $metal)
										@if($metal->lowPrc)
										<tr>
											<td valign="middle" align="center" width="10%">{{ $metal->pname }}</td>
											<td valign="middle" align="center" width="19%">{{ (int)$metal->topPrc }}</td>
											<td valign="middle" align="center" width="19%">{{ (int)$metal->lowPrc }}</td>
											<td valign="middle" align="center" width="19%">{{ (int)$metal->avgPrc }}</td>
											<td valign="middle" align="center" width="19%">
												<p class="
												@if($metal->upDownType == 1)
												up 
												@else 
												down
												@endif">{{ (int)$metal->upDown }}</p>
											</td>
											<td valign="middle" align="center" width="14%">
												<p class="date color-999">{{ substr($metal->dt, 5, 5) }}</p>
											</td>
										</tr>
										@endif
										@endforeach
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="hangqing-scoll hd">
						<ul>
							<li class="on">1</li>
							<li>2</li>
						</ul>
					</div>
				</div>

		</div>

	</div>

</div>

<div class="container-grid clearfix">

	<div class="grid-12">

		<form class="search_area mt20 clearfix"
			action="{{ url('demand/commit') }}" method="get">

			<div class="dv-txt zz">

				<input type="text" class="in-txt" name="content"
					placeholder="写下您的真实需求，包括规格、材质等，收到后我们会立即给您回电确认，剩下的就交给我们吧。">

			</div>

			<div class="dv-btn zz">

				<input type="submit" class="btn-search" value="免费找货">

			</div>

			<div class="zz">
				<img src="{{asset('packages/fore/images/telephone.gif')}}" alt="" />
			</div>

		</form>

	</div>

</div>

@foreach($data['products'] as $k=>$v)

<div class="container-grid">

	<div class="yeehim-common-layer layer-juan clearfix mt20">

		<div class="top grid-12 rel">

			<h3 class="zz font-s-24 font-s-n txt-center pb05 yahei">{{ $k }}</h3>

		</div>

		<div class="grid-3 common-layer-left mt10">

			<div class="tt font-s-16 txt-center color-FFF">最新求购信息</div>

			<div class="main">

				<table border="0" cellspacing="0" cellpadding="0" width="100%"
					class="table-left">

					@foreach($v['buy'] as $buy)

					<tr>

						<td valign="middle" align="center" width="33.33%">

							<p>{{ $buy->pname }} {{ $buy->qty }}吨</p>

							<p class="color-999">{{ $buy->alloyNum }} {{ $buy->processStatus
								}}</p>

						</td>

						<td valign="middle" align="center" width="33.33%">

							<p class="yahei font-s-16 color-orange">￥{{ $buy->intentPrc }}</p>

						</td>

						<td valign="middle" align="center" width="33.33%"><a
							href="{{ url('purchase?id='.$buy->id) }}" target="_blank"
							class="send-price hover-light">我要报价</a></td>

					</tr>

					@endforeach

				</table>

				<script type="text/javascript">

						$(".table-left tr:even").addClass("even");

					</script>

			</div>

		</div>

		<div class="grid-6 common-layer-center mt10">

			<div class="tt clearfix">

				<div class="zz font-s-16">最新现货资源</div>

				<div class="yy">
					<a href="{{ url('/mall?product_matType='.$k.'&search=1') }}"
						target="_blank" class="color-999 hover-orange">查看更多货源 ></a>
				</div>

			</div>

			<div class="main">

				<table border="0" cellspacing="0" cellpadding="0" width="100%"
					class="table-center">

					<tr>

						<td valign="middle" align="center" width="10%">品名</td>

						<td valign="middle" align="center" width="15%">铝厂</td>

						<td valign="middle" align="center" width="10%">合金牌号</td>

						<td valign="middle" align="center" width="15%">加工状态</td>

						<td valign="middle" align="center" width="10%">单位</td>

						<td valign="middle" align="center" width="10%">单价</td>

						<td valign="middle" align="center" width="10%">数量</td>

						<td valign="middle" align="center" width="10%">时间</td>

						<td valign="middle" align="center" width="10%">操作</td>

					</tr>

					@foreach($v['goods'] as $goods) {{--*/ $qty = $goods->qty -
					\App\ProdQty::where('inid', $goods->id)->sum('qty') -
					$goods->lockedQty; /*--}} @if($qty != 0)

					<tr>

						<td valign="middle" align="center" width="10%">{{
							$goods->product->pname }}</td>

						<td valign="middle" align="center" width="15%">{{
							$goods->product->coopManu->cname }}</td>

						<td valign="middle" align="center" width="10%">{{
							$goods->product->alloyNum }}</td>

						<td valign="middle" align="center" width="15%">{{
							$goods->product->processStatus }}</td>

						<td valign="middle" align="center" width="15%">{{
							$goods->product->unit }}</td>

						<td valign="middle" align="center" width="10%">

							<p class="yahei font-s-16 color-orange">￥{{
								$goods->product->oUnitPrc }}</p>

						</td>

						<td valign="middle" align="center" width="10%">{{ $qty }}</td>

						<td valign="middle" align="center" width="10%">{{
							substr($goods->created_at, 5, 5) }}</td>

						<td valign="middle" align="center" width="10%"><a
							href="{{ url('mall?product_matType='.$k.'&product_alloyNum='.$goods->product->alloyNum.'&product_processStatus='.$goods->product->processStatus.'&product_coopid='.$goods->product->coopid.'&search=1') }}"
							target="_blank" class="color-blue hover-line">查看</a></td>

					</tr>

					@endif @endforeach

				</table>

				<script type="text/javascript">

						$(".table-center tr:even").addClass("even");

					</script>

			</div>

		</div>

		<div class="grid-3 common-layer-right mt10">

			<div class="tt font-s-16 txt-center"
				style="background-color: #F5F5F5;">交易记录</div>

			<div class="main">

				<table border="0" cellspacing="0" cellpadding="0" width="100%"
					class="table-right">

					<tr>

						<td valign="middle" align="center" width="25%">采购内容</td>

						<td valign="middle" align="center" width="35%">价格（元/吨）</td>

						<td valign="middle" align="center" width="25%">交易状态</td>

						<td valign="middle" align="center" width="15%">时间</td>

					</tr>

				</table>

				<ul class="record-data">

					@foreach($v['trade'] as $trade)

					<li class="tip-trigger">

						<div class="tip">

							<div class="clearfix">

								<div class="zz">采购内容</div>
								<div class="yy">{{ $trade->oiStatus }}</div>

							</div>

							<p class="color-666">{{ $trade->demand->pname }}【{{
								$trade->demand->variety }}】 {{ $trade->demand->alloyNum }} {{
								$trade->demand->processStatus }} {{ $trade->demand->standard }}
								{{ $trade->demand->disMode }} {{
								$trade->demand->qty*$trade->demand->unitWeight }}吨&nbsp;￥{{
								$trade->demand->intentPrc }}&nbsp;</p>

						</div> <span class="buy-tit">{{ $trade->demand->alloyNum }}</span>

						<span class="buy-price">

							<p class="yahei font-s-14 color-orange">￥{{
								$trade->demand->intentPrc }}</p>

					</span> <span class="buy-status"> @if($trade->oiStatus == '待付款')

							<p>待付款</p> @else

							<p class="color-orange">交易成功</p> @endif

					</span> <span class="buy-time">

							<p class="color-999">{{ substr($trade->created_at, 11, 5) }}</p>

					</span>

					</li> @endforeach

				</ul>

			</div>

		</div>

		<div class="grid-12">

			<div class="company-list clearfix">

				<div class="tit zz txt-center font-s-18 yahei">推荐铝厂</div>

				@foreach($v['logo'] as $logo)

				<div class="img zz">
					<a href="" target="_blank"><img
						src="{{ url('public/uploads/coopManu/'.$logo->logo) }}" alt="" /></a>
				</div>

				@endforeach

			</div>

		</div>

	</div>

</div>

@endforeach









<div class="container-grid mt20">

	<div class="container-floor clearfix">

		<div class="floor-top grid-12"></div>

		<div class="grid-5 floor-service pl15 pt15 pr15">

			<h3 class="font-s-16 font-w-n yahei line-solid-bottom pb10">客户经理免费找货</h3>

			@foreach($data['freeGoods'] as $free)
			<div class="service-list clearfix mt15 line-solid-bottom pb15 rel">

				<div class="img zz">
					<img src="{{ url('public/uploads/freeGoods/'.$free->avatar) }}" alt=""
						width="80" height="80" />
				</div>

				<div class="name zz ml15">

					<p class="font-s-14">{{ $free->name }}</p>

					<p class="font-s-14">{{ $free->mobile }}</p>

				</div>

				<div class="info zz ml15">

					<p class="color-666 line-h-24">主要服务：{{ $free->service }}</p>

					<p class="color-666 line-h-24">年限：{{ $free->descp }}</p>

				</div>

				<div class="qq">
					<a href=""></a>
				</div>

			</div>
			@endforeach
			

		</div>

		<div class="grid-7 floor-news pt15">

			<div class="clearfix line-solid-bottom pb10 ml15 mr15">

				<h3 class="font-s-16 font-w-n yahei zz">资讯动态</h3>

				<div class="yy">
					<a href="{{ url('news') }}" target="_blank"
						class="hover-orange color-999">更多 ></a>
				</div>

			</div>

			<ul class="clearfix ul-txt-over">

				@foreach($data['news'] as $v)

				<li><i class="icon"></i><a href="{{ url('news/article/'.$v->id) }}"
					target="_blank" title="{{ $v->title }}" class="hover-orange">{{
						$v->title }}</a></li> @endforeach

			</ul>

		</div>

	</div>

</div>
<div class="float-layer clearfix">
	<div class="zz layer-left"></div>
	<div class="zz layer-right">
		<div class="layer-top yahei font-s-16 color-FFF txt-center">快速找货</div>
		<div class="layer-main">
			<div class="layer-form">
				{!! Form::open(['url'=>url('demand/commit'), 'id'=>'form22']) !!}
					<div class="mt10">
						<input type="text" class="layer-txt" placeholder="手机号" name="mobile" id="mobile22" value="@if(auth()->check()){{trim(auth()->user()->mobile)}}@endif"/>
					</div>
					<div class="mt10">
						<input type="text" class="layer-txt" placeholder="一句话需求" name="content" id="content22"/>
					</div>
					<div class="mt10">
						<input type="submit" class="layer-submit" id="layer-submit" value="提交" style="background-color: #ccc;border: 1px solid #ccc;"/>
					</div>
				</form>
				<script type="text/javascript">
					$('#mobile22').blur(function(){
						if($('#mobile22').val() == '' || $('#content22').val() == '') {
							$("#layer-submit").css({'background-color':'#ccc', 'border':'1px solid #ccc'});
						}
						else {
							$("#layer-submit").css({'background-color':'#FF9900', 'border':'1px solid #FF9900'});
						}
					});
					$('#content22').change(function(){
						if($('#mobile22').val() == '' || $('#content22').val() == '') {
							$("#layer-submit").css({'background-color':'#ccc', 'border':'1px solid #ccc'});
						}
						else {
							$("#layer-submit").css({'background-color':'#FF9900', 'border':'1px solid #FF9900'});
						}
					});
					$("#form22").submit(function(){
						if($('#mobile22').val() == '' || $('#content22').val() == '') {
							return false;
						}
					});
					@if ($errors->has('mobile'))alert('请填写正确的手机号码！');@endif
				</script>
			</div>
			<div class="layer-service mt20 pt20">
				<div class="pic"><img src="{{ asset('packages/fore/images/layer_service.gif') }}" alt="" /></div>
				<div class="layer-qq txt-center mt20 mb20"><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2385847714&amp;site=qq&amp;menu=yes" title="点击在线沟通" target="_blank" class="c"><img src="{{ asset('packages/fore/images/layer_qq.gif') }}" alt="" class="margin-auto" /></a></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(".layer-left").click(function(){
			var _width = $(".layer-right").width();
			if(_width <= 0)
			{
				$(".layer-left").addClass("on");
				$(".layer-right").animate({width: 135}, 'swing');
			}
			else
			{
				$(".layer-left").removeClass("on");
				$(".layer-right").animate({width: 0}, 'swing');
			}
		});
	</script>
</div>
<script type="text/javascript">
		$(".idn-hangqing").slide({ mainCell:".hangqing-con", effect:"left"});
	</script>
@stop
