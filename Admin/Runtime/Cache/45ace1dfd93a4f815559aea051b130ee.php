<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>『酒店业务管理平台』</title>
<link rel="shortcut icon" href=" /favicon.ico" /> 
<link href="__PUBLIC__/admin/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__PUBLIC__/admin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"/>
</head>
<script src="__PUBLIC__/Js/jquery-1.4.3.min.js"></script>
<script src="__PUBLIC__/Js/formvalidator/formValidator-4.1.3.min.js" type="text/javascript"></script>
<body>
<div>
 <div>
    <div style="background:#EEF5DE;height:20px;padding-top: 5px;">
     <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置:酒店 >服务集查询</span>
    </div>   
</div>
<div style="border:1px solid #C8DAA3;">
 <div>
   <div style="background:#FFFFFF;height:20px;">
    </div>
</div>
<div class="table-list">
<form action="__URL__/panel" method="POST" id="form">
 <div id="searchid" style="padding-bottom:5px;">
   <table width="98%" cellspacing="0" class="search-form">
    <tbody>
	  <tr>
		<td>
                            设备MAC&nbsp;&nbsp;     
          <input type="text" name="mac" size="20" value="<?php echo I('mac');?>" > &nbsp; 
          <input  type="submit"  name='dosubmit' class="btn-info" value="搜索" />	
		</td>
	 </tr>
    </tbody>
   </table>
  </div>    
    <table width="100%" cellspacing="0" style="font-size:12px;">
       <tbody>
            <tr>
            <td width="50" align="center">分组 : </td>
            <td align="left"><input type="text" name="group_id" id="group_id" value="<?php echo ($config['group_id']); ?>"></td>	            
            </tr>
            <tr>
            <td width="50" align="center">面板：</td>
            <td align="left">
            <input type="text" name="panel" id="panel" size="50" value="<?php echo ($config['panel']); ?>">
            	<?php if($config['mac'] != '' AND $config['data'] != '' ): ?>&nbsp;&nbsp;|&nbsp;&nbsp;
            	  <span id="panel_view">
            	   <a target="_blank" href="<?php echo U('panel_view',array('mac'=>$config['mac'],'data'=>$config['data']),'');?>">预览大图</a>
            	  </span><?php endif; ?>
            </td>
            </tr>
            <?php if($config['mac'] != '' AND $config['data'] != '' ): ?><tr>
            <td width="50" align="center"></td>
            <td align="left">
             <a title="预览大图" target="_blank" href="<?php echo U('panel_view',array('mac'=>$config['mac'],'data'=>$config['data']),'');?>">              
               <img src="<?php echo ($config['image']); ?>" style="width:376px;">              
             </a>
            </td>
            </tr><?php endif; ?>	            	           
       </tbody>    
 	</table>
	<div class="btn" style="font-size:12px;">
		 <input  type="button" id="back" class="button" value="返回" alt="返回" />
	</div>
</form>
</div>
</div>
</div>
<script>
$(function(){
	$('#back').click(function(){
		window.location.href="<?php echo U('panel');?>";
	});
});
</script>
</body>
</html>