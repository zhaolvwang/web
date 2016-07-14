@extends('member.member')

@section('content')
<script src="{{ asset('packages/jcrop/js/jquery.Jcrop.min.js') }}"></script>
<script type="text/javascript">
  jQuery(function($){

    

    function updatePreview(c)
    {
    	$('#x').val(c.x);
	    $('#y').val(c.y);
	    $('#w').val(c.w);
	    $('#h').val(c.h);
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;

        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
      }
    };

  });

  $(function(){

	   /* $('#cropbox').Jcrop({
	      aspectRatio: 1,
	      onSelect: updateCoords
	    }); */

	  });

	  function updateCoords(c)
	  {
	    $('#x').val(c.x);
	    $('#y').val(c.y);
	    $('#w').val(c.w);
	    $('#h').val(c.h);
	  };

	  function checkCoords()
	  {
	    if (parseInt($('#w').val())) return true;
	    alert('Please select a crop region then press submit.');
	    return false;
	  };
	  


</script>
<link rel="stylesheet" href="{{ asset('tapmodo-Jcrop-1902fbc/demos/demo_files/demos.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('packages/jcrop/css/jquery.Jcrop.css') }}" type="text/css" />
<style type="text/css">

/* Apply these styles only when #preview-pane has
   been placed within the Jcrop widget */
#preview-pane {
  display: block;
  position: absolute;
  z-index: 2000;
  top: 10px;
  right: -280px;
  padding: 6px;
  border: 1px rgba(0,0,0,.4) solid;
  background-color: white;

  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  border-radius: 6px;

  -webkit-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
}

/* The Javascript code will set the aspect ratio of the crop
   area based on the size of the thumbnail preview,
   specified here */
#preview-pane .preview-container {
  width: 150px;
  height: 150px;
  overflow: hidden;
}

</style>
<div class="member-tab">
						<span class="current"><a href="edit_ava.shtml">编辑头像<i></i></a></span>
					</div>
					<div class="account-main pt40 pb40 pl40 pr40">
						<div class="img txt-center"><img src="{{ $headPic }}" alt="" width="150" height="150" class="margin-auto imgUrl" /></div>
						<div class="clearfix margin-auto mt40" style="width: 530px;">
							<div class="zz up-load-layer rel">
								<input type="file" class="in-file" onchange="document.getElementById('upfileResult').innerHTML=this.value" name="file" id="avatar_upload_file">
								<div class="tip yahei font-s-14 txt-over" id="upfileResult">文件名</div>
							</div>
						<div class="zz ml10"><input type="button" class="btn-file hover-light font-s-16 upload_btn" value="上  传"></div>
						</div>
					</div>
<div class="jc-demo-box" style="display: none;"><!-- {{ asset('tapmodo-Jcrop-1902fbc/demos/demo_files/sago.jpg') }} -->

  <img src="" id="cropbox" alt="[Jcrop Example]" />

  <div id="preview-pane">
    <div class="preview-container">
      <img src="" class="jcrop-preview imgUrl" alt="Preview" />
    </div>
  </div>

<div class="clearfix"></div>

<!-- This is the form that our event handler fills -->
		<form action="{{ url('member/account/uploadAvatar') }}" method="post" onsubmit="return checkCoords();">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="avatar_file" name="avatar" />
			<input type="hidden" id="fileName" name="fileName" />
			<input type="hidden" id="fileType" name="fileType" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="submit" class="btn-file hover-light font-s-16" value="确  定">
		</form>

</div>
<script src="{{ asset('packages/ajaxfileupload/ajaxfileupload.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(".upload_btn").click(function(){
	var _this=this;
	var fileid="avatar_upload_file";
	var name='avatar';
	var id='{{ auth()->user()->id }}';
	$.ajaxFileUpload
	(
		{
			url:"{{ asset('public/uploads/doajaxfileupload.php') }}?id="+id+"&name="+name,
			secureuri:false,
			fileElementId:fileid,
			dataType: 'json',
			success: function (data, status)
			{
				if(data){
					$(".jc-demo-box").show();
		           	$("#fileName").val(data.fileName);
		           	$("#fileType").val(data.fileType);
		           	$("#avatar_file").val(data.imgUrl);
		           	$("#cropbox").attr("src", data.imgUrl);
		           	$(".imgUrl").attr("src", data.imgUrl);

		            // Create variables (in this scope) to hold the API and image size
		            var jcrop_api,
		                boundx,
		                boundy,

		                // Grab some information about the preview pane
		                $preview = $('#preview-pane'),
		                $pcnt = $('#preview-pane .preview-container'),
		                $pimg = $('#preview-pane .preview-container img'),

		                xsize = $pcnt.width(),
		                ysize = $pcnt.height();
		            
		            console.log('init',[xsize,ysize]);
		            $('#cropbox').Jcrop({
		              onChange: updatePreview,
		              onSelect: updatePreview,
		              aspectRatio: 1
		            },function(){
		              // Use the API to get the real image size
		              var bounds = this.getBounds();
		              boundx = bounds[0];
		              boundy = bounds[1];
		              // Store the API in the jcrop_api variable
		              jcrop_api = this;
		              
		              // Move the preview into the jcrop container for css positioning
		              $preview.appendTo(jcrop_api.ui.holder);
		            });


		            function updatePreview(c)
		            {
		            	$('#x').val(c.x);
		        	    $('#y').val(c.y);
		        	    $('#w').val(c.w);
		        	    $('#h').val(c.h);
		              if (parseInt(c.w) > 0)
		              {
		                var rx = xsize / c.w;
		                var ry = ysize / c.h;

		                $pimg.css({
		                  width: Math.round(rx * boundx) + 'px',
		                  height: Math.round(ry * boundy) + 'px',
		                  marginLeft: '-' + Math.round(rx * c.x) + 'px',
		                  marginTop: '-' + Math.round(ry * c.y) + 'px'
		                });
		              }
		            };
		           	
	            }else{
					alert("系统错误，请重试！");
	            } 
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)

	//return false;
});
</script>
@stop
