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
<link href="__PUBLIC__/admin/js/poshytip-1.2/tip-green/tip-green.css" rel="stylesheet" type="text/css" />
<script src="__PUBLIC__/admin/js/jquery.min.js" type="text/javascript" ></script>
<script src="__PUBLIC__/admin/js/layer/layer.js" type="text/javascript" ></script>
<script src="__PUBLIC__/admin/js/poshytip-1.2/jquery.poshytip.js" type="text/javascript" ></script>
<body>
<div>
 <div>
    <div style="background:#EEF5DE;height:20px;padding-top: 5px;">
      <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置: 酒店查看&nbsp;>&nbsp;<?php echo ($hotel_name); ?></span>
    </div>   
</div>
<div style="border:1px solid #C8DAA3;margin-bottom:3px;">
 <div>
   <div style="background:#FFFFFF;height:25px;margin-bottom:2px;padding-top:8px;">
	   <a href="<?php echo U('import',array('hotel_id'=>$hotel_id,'group_id'=>$group_id,'hotel_name'=>$hotel_name));?>"  target="main" title="酒店列表" style="text-decoration:none;color:#333">
	   <span style="margin-right:5px;margin-top:5px;margin-left:10px;padding: 3px;font-size:12px;background-color:#618d01;color:#fff;">
	          导 入</span></a>| 
	   <span style="margin-right:5px;margin-left:5px;font-size:12px;">
	   <a href="<?php echo U('edit',array('hotel_id'=>$hotel_id,'group_id'=>$group_id),'');?>" style="text-decoration:none;" target="main" title="添加设备">添加设备</a>
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
          <input type="hidden" name="hotel_id" value="<?php echo ($hotel_id); ?>"> 
          <select name="ysten_gid" id="ysten_gid" style="height:31px;margin-bottom:3px;">
          	<option value="0">--全部--</option>
          	<option value="-1" <?php if(I('ysten_gid') == -1): ?>selected<?php endif; ?>>未上线</option>
          	<option value="1" <?php if(I('ysten_gid') == 1): ?>selected<?php endif; ?>>已上线</option>
          </select> 
         <!--  <input  type="hidden" name="export" >  -->    
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
   <form name="myform"  id="myform" action="__URL__/syncs" method="POST">
    <table width="98%" cellspacing="0" style="font-size:12px;">
       <thead>
        <tr>
        <th width="50" align="left"><input class="check-all" type="checkbox" value=""></th>
        <th align="left">ID</th>
		<th align="left">电视账号</th>
		<th align="left">广电号</th>
		<th align="left">设备MAC</th>
		<th align="left">分组ID</th>
		<th align="left">播控ID</th>		
		<th align="left">状态</th>
		<th align="left">创建时间</th>	
		<th align="left">管理操作</th>							
        </tr>
       </thead>
    <tbody> 
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	<td align="left"><input type="hidden" name="ids[]" value="<?php echo ($vo['id']); ?>" > <input type="checkbox" name="macs[]" class="aids" value="<?php echo ($vo['device_mac']); ?>" id="id"></td>	
	<td align="left"><?php echo ($vo['devid']); ?></td>
	<td align="left"><a title="点击可查看用户账号状态" href="<?php echo U('/account/index',array('user_id'=>$vo['user_id']));?>"><?php echo ($vo['user_id']); ?></a></td>
	<td align="left"><?php echo ($vo['device_code']); ?></td>
	<td align="left"><a title=<?php if($vo['retain_mac'] != null): ?>"<?php echo ($vo['retain_mac']); ?>"<?php else: ?>"服务集查询，面板预览"<?php endif; ?> href="__URL__/service/mac/<?php echo ($vo['device_mac']); ?>/hotel/<?php echo ($hotel_name); ?>" class="tips"><?php if($vo['retain_mac'] != null): ?><font style="color:red;"><?php echo ($vo['device_mac']); ?></font><?php else: echo ($vo['device_mac']); endif; ?></a></td>		
	<td align="left"><?php echo ($vo['group_id']); ?></td>
	<td align="left"><?php echo ($vo['ysten_gid']); ?></td>
	<td align="left"><?php if($vo['group_id'] == $vo['ysten_gid'] AND $vo['group_id'] != 0 AND $vo['ysten_gid'] != 0 ): ?><font style="color:green">已上线</font><?php else: ?><font style="color:#F09815">未上线</font><?php endif; ?></td>
	<td align="left"><?php echo ($vo['create_date']); ?></td>
	<td align="left">
	<a href="__URL__/sync/mac/<?php echo ($vo['device_mac']); ?>" title="分组ID同步">同步</a> | 
	<a id="htid" data="<?php echo ($vo['hotel_id']); ?>" href="__URL__/service/mac/<?php echo ($vo['device_mac']); ?>" title="服务集查询，面板预览" class="tips">查看</a> | 
	<a href="__URL__/edit/id/<?php echo ($vo['id']); ?>">修改</a> | 
	<a href="__URL__/del/ids/<?php echo ($vo['id']); ?>" onClick="return confirm('确定删除该记录吗?')">删除</a>	
	</td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>	
    </tbody>
    </table>
	<div class="btn" style="font-size:12px;">
     <label for="check_box" id ="CheckedRev">
     <a href="<?php echo U('index',array('export'=>1,'hotel_id'=>$hotel_id,'ysten_gid'=>I('ysten_gid'),'device_mac'=>I('device_mac'),'device_code'=>I('device_code'),'user_id'=>I('user_id')));?>" id="export" class="coolbg">导出</a>&nbsp;
     <a href="javascript:void(0)" id="synchro" class="coolbg">同步</a>&nbsp;
     <input type="button" id="del" value="删除" class="coolbg"/>&nbsp;      
     <input type="button" id="back" value="返回" class="coolbg"/>
     </label>
     <div id="pages"><?php echo ($page); ?></div>
	</div>
 </form>
