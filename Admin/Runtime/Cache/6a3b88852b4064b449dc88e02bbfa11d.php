<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>『酒店业务管理平台』</title>
<link rel="shortcut icon" href=" /favicon.ico" /> 
<link href="__PUBLIC__/admin/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__PUBLIC__/admin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"/>
</head>
<link href="__PUBLIC__/admin/js/layer/skin/default/layer.css" rel="stylesheet" type="text/css" />
<script src="__PUBLIC__/admin/js/jquery.min.js" type="text/javascript" ></script>
<script src="__PUBLIC__/admin/js/layer/layer.js" type="text/javascript" ></script>
<body>
<div>
 <div>
    <div style="background:#EEF5DE;height:20px;padding-top: 5px;">
      <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置: 酒店&nbsp;>&nbsp;账号列表</span>
    </div>   
</div>
<div style="border:1px solid #C8DAA3;margin-bottom:3px;">
<div>
<div style="background:#FFFFFF;height:25px;margin-bottom:2px;padding-top:8px;">
	   <a href="javascript:void(0)" class="import" target="main" title="账号列表" style="text-decoration:none;color:#333">
	   <span  style="margin-right:5px;margin-top:5px;margin-left:10px;padding: 3px;font-size:12px;background-color:#618d01;color:#fff;">
	          导 入</span></a>| 
	   <span style="margin-right:5px;margin-left:5px;font-size:12px;">
	   <a href="<?php echo U('insert');?>" style="text-decoration:none;" target="main" title="添加">添加</a>
	   </span> 
   </div>
 </div>
</div>

<div class="pad_10" style="margin-right:10px;margin-left:10px;font-size:12px;">
<div id="searchid">
   <table width="98%" cellspacing="0" class="search-form">
    <tbody>
	  <tr>
		<td>
		<div style="border:1px solid #87b87f;zoom:1; background: rgba(255, 255, 255, 0.15); padding:5px 10px; line-height:20px;">
		<form id="search_form" class="form-inline" action="__URL__/index" method="post">
                   电视账号
          <input type="text" name="user_id" value="<?php echo I('user_id');?>" >&nbsp;
                  广电号     
          <input type="text" name="device_code" value="<?php echo I('device_code');?>" >
                  设备MAC     
          <input type="text" name="device_mac" value="<?php echo I('device_mac');?>" >  
          <select name="status" id="status" style="height:31px;margin-bottom:3px;">
          	<option value="0">--全部--</option>
          	<option value="-1" <?php if(I('status') == -1): ?>selected<?php endif; ?>>未读取</option>
          	<option value="1" <?php if(I('status') == 1): ?>selected<?php endif; ?>>已读取</option>
          	<option value="2" <?php if(I('status') == 2): ?>selected<?php endif; ?>>不存在</option>
          	<option value="3" <?php if(I('status') == 3): ?>selected<?php endif; ?>>存异常</option>
          </select>      
          <input  type="submit"  name='dosubmit' class="btn-info" value="搜索" />
        </form>
		</div>
		</td>
	 </tr>
    </tbody>
   </table>
  </div>
</div>

<div class="table-list">
   <form name="myform"  id="myform" action="__URL__/del" method="POST">
    <table width="98%" cellspacing="0" style="font-size:12px;">
       <thead>
        <tr>
        <th width="50" align="left"><input class="check-all" type="checkbox" value=""></th>
        <th align="left">ID</th>
		<th align="left">电视账号</th>
		<!-- <th align="left">易视腾ID</th> -->
		<th align="left">广电号</th>
		<th align="left">设备MAC</th>		
		<th align="left">状态</th>
		<th align="left">加入时间</th>
		<th align="left">更新时间</th>
		<th align="left">所属酒店</th>	
		<th align="left">管理操作</th>				
        </tr>
       </thead>
    <tbody> 
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	<td align="left"><input type="checkbox" name="ids[]" class="aids" value="<?php echo ($vo['id']); ?>"></td>	
	<td align="left"><?php echo ($vo['id']); ?></td>
	<td align="left"><?php echo ($vo['user_id']); ?></td>
	<!-- <td align="left"><?php echo ($vo['ysten_id']); ?></td> -->
	<td align="left"><?php echo ($vo['device_code']); ?></td>
	<td align="left"><?php echo ($vo['device_mac']); ?></td>	
	<td align="left">
	<?php if($vo['status'] == 1 AND $vo['device_code'] !='' AND $vo['device_mac'] !=''){ echo '<span style="color:green;">已读取</span>'; } elseif($vo['status'] == 2){ echo '<span style="color:orange;">不存在</span>'; } elseif($vo['status'] == 3){ echo '<span style="color:#FF6A31;">存异常</span>'; } else{ } ?>
	<td align="left"><?php echo ($vo['at_time']); ?></td>
	<td align="left"><?php echo ($vo['up_time']); ?></td>
	<td align="left"><?php echo ($vo['hotel_name']); ?></td>
	</td>
	<td align="left">
	<a href="<?php echo U('del',array('ids'=>$vo['id'],'user_id'=>$vo['user_id']));?>" onClick="return confirm('确定删除该记录吗?')">删除</a>
	</td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>	
    </tbody>
    </table>
	<div class="btn" style="font-size:12px;">
     <label for="check_box" id ="CheckedRev">
		<a href="javascript:void(0)" class="coolbg import">导入</a>&nbsp;
		<!-- <a href="javascript:history.back();" class="coolbg">返回</a>&nbsp; -->
		<input type="button" id="del" value="删除" class="coolbg"/>  	
     </label>
     <div id="pages"> <?php echo ($page); ?></div>
	</div>
 </form>
</div>
</div>
<script>
$(function(){
    $(".check-all").click(function () {
        $(".aids").prop("checked", this.checked);
    });
	
	$("#del").click(function () {
          if(confirm('确定删除该记录吗?')){
        	 return window.myform.submit();
          }
          else{
        	  return false;
          }
	})
	
	//导入
	$('.import').click(function(){
		window.location = "<?php echo U('import');?>";
	});
	
	
	
});
</script>
</body>
</html>