<?php
$savePath = $_REQUEST['name']."/";
    	if (!is_dir($savePath))
    	 	mkdir($savePath);
    	if (!is_dir($savePath . $_REQUEST['id'] . '/'))
    	 	mkdir($savePath . $_REQUEST['id'] . '/'); 
    	//print_r($_FILES);exit;
    	$uploadName=$_FILES['file']['tmp_name'];
    	$file_type=explode(".", $_FILES['file']['name']);
    	$fileName=$_REQUEST['name'].'_'.md5(uniqid()).".".$file_type[1];
//     	print_r($_FILES);exit;
    	//$fileName=$_FILES['headpic']['name'];
    	$targetPath=$_SERVER['DOCUMENT_ROOT']."/public/uploads/".$savePath . $_REQUEST['id'] . '/'.$fileName;
//     	print_r($_SERVER);exit;
    	$temp=iconv("utf-8", "gbk//ignore", $targetPath); 
    	 	
     	if(is_uploaded_file($uploadName)){
    		if (move_uploaded_file($uploadName, $temp)) {
    			 
    		}else{
    			echo 0;
    		}
    	}else{
    		echo 0;
    	}
    	$res=array();
    	$res["error"] = "";//错误信息
    	$res["msg"] = "";//提示信息
    	$res["imgUrl"] = "/public/uploads/".$savePath . $_REQUEST['id'] . '/'.$fileName;
    	$res["fileName"] = $fileName;
    	$res["originalName"] = $_FILES['file']['name'];	//原本的文件名称
    	$res["fileType"] = $file_type[1];
    
    	echo json_encode($res);
?>