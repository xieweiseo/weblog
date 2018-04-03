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
<link href="__PUBLIC__/Js/bootstrap/css/bootstrap.css" rel="stylesheet"/>
<link href="__PUBLIC__/Js/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/admin/js/poshytip-1.2/tip-green/tip-green.css" rel="stylesheet" type="text/css" />
<script src="__PUBLIC__/admin/js/jquery.min.js" type="text/javascript" ></script>
<script src="__PUBLIC__/admin/js/layer/layer.js" type="text/javascript" ></script>
<script src="__PUBLIC__/admin/js/poshytip-1.2/jquery.poshytip.js" type="text/javascript" ></script>
<script src="__PUBLIC__/Js/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>
<script src="__PUBLIC__/Js/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/Js/daterangepicker/daterangepicker.js" type="text/javascript"></script>


<style>
#content{height:2000px}#back-to-top.show{opacity:1}#back-to-top{position:fixed;bottom:40px;right:13px;z-index:9999;width:32px;height:32px;text-align:center;line-height:30px;background:#f5f5f5;color:#444;cursor:pointer;border-radius:2px;text-decoration:none;opacity:0;transition:opacity .2s ease-out;border:1px solid #ddd;
}a {
color: #444; 
  text-decoration: none;
}
a:hover,
a:focus {
  color: #444;
text-decoration: none;
}
td,th{height:32px;}


</style>
<body>
    <a href="#" id="back-to-top" title="Back to top">&uarr;</a>
<div>
 <div>
    <div style="background:#EEF5DE;height:28px;padding-top: 7px;">
      <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置 : 酒店 >列表</span>
    </div>   
</div>
<div style="border:1px solid #C8DAA3;margin-bottom:3px;">
 <div>
   <div style="background:#FFFFFF;height:32px;margin-bottom:2px;padding-top:8px;">
	   <a href="__URL__/import"  target="main" title="酒店列表" style="text-decoration:none;color:#333">
	   <span style="margin-right:5px;margin-top:5px;margin-left:10px;padding: 3px;font-size:12px;background-color:#618d01;color:#fff;">
	          导 入</span></a>|
	   <span style="margin-right:5px;margin-left:5px;font-size:12px;">
	   <a href="__URL__/add" style="text-decoration:none;" target="main" title="添加酒店">添加酒店</a>
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
                   酒店名
          <input type="text" name="hotel_name" value="<?php echo I('hotel_name');?>">&nbsp;		
<!--                    分组
          <input type="text" name="group_id" value="<?php echo I('group_id');?>" size="5">&nbsp; -->
	 MAC	  
		  <input type="text" name="device_mac" size="20" value="<?php echo I('device_mac');?>">&nbsp;  
	 <!--上线数-->
	      <select name="online_count"><option value="rc" <?php if($_POST['online_count']=="rc"){echo "selected='selected'";} ?>>房间数</option><option value="oc" <?php if($_POST['online_count']=="oc"){echo "selected='selected'";} ?>>上线数</option></select>
	      <input type="text" name="start_num" size="5" value="<?php echo I('start_num');?>"> --  
	 	  <input type="text" name="end_num" size="5" value="<?php echo I('end_num');?>">&nbsp;        
