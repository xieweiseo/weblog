<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>『酒店业务管理平台』</title>
<link rel="shortcut icon" href=" /favicon.ico" /> 
<link href="__PUBLIC__/admin/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__PUBLIC__/admin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"/>
</head>
<link href="__PUBLIC__/Js/bootstrap/css/bootstrap.css" rel="stylesheet"/>
<link href="__PUBLIC__/admin/js/layer/skin/default/layer.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Js/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="__PUBLIC__/admin/js/jquery.min.js" type="text/javascript" ></script> 
<script src="__PUBLIC__/admin/js/layer/layer.js" type="text/javascript" ></script>
<script src="__PUBLIC__/Js/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>
<script src="__PUBLIC__/Js/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/Js/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<body>
<div>
 <div>
    <div style="background:#EEF5DE;height:22px;padding-top: 8px;padding-bottom:22px;">
      <span style="margin-right:5px;margin-left:5px;font-size:12px;">当前位置: 酒店开机数&nbsp;&gt;&nbsp;</span>
    </div>   
</div>
<div style="border:1px solid #C8DAA3;margin-bottom:3px;">
</div>
<div class="pad_10" style="margin-right:10px;margin-left:10px;font-size:12px;">
<div id="searchid">
   <table width="98%" cellspacing="0" class="search-form">
    <tbody>
	  <tr>
		<td>
		<div style="border:1px solid #87b87f;zoom:1; background: rgba(255, 255, 255, 0.15); padding:5px 10px; line-height:20px;">
		<form id="search_form" class="form-inline" action="__URL__/single_hotel" method="post">
                  酒店名称
          <input type="text" name=hotel_name value="<?php echo I('hotel_name');?>" >&nbsp;
                  选择时间     
          <input type="text" name="trunon_date" value="<?php echo ($trunon_date); ?>" id="date_time">&nbsp;
           
          <input  type="submit"  name='dosubmit' class="btn-info" value="查询" />
        </form>
		</div>
		</td>
	 </tr>
    </tbody>
   </table>
  </div>
</div>

<div class="table-list">
   <form name="myform"  id="myform" action="#" method="POST">
    <table width="98%" cellspacing="0" style="font-size:12px;">
       <thead>
        <tr>
        <th width="50" align="left"><input class="check-all" type="checkbox" value=""></th>
        <!-- <th align="left">ID</th> -->
		<th align="left">酒店名</th>
		<th align="left">设备数</th>
		<th align="left">开机数</th>
		<th align="left">开机时间</th>						
        </tr>
       </thead>
    <tbody> 
    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	<td align="left"><input type="hidden" name="ids[]" value="#" > <input type="checkbox" name="macs[]" class="aids" value="#" id="id"></td>	
	<!-- <td align="left"></td> -->
	<td align="left"><?php echo ($vo['hotel_name']); ?></td>
	<td align="left"><?php echo ($vo['decount']); ?></td>
	<td align="left"><?php if($vo['tocount'] == 0): ?><font style="color:red;"><?php echo ($vo['tocount']); ?></font><?php else: echo ($vo['tocount']); endif; ?></td>
	<td align="left"><?php echo ($vo['date']); ?></td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
    </table>
	<div class="btn" style="font-size:12px;">
     <label for="check_box" id ="CheckedRev">    
      <input type="button" id="back" value="返回" class="coolbg" style="line-height:18px;"/>
     </label>
     <div id="pages"><?php echo ($page); ?></div>
	</div>
 </form>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {	
	//返回
	$('#back').click(function(){
		//window.location.href = "<?php echo U('/report/index');?>";
		window.history.back();
	});	
	
	$('#date_time').daterangepicker({
		format:"YYYY-MM-DD",
		singleDatePicker: true,
		showDropdowns: true,
		minDate:'2017-01-01',
		maxDate:'2030-01-01',
		startDate:'2018-03-01',
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