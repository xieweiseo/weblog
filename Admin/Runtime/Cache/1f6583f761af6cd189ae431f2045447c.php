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
<script src="__PUBLIC__/Js/jquery-1.4.3.min.js"></script>
<script src="__PUBLIC__/Js/formvalidator/formValidator-4.1.3.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/Js/formvalidator/formValidatorRegex.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$.formValidator.initConfig({theme:"ArrowSolidBox",submitOnce:true,formID:"form",onError:function(msg){}});
	$("#group_id").formValidator({onShow:"分组ID不能为空",onFocus:"分组ID不能为空"}).inputValidator({min:1,max:50,onError:"分组ID不能为空"});
	$("#user_id").formValidator({onshow:"账号不能为空",onFocus:"账号不能为空"}).inputValidator({min:15,max:15,onError:"账号为15位数字"}).regexValidator({regExp:"num",dataType:"enum",onError:"账号格式不正确，应为15位数字"});
	$("#device_code").formValidator({onShow:"广电号不能为空",onFocus:"广电号不能为空"}).inputValidator({min:1,max:50,onError:"广电号不能为空"}).regexValidator({regExp:"num",dataType:"enum",onError:"广电号格式不正确"});	
	$("#device_mac").formValidator({onShow:"设备MAC不能为空",onFocus:"设备MAC不能为空"}).regexValidator({regExp:"mac",dataType:"enum",onError:"设备MAC格式不正确"});		
});
</script>
<body>
<div>
 <div>
    <div style="background:#EEF5DE;height:20px;padding-top: 5px;">
     <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置: 酒店 >添加设备</span>
    </div>   
</div>
<div style="border:1px solid #C8DAA3;margin-top:18px;">
<div class="table-list">
    <form action="__URL__/insert" method="POST" id="form">
    <table width="100%" cellspacing="0" style="font-size:12px;">
       <tbody>
            <tr>
            <td width="50" align="center">分组 : </td>
            <td align="left"><input type="text" name="group_id" id="group_id" value="<?php echo ($vo['group_id']); ?>"><span id="group_idTip"></span></td>	            
            </tr>
            <tr>
            <td width="50" align="center">账号：</td>
            <td align="left">
            <input type="text" name="user_id" id="user_id" class="required" size="50" value="<?php echo ($vo['user_id']); ?>" <?php if($vo['user_id'] != ''): ?>readonly="readonly"<?php endif; ?>><span id="user_idTip"></span>
            </td>	
            </tr>
            <tr>
            <td width="50" align="center">广电号：</td>
            <td align="left">
            <input type="text" name="device_code" id="device_code" size="50" value="<?php echo ($vo['device_code']); ?>"><span id="device_codeTip"></span>
            </td>	
            </tr>
            <tr>
            <td width="50" align="center">MAC：</td>
            <td align="left">
             <input type="text" name="device_mac" id="device_mac" size="50" value="<?php echo ($vo['device_mac']); ?>"><span id="device_macTip"></span>
            </td>	
           </tr>
            <tr>
            <td width="50" align="center">RETAINING：</td>
            <td align="left">
             <input type="text" name="retain_mac" id="retain_mac" size="50" value="<?php echo ($vo['retain_mac']); ?>" readonly="readonly"><span id="device_macTip"></span>
            </td>	
           </tr>                        
       </tbody>    
 	</table>
	<div class="btn" style="font-size:12px;">
         <input type=hidden name="id" value="<?php echo ($vo['id']); ?>">
         <input type=hidden name="hotel_id" value="<?php echo ($vo['hotel_id']); ?>">
         <input type="submit" value="提交" id="send" class="button" style="margin-left: 450px;">&nbsp;&nbsp;
		 <input  type="button" onClick="history.back()" class="button" value="返回" alt="返回" />
	</div>
</form>
</div>
</div>
</div>
</body>
</html>