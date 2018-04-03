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
<script src="__PUBLIC__/Js/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
<script src="__PUBLIC__/Js/jquery-validation-1.14.0/dist/localization/messages_zh.min.js"></script>
<script src="__PUBLIC__/admin/js/layer/layer.js"></script>
<body>
<div>
 <div>
    <div style="background:#EEF5DE;height:20px;padding-top: 5px;">
   <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置 : 酒店  >添加酒店</span>
    </div>   
</div>
<div style="border:1px solid #C8DAA3;">
 <div>
    <div style="background:#FFFFFF;height:20px;margin-bottom:5px;"><p>
   <span style="margin-right:5px;margin-left:10px;font-size:12px;">
   <a href="__URL__/index" style="text-decoration:none;" target="main" title="酒店列表">酒店列表</a></span>|
   <a href="__URL__/add"  target="main" title="添加酒店" style="text-decoration:none;">
   <span style="margin-right:5px;margin-top:5px;padding: 3px;font-size:12px;background-color:#618d01;color:#fff;">
   添加酒店</span></a>
    </div>
   <hr style="height:1px;border:none;border-top:1px solid #eee;">
</div>
<div class="table-list">
    <form action="__URL__/insert" method="POST" id="form" enctype="multipart/form-data">
    <table width="100%" cellspacing="0" style="font-size:12px;">
       <tbody>
            <tr>
            <td width="70" align="center">酒店名 ：</td>
            <td align="left"><input type="text" name="hotel_name" id="hotel_name" size="50" value="<?php echo ($vo['hotel_name']); ?>"> <span class="red_font">*</span></td>	            
            </tr>       
            <tr>
            <td width="70" align="center">分组：</td>
            <td align="left"><input type="text" name="group_id" id="group_id" size="50" value="<?php echo ($vo['group_id']); ?>"> <span class="red_font">*</span></td>	            
            </tr>
            <tr>
            <td width="70" align="center">地区：</td>
            <td align="left">            	
				<p id="city_china_val">		
					<select name="province" class="province" data-value="<?php echo ($vo['province']); ?>" data-first-title="选择省" disabled="disabled"></select>		
					<select name="city" class="city" data-value="<?php echo ($vo['city']); ?>" data-first-title="选择市" disabled="disabled"></select>		
					<select name="area" class="area" data-value="<?php echo ($vo['area']); ?>" data-first-title="选择地区" disabled="disabled"></select>		
				</p>				
            </td>	
            </tr>
            <?php if($vo['id'] == ''): ?><tr>
            <td width="70" align="center">导入设备：</td>
            <td align="left">
            	<input type="file" name="upload_excel" id="upload_excel" size="50"> &nbsp;&nbsp;&nbsp;&nbsp; | <a href="<?php echo U('template_download');?>" >模板下载</a>
            </td>	
            </tr><?php endif; ?>            
            <tr>
            <td width="70" align="center">描述：</td>
            <td align="left">
            	<textarea name="description" style="height:69px; width:363px;"><?php echo ($vo['description']); ?></textarea>
            </td>	
            </tr>          
       </tbody>    
 	</table>
	<div class="btn" style="font-size:12px;">
         <input type=hidden name="id" value="<?php echo ($vo['id']); ?>">
         <input type="submit" value="提交" id="send" class="button" style="margin-left: 450px;">&nbsp;&nbsp;
		 <input  type="button" class="back" value="返回" alt="返回" />
	</div>
</form>
</div>
</div>
</div>
<script src="__PUBLIC__/Js/cxselect/jquery.cxselect.min.js"></script>
<script>
$.cxSelect.defaults.url = "__PUBLIC__/Js/cxselect/cityData.min.json";
$('#city_china_val').cxSelect({
	selects: ['province', 'city', 'area'],
	nodata: 'none'
});
//------>返回
$(function(){
	$('.back').click(function(){
		window.location.href = "<?php echo U('index');?>";
	});
});
//------>表单验证
   $.validator.setDefaults({
		submitHandler: function() {
			window.form.submit();
		}
   });
   
   $().ready(function () {
       // 在键盘按下并释放及提交后验证提交表单
       $("#form").validate({
           rules: {
        	   hotel_name:{
                   required: true,
                   minlength: 2        		   
        	   },
               group_id: {
                   required: true,
                   digits:true,
                   minlength: 4
               },
           },
           messages: {
        	   hotel_name:{
                   required: "酒店名不能为空",
                   minlength: "酒店名至少两位字符"        		   
        	   },      	   
               group_id: {
                   required: "分组不能为空",
                   minlength: "分组至少4位数字"
               },
           },
           //重写showErrors
           showErrors: function (errorMap, errorList) {
               var msg = "";
               $.each(errorList, function (i, v) {
                   //msg += (v.message + "\r\n");
                   //在此处用了layer的方法,显示效果更美观
                   layer.tips(v.message, v.element, {time: 2000, tips:[2,"#F69804"] });
                   return false;
               });  
           },
           /* 失去焦点时不验证 */
           onfocusout: false
       });
   });
  </script>
</body>
</html>