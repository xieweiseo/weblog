<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="zh-CN">
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="robots" content="none">
<meta http-equiv="Pragma" content="no-cache">
<meta name="Author" Content="wangdong">
<title>行业运营管理平台 by www.digitlink.cn</title>
<script type="text/javascript">
if(window.top!=window){
    window.top.location.href=document.location.href;
}
</script>
<link rel="shortcut icon" href="__ROOT__/favicon.ico" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/semantic/semantic.min.css">
<script src="__PUBLIC__/admin/js/jquery.min.js"></script>
<script src="__PUBLIC__/admin/semantic/semantic.min.js"></script>
<style type="text/css">
body {
	background-color: #EEEEEE;
}
body > .grid {
	height: 100%;
}
.image {
	margin-top: -100px;
	height:60px;
}
.column {
	max-width: 450px;
}
.ui.teal.button, .ui.teal.buttons .button {
    background-color: #74a900;
    color: #fff;
    text-shadow: none;
    background-image: none;
} 
.ui.teal.button:hover, .ui.teal.buttons .button:hover {
    background-color: #699705;
    color: #fff;
    text-shadow: none;
}
.ui.teal.header {
    color: #74a900!important;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('.ui.form').form({
		fields: {
			username: {
				identifier  : 'username',
				rules: [
					{
						type   : 'length[2]',
						prompt : '用户名不少于2个字符'
					}
				]
			},
			password: {
				identifier  : 'password',
				rules: [
					{
						type   : 'length[4]',
						prompt : '密码不少于4个字符'
					}
				]
			},
/* 			code: {
				identifier  : 'code',
				rules: [
					{
						type   : 'exactLength[5]',
						prompt : '验证码为5个字符'
					}
				]
			} */
		},
		onSuccess: function(event, fields){
			var $form = $('.ui.form');
			var url   = $form.attr('action');
			$form.addClass('loading');

			$.ajax({
				type: 'POST',
				url: url,
				data: $form.serialize(),
				dataType: 'json',
				cache: false,
				success: function(res){
					if(!res.status){
						$form.removeClass('loading');
						$('.code').trigger('click');
						$('.dimmer').find('.info').text(res.info);
						$('.dimmer').dimmer('show');
					}else{
						window.location.href = res.url;
					}
				},
				error: function(){
					$form.removeClass('loading');
					$('.code').trigger('click');
					$('.dimmer').find('.info').text('网络异常');
					$('.dimmer').dimmer('show');
				}
			});
		}
	}).on('submit', function(){
		return false;
	});
	$('.code').popup({position:'bottom left'}).on('click', function(){
		var src = $(this).attr('src');
		$(this).prop('src', src + '&' + Math.random());
	});
});
</script>
</head>
<body>

<div class="ui middle aligned center aligned grid">
	<div class="column">
		<h2 class="ui teal header">
			<img src="__PUBLIC__/admin/images/logo.png" class="image">
			<div class="content">行业运营管理平台OMS</div>
		</h2>
		<form class="ui large form" action="__URL__/checkLogin" method="post">
			<div class="ui stacked segment">
				<div class="field">
					<div class="ui left icon input">
						<i class="user icon"></i>
						<input type="text" name="username" value="" placeholder="请输入用户名">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input">
						<i class="lock icon"></i>
						<input type="password" name="password" value="" placeholder="请输入密码">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input">
						<i class="protect icon"></i>
						<input type="text" name="code" placeholder="请输入验证码">
						<div><img data-content="点击切换验证码" class="code" src="__URL__/code/" /></div>
					</div>
				</div>
				<div class="ui fluid large teal submit button">登录</div>
			</div>

			<div class="ui error message"></div>

		</form>
	</div>
</div>
<div class="ui page dimmer">
	<div class="content">
		<div class="center">
			<h2 class="ui inverted icon header">
				<i class="warning circle icon"></i>
				<span class="info">登录失败</span>
			</h2>
		</div>
	</div>
</div>

</body>

</html>