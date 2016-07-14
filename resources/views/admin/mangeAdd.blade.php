@extends('Admin.layout2')
@section('title')添加管理员-管理员管理@endsection
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
    <li class="active"><a href="{{action('Admin\AdminController@getMangeList')}}">管理员管理</a></li>
   </ul><!-- .breadcrumb -->

  </div>

  <div class="page-content">
   <div class="page-header">
    <h1>
     管理员管理
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
       <form class="form-horizontal" role="form" method="post" action="{{action('Admin\UserController@postAdd')}}" id="addPermissionForm">
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 手机号 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="手机号" class="col-xs-10 col-sm-5" name="mobile" value="@if(isset($info['mobile'])) {{$info['mobile']}} @endif">
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 真实姓名 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="真实姓名" class="col-xs-10 col-sm-5" name="uname" value="@if(isset($info['uname'])) {{$info['uname']}} @endif">
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 身份证号码 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="身份证号码" class="col-xs-10 col-sm-5" name="idenNum" value="@if(isset($info['idenNum'])) {{$info['idenNum']}} @endif">
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 邮箱 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="邮箱" class="col-xs-10 col-sm-5" name="email" value="@if(isset($info['email'])) {{$info['email']}} @endif">
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 公司固话 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="公司固话" class="col-xs-10 col-sm-5" name="tel" value="@if(isset($info['tel'])) {{$info['tel']}} @endif">
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 公司名称 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="角色名称" class="col-xs-10 col-sm-5" name="cmpy" value="@if(isset($info['cmpy'])) {{$info['cmpy']}} @endif">
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 地址 </label>

         <div class="col-sm-9">
          <input type="text" id="form-field-1" placeholder="地址" class="col-xs-10 col-sm-5" name="address" value="@if(isset($info['address'])) {{$info['address']}} @endif">
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 部门 </label>

         <div class="col-sm-9">
          <select name="dempt">
           @foreach($departments as $v)

            <option value="{{$v['id']}}">{{$v['name']}}</option>

            @endforeach
          </select>
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 管理角色 </label>

         <div class="col-sm-9">
          <select name="role_id">
           @foreach($roles as $v)

            <option value="{{$v['role_id']}}">{{$v['role_name']}}</option>

           @endforeach
          </select>
         </div>
        </div>
        <div class="space-4"></div>

        <input type="hidden" name="id" value="{{$id}}">

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
