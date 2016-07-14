@extends('Admin.layout2')
@section('title')角色列表-角色管理@endsection
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
    <li class="active">角色管理</li>
   </ul><!-- .breadcrumb -->

  </div>

  <div class="page-content">
   <div class="page-header">
    <h1>
     角色管理
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
       <div class="col-md-offset-2 col-md-9">
        @if(count($errors) > 0)
       <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">
         <i class="icon-remove"></i>
        </button>

        <strong>
         <i class="icon-remove"></i>
         错误
        </strong>
        @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
        @endforeach

        <br>
       </div>
         @endif
       </div>
       <form class="form-horizontal" role="form" method="post" action="{{action('Admin\RoleController@postAdd')}}" id="addPermissionForm">
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 角色名称 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="角色名称" class="col-xs-10 col-sm-5" name="role_name" value="@if(isset($info['role_name'])) {{$info['role_name']}} @endif">
         </div>
        </div>

        <div class="space-4"></div>

        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 状态 </label>

         <div class="col-sm-9">
          <select name="status">
           <option value="1" @if(isset($info['status']) && $info['status'] == 1) checked @endif>正常</option>
           <option value="2" @if(isset($info['status']) && $info['status'] == 2) checked @endif>无效</option>
          </select>

         </div>
         <input type="hidden" name="id" value="{{$id}}">
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

     <div class="hr hr-18 dotted hr-double"></div>

    </div><!-- /.row -->
   </div><!-- /.page-content -->
  </div><!-- /.main-content -->

 </div>
@endsection

@section('footer')

 <script type="text/javascript">
  $('#submitBtn').click(function(){
   $('form').submit();
  })
 </script>
@endsection
