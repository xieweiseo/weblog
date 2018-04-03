<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>『酒店业务管理平台』</title>
<link rel="shortcut icon" href=" /favicon.ico" /> 
<link href="__PUBLIC__/admin/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__PUBLIC__/admin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"/>
</head>
<script src="__PUBLIC__/Js/jquery.min.js"></script>
<body>
<div>
 <div>
    <div style="background:#EEF5DE;height:20px;padding-top: 5px;">
   <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置 : 酒店>导入</span>
    </div>  
</div>
<div style="border:1px solid #C8DAA3;">
 <div>
    <div style="background:#FFFFFF;height:25px;margin:8px 0 5px;">
   <span style="margin-right:5px;margin-left:5px;font-size:12px;"><a href="__URL__/index"  target="main" title="酒店列表">酒店列表</a></span>|
   <span style="margin-right:5px;margin-left:5px;font-size:12px;"><a href="__URL__/add"  target="main" title="添加酒店">添加酒店</a></span>
    </div>
   <hr style="height:1px;border:none;border-top:1px solid #eee;">
</div>
<div class="table-list">
    <form enctype="multipart/form-data" action="__URL__/import" method="POST" id="myform">
    <table width="100%" cellspacing="0" style="font-size:12px;">
       <tbody>
            <tr>
            <td width="50" align="center">文件：</td>
            <td align="left">
             <input type="file" name="upload_excel" id="upload_excel" size="50"/>&nbsp;&nbsp;<input type="button" id="upload" value="上传" />&nbsp;&nbsp;&nbsp;&nbsp; | <a href="<?php echo U('Hotel/template_download');?>" >模板下载
            </td>	
            </tr>
       </tbody>    
 	</table>
	<div class="btn" style="font-size:12px;">
     <input type=hidden name="id" value="<?php echo ($vo["id"]); ?>">
     <input type="submit"  name="dosubmit" value="确定" id="send" class="button" style="margin-left: 450px;">&nbsp;&nbsp;
	 <input  type="button" class="goback" class="button" value="取 消" alt="取消" />
	</div>
</form>
</div>
</div>
</div>
<script type="text/javascript">
$(function(){
	$('.goback').click(function(){
		window.location.href='<?php echo U("index");?>';
		
	});
	
	$('#upload').click(function(){
		window.myform.submit();
	});
});
</script>
</body>
</html>