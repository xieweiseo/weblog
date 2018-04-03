<?php
if (!defined('W3CORE_PATH')) exit();
return array(
	'DB_NAME'=>'dhtal',
	'DB_HOST'=>'127.0.0.1',
	'DB_USER'=>'root',
	'DB_PWD'=>'root',
	'DB_TYPE'=>'mysql',
	'DB_PORT'=>3306, 
	'DB_PREFIX'=>'wb_',
	'RBAC_ROLE_TABLE'=>'wb_role',
	'RBAC_USER_TABLE'=>'wb_role_user',
	'RBAC_ACCESS_TABLE'=>'wb_access',
	'RBAC_NODE_TABLE'=>'wb_node',
	'KEYCODE'=>'x53pc9',
    'APP_DEBUG'=>  true,// 开启调试模式
    $DB_CONFIG => "mysql://root:root@127.0.0.1:3306/panel"
);
?>