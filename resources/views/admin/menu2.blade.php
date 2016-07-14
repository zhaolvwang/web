<div class="sidebar" id="sidebar">
 <script type="text/javascript">
  try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
 </script>

 <div class="sidebar-shortcuts" id="sidebar-shortcuts">
  <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
   <button class="btn btn-success">
    <i class="icon-signal"></i>
   </button>

   <button class="btn btn-info">
    <i class="icon-pencil"></i>
   </button>

   <button class="btn btn-warning">
    <i class="icon-group"></i>
   </button>

   <button class="btn btn-danger">
    <i class="icon-cogs"></i>
   </button>
  </div>

  <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
   <span class="btn btn-success"></span>

   <span class="btn btn-info"></span>

   <span class="btn btn-warning"></span>

   <span class="btn btn-danger"></span>
  </div>
 </div><!-- #sidebar-shortcuts -->
 <div class="sidebar-collapse" id="sidebar-collapse">
  <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
 </div>

 <ul class="nav nav-list">
  <li @if($menu_open == 'admin/index') class="active" @endif>
  <a href="{{url('admin/index')}}">
  <i class="icon-dashboard"></i>
  <span class="menu-text"> 控制台 </span>
  </a>
  </li>
  @foreach($adminPermission as $key=>$value)

   <li class="">
    <a class="dropdown-toggle" href="@if(trim($value['permission_code']) == '') javascript:; @else {{url($value['permission_code'])}} @endif">
     <i class="icon-dashboard"></i>
     <span class="menu-text"> {{$value['permission_name']}} </span>
     @if(isset($value['menu']) && !empty($value['menu']))<b class="arrow icon-angle-down"></b>@endif
    </a>
    @if(isset($value['menu']) && !empty($value['menu']))
     <ul class="submenu">

      @foreach($value['menu'] as $k=>$v)

       <li @if($v['permission_code'] == $menu_open) class="active" @endif>
        <a href="{{url($url_prev . $v['permission_code'])}}">
         <i class="icon-double-angle-right"></i>
         {{$v['permission_name']}}
        </a>
       </li>

       @endforeach

     </ul>
     @endif
   </li>

   @endforeach

 </ul><!-- /.nav-list -->

 <script type="text/javascript">
  try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
  $(function() {
   $('ul.submenu').find('.active').parent('.submenu').parent('li').addClass('active').addClass('open');
   $('ul.submenu').find('.active').parent('.submenu').parent('li').find('b').addClass('icon-angle-up').removeClass('icon-angle-down');
   $('.dropdown-toggle').click(function () {
    if ($(this).find('.arrow').hasClass('icon-angle-down')) {
     $(this).find('.arrow').addClass('icon-angle-up').removeClass('icon-angle-down');
    } else {
     $(this).find('.arrow').addClass('icon-angle-down').removeClass('icon-angle-up');
    }
   })
  });
 </script>
</div>