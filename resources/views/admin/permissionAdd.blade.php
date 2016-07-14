@extends('Admin.layout2')
@section('title')权限添加-权限管理@endsection
@section('content')
 <div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
   <script type="text/javascript">
    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
   </script>

   <ul class="breadcrumb">
    <li>
     <i class="icon-home home-icon"></i>
     <a href="{{url('admin/index')}}">首页</a>
    </li>
    <li class="active"> <a href="{{action('Admin\PermissionController@getList')}}">权限管理</a></li>
   </ul><!-- .breadcrumb -->

  </div>

  <div class="page-content">
   <div class="page-header">
    <h1>
     权限管理
     <small>
      <i class="icon-double-angle-right"></i>
      添加
     </small>
    </h1>
   </div><!-- /.page-header -->

   <div class="row">
    <div class="col-xs-12">
     <!-- PAGE CONTENT BEGINS -->

     <div class="row">
      <div class="col-xs-12">
       <form class="form-horizontal" role="form" method="post" action="{{action('Admin\PermissionController@postAdd')}}" id="addPermissionForm">
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 名称 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="操作/菜单名称" class="col-xs-10 col-sm-5" name="permission_name">
         </div>
        </div>

        <div class="space-4"></div>

        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 上级菜单 </label>

         <div class="col-sm-9">
          <select id="permission_parent_id" name="permission_parent_id">
           <option value="0">一级菜单</option>
           {{--<option value="1">订单列表</option>--}}
           {{--<option value="2">权限列表</option>--}}
           {{--<option value="3">用户列表</option>--}}
           @foreach($tree as $key=>$value)
            <option value="{{$value['permission_id']}}" style="font-weight: bolder;">{{$value['permission_name']}}</option>
             @if(isset($value['menu']) && !empty($value['menu']))
               @foreach($value['menu'] as $k=>$v)
             <option value="{{$v['permission_id']}}">&nbsp;&nbsp;{{$v['permission_name']}}</option>
               @endforeach
             @endif
             {{--<option value="5">商品添加</option>--}}

            @endforeach
          </select>

         </div>
        </div>

        <div class="space-4"></div>

        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 类型 </label>

         <div class="col-sm-9">
          <select name="type">
            <option value="菜单">菜单</option>
            <option value="操作">操作</option>
          </select>

         </div>
        </div>

        <div class="space-4"></div>



        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 菜单/操作代码 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-2" placeholder="菜单/操作代码" class="col-xs-10 col-sm-5" name="permission_code">
          <span class="help-inline col-xs-12 col-sm-7">
          <span class="middle red icon-bell">如果添加的是一级菜单，此处为空</span>
          </span>
         </div>
        </div>
        <div class="space-4"></div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="clearfix form-actions">
         <div class="col-md-offset-3 col-md-9">
          <button class="btn btn-info" type="button" id="submitBtn">
           <i class="icon-ok bigger-110"></i>
           提交
          </button>

          &nbsp; &nbsp; &nbsp;
          <button class="btn" type="reset">
           <i class="icon-undo bigger-110"></i>
           重置
          </button>
         </div>
        </div>

       </form>
      </div><!-- /span -->
     </div><!-- /row -->

    </div><!-- /.row -->
   </div><!-- /.page-content -->
  </div><!-- /.main-content -->

 </div>
@endsection