<!--                   地区    
		  <span id="city_china_val">		
			<select name="province" class="province" data-value="<?php echo I('province');?>" data-first-title="选择省" disabled="disabled"></select>		
			<select name="city" class="city" data-value="<?php echo I('city');?>" data-first-title="选择市" disabled="disabled"></select>		
			<select name="area" class="area" data-value="<?php echo I('area');?>" data-first-title="选择地区" disabled="disabled"></select>		
		  </span>    -->
	  状态 <select name="online"><option value="0">--全部--</option><option value="1" <?php if(I('online')==1){echo 'selected="selected"';} ?>>已上线</option><option value="2" <?php if(I('online')==2){echo 'selected="selected"';} ?>>未上线</option></select>	              
          
             时间
          <input type="text" name="timegap" value="<?php echo I('timegap');?>" id="start_time" style="width:190px;">
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
        <th align="left">酒店名</th>
        <th align="left">地区</th>
		<th align="left">分组</th>
		<th align="left">播控ID</th>
		<th align="center">设备数</th>
		<th align="center">上线数</th>
		<th align="left">创建时间</th>	
		<th align="left">管理操作</th>							
        </tr>
       </thead>
    <tbody>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	<td align="left"><input type="checkbox" name="id[]" class="aids" value="<?php echo ($vo['id']); ?>" id="id"></td>	
	<td align="left"><?php echo ($vo['hid']); ?></td>
	<td align="left"><a class="tips" title="点击查看酒店开机数" href="<?php echo U('report/single_hotel',array('hotel_name'=>$vo['hotel_name']));?>"><?php echo ($vo['hotel_name']); ?></a></td>
	<td align="left"><?php echo ($vo['province']); if(!empty($vo['city'])){ echo ($vo['city']); } ?></td>
	<td align="left"><?php echo ($vo['group_id']); ?></td>
	<td align="left"><?php if(($vo['group_id'] != $vo['ysten_gid']) AND $vo['ysten_gid'] != ''): ?><font style="color:red"><?php echo ($vo['ysten_gid']); ?></font><?php else: echo ($vo['ysten_gid']); endif; ?></td>
	<td align="center">
	 <a class="tips" title="点击查看酒店设备" href="<?php echo U('Device/index',array('hotel_id'=>$vo['id'],'group_id'=>$vo['group_id']),'');?>">
	  <?php if($vo['count'] != 0 ): ?><font style="color:green"><?php echo ($vo['count']); ?></font><?php else: echo ($vo['count']); endif; ?>
	  </a></td>
	<td align="center">
	 <a class='tips' title="点击查看酒店设备" href="<?php echo U('Device/index',array('hotel_id'=>$vo['id'],'group_id'=>$vo['group_id']),'');?>">
	  <?php if($vo['count'] == $vo['online_count'] AND $vo['count'] != 0 AND $vo['online_count'] != 0): ?><font style="color:green"><?php echo ($vo['online_count']); ?></font>
	  	<?php else: ?>
 			<font style="color:red"><?php echo ($vo['online_count']); ?></font><?php endif; ?>
	 </a></td>   	
	<td align="left"><?php echo ($vo['create_date']); ?></td>
	<td align="left">
	<a href="<?php echo U('Device/index',array('hotel_id'=>$vo['id'],'group_id'=>$vo['group_id']),'');?>" class='tips'>查看</a> | 
	<a href="__URL__/edit/id/<?php echo ($vo['id']); ?>">修改</a> | 
	<a href="__URL__/del/id/<?php echo ($vo['id']); ?>" onClick="return confirm('确定删除该记录吗?')">删除</a>
	</td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
    </table>
	<div class="btn" style="font-size:12px;">
     <label for="check_box" id ="CheckedRev">
     <a href="javascript:void(0)" id="ysten_gid" class="coolbg">同步</a>&nbsp;
     </label>
     &nbsp;<input type="button" id="del" value="删除" class="coolbg"/>  
     <span style="margin-left:8px;">酒店总数：<?php echo ($info['total']); ?>&nbsp;&nbsp;设备总数：<?php echo ($info['count']); ?>&nbsp;&nbsp;上线总数：<?php echo ($info['online']); ?></span>	
     &nbsp;&nbsp;<div id="pages"><?php echo ($page); ?></div>
	</div>
 </form>
</div>
</div>
<script src="__PUBLIC__/Js/cxselect/jquery.cxselect.min.js"></script>
<script type="text/javascript">
$.cxSelect.defaults.url = "__PUBLIC__/Js/cxselect/cityData.min.json";
$('#city_china_val').cxSelect({
	selects: ['province', 'city', 'area'],
	nodata: 'none'
});

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
	
	//提示
	//var tip_index = 0;
	//$(".tips").bind("mouseenter",function(){
	//tip_index=layer.tips('点击查看酒店设备','#id',{tips: [1, '#B7CB4B'],time:0,tipsMore: true});
	//}).bind('mouseleave','1',function(){layer.close(tip_index);});
	
	//鼠标悬停提示
	$('.tips').poshytip({className: 'tip-green',offsetY: 15,offsetX: 8,showTimeout: 0});
		
	//同步	
	$('#ysten_gid').click(function(){
		//window.location.href = "<?php echo U('/device/synchro');?>";return;
		var data = <?php echo isset($list[0]['id'])?$list[0]['id']:0 ?>;
		if(data!=0){
			$.ajax({
				  type: "POST",
				  url: "<?php echo U('/device/synchro');?>",
				  data: '',
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
})

//跳转top
if ($('#back-to-top').length) {
  var scrollTrigger = 500; // px
  // $(window).scrollTop()与 $(document).scrollTop()产生结果一样
  // 一般使用document注册事件，window使用情况如 scroll, scrollTop, resize
  $(window).on('scroll', function () {
      if ($(window).scrollTop() > scrollTrigger) {
          $('#back-to-top').addClass('show');
      } else {
          $('#back-to-top').removeClass('show');
      }
  });

  $('#back-to-top').on('click', function (e) {
      // html,body 都写是为了兼容浏览器
      $('html,body').animate({
          scrollTop: 0
      }, 300);

      return false;
  });
}


$(document).ready(function() {
	$('#start_time').daterangepicker({
		format:"YYYY-MM-DD",
		singleDatePicker: false,
		showDropdowns: true,
		minDate:'2017-01-01',
		maxDate:'2030-01-01',
		startDate:'2017-01-01',
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
           '今天': [moment(), moment()],
           '昨天': [moment().subtract('days', 1), moment().subtract('days', 1)],
           '最近7天': [moment().subtract('days', 6), moment()],
           '最近30天': [moment().subtract('days', 29), moment()],
           '上一个月': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        opens: 'right',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
	    locale : {
            applyLabel : '确定',
            cancelLabel : '取消',
            fromLabel : '起始时间',
            toLabel : '结束时间',
            customRangeLabel : '自定义',
            daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
            monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
            firstDay : 1
        }
	});
});
</script>
</body>
</html>