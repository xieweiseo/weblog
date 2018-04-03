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
   <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置 : 设备管理 >导入</span>
    </div>  
</div>
<div>
 <div>
    <div style="background:#FFFFFF;height:25px;margin:18px 8px 5px;">
		<span style="margin-right:5px;margin-top:5px;margin-left:10px;padding: 3px;font-size:12px;background-color:#618d01;color:#fff">
		 <?php echo ($hotel_name); ?>
		</span>
    </div>
</div>
</div>
<div class="table-list">
    <form enctype="multipart/form-data" action="__URL__/import" method="POST" id="myform">
    <table width="100%" cellspacing="0" style="font-size:12px;">
       <tbody>
            <tr>
            <td width="50" align="center">文件：</td>
            <td align="left">
             <input type="file" name="upload_excel" id="upload_excel" size="50"/>&nbsp;&nbsp;<input type="button" id="upload" value="上传" /> &nbsp;&nbsp;&nbsp;&nbsp; | <a href="<?php echo U('Device/template_download');?>" >模板下载</a>
            </td>	
            </tr>            
       </tbody>    
 	</table>
	<div id="ckbtn" class="btn" style="font-size:12px;">
	<label><input style="vertical-align:middle;" type="checkbox" name="mac_import" value='1'><span style="vertical-align:middle;">mac导入</span></label>  <label><input style="vertical-align:middle;" type="checkbox" name="mac_import" value='2'><span style="vertical-align:middle;">广电号导入</span></label>&nbsp;&nbsp; <label><span style="color:#ccc">默认都不勾选为电视账号导入</span></label> 
     <input type=hidden name="hotel_id" value="<?php echo ($hotel_id); ?>">
     <input type=hidden name="group_id" value="<?php echo ($group_id); ?>">
     <input type=hidden name="hotel_name" value="<?php echo ($hotel_name); ?>">
     <input type="submit"  name="dosubmit" value="确定" id="send" class="button" style="margin-left: 450px;">&nbsp;&nbsp;
	 <input  type="button" class="button" onclick="javascript:;history.back();" value="取 消" alt="取消" />
	</div>
</form>
</div>
</div>
<script type="text/javascript">
$(function(){
	//checkbox单选
    $('#ckbtn').find('input[type=checkbox]').bind('click', function(){  
        $('#ckbtn').find('input[type=checkbox]').not(this).attr("checked", false);  
    });  	
	
	//表单提交
	$('#upload').click(function(){
		window.myform.submit();
	});
});
</script>
</body>
</html>