@section('footer')

 <script type="text/javascript">
  $(function(){
   $('#submitBtn').click(function(){
    $("form").submit();
   });
  })

  jQuery(function($) {
   $('.easy-pie-chart.percentage').each(function(){
    var $box = $(this).closest('.infobox');
    var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
    var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
    var size = parseInt($(this).data('size')) || 50;
    $(this).easyPieChart({
     barColor: barColor,
     trackColor: trackColor,
     scaleColor: false,
     lineCap: 'butt',
     lineWidth: parseInt(size/10),
     animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
     size: size
    });
   })

   $('.sparkline').each(function(){
    var $box = $(this).closest('.infobox');
    var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
    $(this).sparkline('html', {tagValuesAttribute:'data-values', type: 'bar', barColor: barColor , chartRangeMin:$(this).data('min') || 0} );
   });




   var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
   var data = [
    { label: "social networks",  data: 38.7, color: "#68BC31"},
    { label: "search engines",  data: 24.5, color: "#2091CF"},
    { label: "ad campaigns",  data: 8.2, color: "#AF4E96"},
    { label: "direct traffic",  data: 18.6, color: "#DA5430"},
    { label: "other",  data: 10, color: "#FEE074"}
   ]
   function drawPieChart(placeholder, data, position) {
    $.plot(placeholder, data, {
     series: {
      pie: {
       show: true,
       tilt:0.8,
       highlight: {
        opacity: 0.25
       },
       stroke: {
        color: '#fff',
        width: 2
       },
       startAngle: 2
      }
     },
     legend: {
      show: true,
      position: position || "ne",
      labelBoxBorderColor: null,
      margin:[-30,15]
     }
     ,
     grid: {
      hoverable: true,
      clickable: true
     }
    })
   }
   drawPieChart(placeholder, data);

   /**
    we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
    so that's not needed actually.
    */
   placeholder.data('chart', data);
   placeholder.data('draw', drawPieChart);



   var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
   var previousPoint = null;

   placeholder.on('plothover', function (event, pos, item) {
    if(item) {
     if (previousPoint != item.seriesIndex) {
      previousPoint = item.seriesIndex;
      var tip = item.series['label'] + " : " + item.series['percent']+'%';
      $tooltip.show().children(0).text(tip);
     }
     $tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
    } else {
     $tooltip.hide();
     previousPoint = null;
    }

   });

   var d1 = [];
   for (var i = 0; i < Math.PI * 2; i += 0.5) {
    d1.push([i, Math.sin(i)]);
   }

   var d2 = [];
   for (var i = 0; i < Math.PI * 2; i += 0.5) {
    d2.push([i, Math.cos(i)]);
   }

   var d3 = [];
   for (var i = 0; i < Math.PI * 2; i += 0.2) {
    d3.push([i, Math.tan(i)]);
   }


   var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
   $.plot("#sales-charts", [
    { label: "Domains", data: d1 },
    { label: "Hosting", data: d2 },
    { label: "Services", data: d3 }
   ], {
    hoverable: true,
    shadowSize: 0,
    series: {
     lines: { show: true },
     points: { show: true }
    },
    xaxis: {
     tickLength: 0
    },
    yaxis: {
     ticks: 10,
     min: -2,
     max: 2,
     tickDecimals: 3
    },
    grid: {
     backgroundColor: { colors: [ "#fff", "#fff" ] },
     borderWidth: 1,
     borderColor:'#555'
    }
   });


   $('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
   function tooltip_placement(context, source) {
    var $source = $(source);
    var $parent = $source.closest('.tab-content')
    var off1 = $parent.offset();
    var w1 = $parent.width();

    var off2 = $source.offset();
    var w2 = $source.width();

    if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
    return 'left';
   }


   $('.dialogs,.comments').slimScroll({
    height: '300px'
   });


   //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
   //so disable dragging when clicking on label
   var agent = navigator.userAgent.toLowerCase();
   if("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
    $('#tasks').on('touchstart', function(e){
     var li = $(e.target).closest('#tasks li');
     if(li.length == 0)return;
     var label = li.find('label.inline').get(0);
     if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
    });

   $('#tasks').sortable({
            opacity:0.8,
            revert:true,
            forceHelperSize:true,
            placeholder: 'draggable-placeholder',
            forcePlaceholderSize:true,
            tolerance:'pointer',
            stop: function( event, ui ) {//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
             $(ui.item).css('z-index', 'auto');
            }
           }
   );
   $('#tasks').disableSelection();
   $('#tasks input:checkbox').removeAttr('checked').on('click', function(){
    if(this.checked) $(this).closest('li').addClass('selected');
    else $(this).closest('li').removeClass('selected');
   });


  })
 </script>
@endsection