</div>
</div>
</div>
<script type="text/javascript">
$(function(){
    $(".check-all").click(function () {
        $(".aids").prop("checked", this.checked);
    });	
	
	$("#del").click(function () {
          if(confirm('确定删除该记录吗?')){
        	  $("form").attr("action","<?php echo U('del');?>").submit();
        	 //return window.myform.submit();
          }
          else{
        	  return false;
          }
	});
	
    //搜索
    $('#ysten_gid').change(function(){
    	search_form.submit();
    });
        
	//返回
	$('#back').click(function(){
		window.location.href = "<?php echo U('/hotel/index');?>";
	});	

	//同步 synchro	
	$('#synchro').click(function(){
		var number = $("input[type='checkbox']:checked").length;
		if(number!=0){
			window.myform.submit();return;
		}
		//window.location.href = "<?php echo U('/device/synchro',array('hotel_id'=>$hotel_id));?>";return;
		var hotel_id = $("#htid").attr('data');
		var data = <?php echo isset($list[0]['id'])?$list[0]['id']:0 ?>;
		if(data!=0){
			$.ajax({
				  type: "POST",
				  url: "<?php echo U('/device/synchro');?>",
				  data: {hotel_id:hotel_id},
				  dataType:"json",
				  beforeSend:function(){
					//加载层
					  var index = layer.load(0, {shade: false});
					  var index = layer.load(1, {
						content: '数据同步中，请稍后...',
					    shade: [0.6,'#fff'],
					    success: function(layero) {
					    	layero.css('padding-left', '2px');
					    	layero.find('.layui-layer-content').css({
					    	'padding-top': '40px',
					    	'width': '260px',
					    	'font-size':'16px',
					    	'color':'#FF2C00',
					    	'background-position-x': '16px'
					    	});
					       }				  
					  
					  });		 
				    },
				  success: function(res){
					  if(res.status){
						  layer.closeAll('loading');
						  location.reload();
					  }
					  else{
						  alert('同步失败！');
					  }
					  
				}
			});	
	    }
		else{
			alert('没有数据，暂无法进行同步');
		}
						
	});	
	
	//提示
	//var tip_index = 0;
	//$(".tips").bind("mouseenter",function(){
	//tip_index=layer.tips('点击查看设备分组及面板状态','#id',{tips: [1, '#B7CB4B'],time:0,tipsMore: true});
	//}).bind('mouseleave','1',function(){layer.close(tip_index);});	
	
	//鼠标悬停提示
	$('.tips').poshytip({className: 'tip-green',offsetY: 15,offsetX: 8,showTimeout: 0});		
});
</script>
</body>
</html>