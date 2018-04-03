<?php
//redis 配置文件
return array(
'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
'DATA_CACHE_TYPE'=>'Redis',//默认动态缓存为Redis
'REDIS_RW_SEPARATE' => true, //Redis读写分离 true 开启
'REDIS_HOST'=>'127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
'REDIS_PORT'=>'6379',//端口号
'REDIS_TIMEOUT'=>'30',//超时时间
'REDIS_PERSISTENT'=>false,//是否长连接 false=短连接
'REDIS_AUTH'=>'',//AUTH认证密码
'DATA_CACHE_TIME' => 10, //10秒后失效
);
?>