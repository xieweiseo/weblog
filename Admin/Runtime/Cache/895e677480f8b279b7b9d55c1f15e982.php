<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>『酒店业务管理平台』</title>
<link rel="shortcut icon" href=" /favicon.ico" /> 
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/main.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/js/layer/skin/default/layer.css" />
<script src="__PUBLIC__/admin/js/jquery.min.js" type="text/javascript" ></script>
<script src="__PUBLIC__/admin/js/layer/layer.js" type="text/javascript" ></script>
</head>
<body>
<div>
<div class="table-list">    
 <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div style='float:left'></div>
      <div style='float:right;padding-right:8px;'>
        <!--  //保留接口  -->
      </div></td>
  </tr>
  <tr>
    <td height="1" background="__PUBLIC__/admin/images/sp_bg.gif" style='padding:0px'></td>
  </tr>
</table>
<table width="98%" align="center" border="0" cellpadding="3" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px;margin-top:8px;">
  <tr>
    <td " bgcolor="#EEF4EA" class='title'><span>个人信息</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>您好,<?php echo (session('loginUserName')); ?>!上次登录时间：<?php echo (date("y-m-d H:i:s",session('lastLoginTime'))); ?>,上次登录IP：<?php echo (session('lastloginip')); ?></td>
  </tr>
</table>
<table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px">
<!--   <tr>
    <td colspan="2" " bgcolor="#EEF4EA" class='title'>
    	<div style='float:left'><span>快捷操作</span></div>
    	<div style='float:right;padding-right:10px;'></div>
   </td>
  </tr> -->
<!--   <tr bgcolor="#FFFFFF">
    <td height="30" colspan="2" align="center" valign="bottom"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="15%" height="31" align="center"><img src="__PUBLIC__/admin/images/qc.gif" width="90" height="30" /></td>
          <td width="85%" valign="bottom"><div class='icoitem'>
              <div class='ico'><img src='__PUBLIC__/admin/images/addnews.gif' width='16' height='16' /></div>
              <div class='txt'><a href='__APP__/Hotal/index'><u>设备列表</u></a></div>
            </div>
            <div class='icoitem'>
              <div class='ico'><img src='__PUBLIC__/admin/images/manage1.gif' width='16' height='16' /></div>
              <div class='txt'><a href='__APP__/Hotal/add'><u>设备发布</u></a></div>
            </div>
            <div class='icoitem'>
              <div class='ico'><img src='__PUBLIC__/admin/images/file_dir.gif' width='16' height='16' /></div>
              <div class='txt'><a href='__APP__/Group/index'><u>分组管理</u></a></div>
            </div>
            <div class='icoitem'>
              <div class='ico'><img src='__PUBLIC__/admin/images/part-index.gif' width='16' height='16' /></div>
              <div class='txt'><a href="__APP__/Clear/index" target="main" title="缓存管理"><u>缓存管理</u></a></div>
            </div>
            </td>
        </tr>
      </table></td>
  </tr> -->
</table>
<table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px">
  <tr bgcolor="#EEF4EA">
    <td colspan="2" class='title'><span>系统基本信息</span></td>
  </tr>
<!--   <tr bgcolor="#FFFFFF">
    <td width="25%" bgcolor="#FFFFFF">您的级别：</td>
    <td width="75%" bgcolor="#FFFFFF"><?php echo (session('loginUserName')); ?></td>
  </tr> -->
  <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr bgcolor="#FFFFFF">
	    <td><?php echo ($key); ?>：</td>
	    <td><?php echo ($v); ?></td>
	  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<!-- <table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px">
  <tr bgcolor="#EEF4EA">
    <td colspan="2"class='title'><span>业务交流</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="32">官方网站：http://www.digitlink.cn</td>
    <td><a href="http://www.digitlink.cn" target="_blank"><u>北数领航</u></a></td>
  </tr> 
</table> -->

<table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC">
    <tr bgcolor="#EEF4EA">
        <td bgcolor="#FFFFFF">#</td>
        <td bgcolor="#FFFFFF">用户</td>
        <td bgcolor="#FFFFFF">时间</td>
        <td bgcolor="#FFFFFF">IP</td>
        <td bgcolor="#FFFFFF" class="col-xs-7">日志内容</td>
    </tr>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr bgcolor="#EEF4EA">
            <td bgcolor="#FFFFFF"><?php echo ($val['id']); ?></td>
            <td bgcolor="#FFFFFF"><?php echo ($val['name']); ?></td>
            <td bgcolor="#FFFFFF"><?php echo (date("Y-m-d H:i:s",$val['t'])); ?></td>
            <td bgcolor="#FFFFFF"><?php echo ($val['ip']); ?></td>
            <td bgcolor="#FFFFFF"><a style="color:#393939" class="log" href="javascript:;" data="<?php echo ($val['log']); ?>" title="<?php echo ($val['log']); ?>"><?php echo (str_cut($val['log'],120)); ?></a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
 <div id="pages" style="font-size:12px;"><?php echo ($page); ?></div>
</div>
</div>
<script>
$(function(){
	$('.log').click(function(){
		var info = $(this).attr('data');
		layer.msg(info);
	});
});
</script>
</body>
</html>