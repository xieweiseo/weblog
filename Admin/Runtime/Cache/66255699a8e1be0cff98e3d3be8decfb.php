<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="__PUBLIC__/admin/menu.css" type="text/css" />
<style type="text/css"> .menu{margin:0 auto;padding-left:5px;padding-top:8px;padding-right:5px;}</style>                
<script type="text/javascript">
var number = <?php echo ($count); ?>//4;
function LMYC() {
var lbmc;
//var treePic;
    for (i=1;i<=number;i++) {
        lbmc = eval('LM' + i);
        //treePic = eval('treePic'+i);
        //treePic.src = 'images/file.gif';
        lbmc.style.display = 'none';
    }
}
 
function ShowFLT(i) {
	//alert(i);
    lbmc = eval('LM' + i);
    //alert(lbmc);
    //treePic = eval('treePic' + i)
    if (lbmc.style.display == 'none') {
        LMYC();
        //treePic.src = 'images/nofile.gif';
        lbmc.style.display = '';
    }
    else {
        //treePic.src = 'images/file.gif';
        lbmc.style.display = 'none';
    }
}
</script>          
</head>
<body target="main">
<div class="menu">
    <?php $i=1;foreach($menu_list as $vo): ?>
      <dl class="bitem">
        <A onclick=javascript:ShowFLT(<?php echo ($i); ?>) href="javascript:void(null)"><dt><b><?php echo ($vo["title"]); ?></b></dt></A>
        <dd class="sitem" >
           <ul class="sitemu" style="display:none" id="LM<?php echo ($i); ?>">
            <?php foreach($vo['sub_node'] as $val): ?>
             <li class="onactive">
              <div class="items">
                <div class="fllct"><a href="__GROUP__/<?php echo ($val["name"]); ?>"  target="main" title="<?php echo ($val["title"]); ?>"><?php echo ($val["title"]); ?></a></div>								        
              </div>
            </li>
            <?php endforeach; ?>           
          </ul>          
        </dd>
      </dl>
     <?php $i++;endforeach; ?>	  
	 </div>
<script src="__PUBLIC__/admin/js/jquery.min.js" type="text/javascript" ></script> 	 
<script type="text/javascript">  
$(function(){
    $(".onactive").click(function() {  
        $('.onactive').removeAttr("id")
        $(this).attr("id","active");
    });
}); 
</script> 	
</body>
</html>