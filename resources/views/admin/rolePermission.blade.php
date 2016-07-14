@extends('Admin.layout2')
@section('title')角色列表-角色管理@endsection
@section('content')
 <link rel="stylesheet" href="/assets/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
 {{--<script type="text/javascript" src="/assets/ztree/js/jquery-1.4.4.min.js"></script>--}}
 <script type="text/javascript" src="/assets/ztree/js/jquery.ztree.core.js"></script>
 <script type="text/javascript" src="/assets/ztree/js/jquery.ztree.excheck.js"></script>

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
    <li class="active">角色管理</li>
   </ul><!-- .breadcrumb -->

  </div>

  <div class="page-content">
   <div class="page-header">
    <h1>
     角色管理
     <small>
      <i class="icon-double-angle-right"></i>
      分配权限
     </small>
    </h1>
   </div><!-- /.page-header -->

   <div class="row">
    <div class="col-xs-12">
     <!-- PAGE CONTENT BEGINS -->

     <div class="row">
      <div class="col-xs-12">
       <form class="form-horizontal" role="form" method="post" action="" id="addPermissionForm">
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 角色名称 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="角色名称" class="col-xs-10 col-sm-5" name="role_name" value="{{$role_name}}" readonly>
         </div>
        </div>

        <div class="space-4"></div>

        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>
         <label>
           <ul id="treeDemo" class="ztree"></ul>
          </label>
        </div>
        <input type="hidden" value="{{$role_id}}" id="role_id">
        <input type="hidden" value="{!! $data !!}" id="ztreeData">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">
        <div class="clearfix form-actions">
         <div class="col-md-offset-3 col-md-9">
          {{--<button class="btn btn-info" type="button" id="submitBtn">--}}
           {{--<i class="icon-ok bigger-110"></i>--}}
           {{--提交--}}
          {{--</button>--}}

          &nbsp; &nbsp; &nbsp;
          <button class="btn" type="reset" onclick="window.history.back();">
           <i class="icon-undo bigger-110"></i>
           返回
          </button>
         </div>
        </div>

       </form>

      </div><!-- /span -->
     </div><!-- /row -->

     <div class="hr hr-18 dotted hr-double"></div>

    </div><!-- /.row -->
   </div><!-- /.page-content -->
  </div><!-- /.main-content -->

 </div>
@endsection

@section('footer')
 <SCRIPT type="text/javascript">
  var _token = $('#_token').val();
  var role_id = $('#role_id').val();
  <!--
  var setting = {
   check: {
    enable: true
   },
   data: {
    simpleData: {
     enable: true
    }
   },
   callback:{
    onCheck:function(even,  treeId,  treeNode){
      //alert(treeNode.id+'==='+treeNode.name+'==>'+treeNode.checked);
     $.post('/admin/role/permission-setting',
     {id:treeNode.id, role_id:role_id, checked:treeNode.checked,_token:_token},function (data){
       //console.log(data);
     },'json'
     );
    }
   }
  };

//  var zNodes =[
//   { id:1, pId:0, name:"随意勾选 1", open:true},
//   { id:11, pId:1, name:"随意勾选 1-1", open:true},
//   { id:111, pId:11, name:"随意勾选 1-1-1"},
//   { id:112, pId:11, name:"随意勾选 1-1-2"},
//   { id:12, pId:1, name:"随意勾选 1-2", open:true},
//   { id:121, pId:12, name:"随意勾选 1-2-1"},
//   { id:122, pId:12, name:"随意勾选 1-2-2"},
//   { id:2, pId:0, name:"随意勾选 2", checked:true, open:true},
//   { id:21, pId:2, name:"随意勾选 2-1"},
//   { id:22, pId:2, name:"随意勾选 2-2", open:true},
//   { id:221, pId:22, name:"随意勾选 2-2-1", checked:true},
//   { id:222, pId:22, name:"随意勾选 2-2-2"},
//   { id:23, pId:2, name:"随意勾选 2-3"}
//  ];
  var zNodes = {!! $data !!};
  var code;

  function setCheck() {
   var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
           py = $("#py").attr("checked")? "p":"",
           sy = $("#sy").attr("checked")? "s":"",
           pn = $("#pn").attr("checked")? "p":"",
           sn = $("#sn").attr("checked")? "s":"",
           type = //{ "Y":py + sy, "N":pn + sn};

   zTree.setting.check.chkboxType = { "Y" : "", "N" : "" };//{ "Y" : "ps", "N" : "ps" };//type;

   showCode('setting.check.chkboxType = { "Y" : "' + type.Y + '", "N" : "' + type.N + '" };');
  }
  function showCode(str) {
   if (!code) code = $("#code");
   code.empty();
   code.append("<li>"+str+"</li>");
  }

  $(document).ready(function(){
   $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   setCheck();
   $("#py").bind("change", setCheck);
   $("#sy").bind("change", setCheck);
   $("#pn").bind("change", setCheck);
   $("#sn").bind("change", setCheck);
  });

  //-->
 </SCRIPT>
 <script type="text/javascript">
  $('#submitBtn').click(function(){
   $('form').submit();
  })
 </script>
@endsection
