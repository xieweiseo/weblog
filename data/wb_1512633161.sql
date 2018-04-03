#
# WBlog bakfile
#Time: 2017-12-07 15:52
# Type: 
# w3note: http://www.w3note.com
# --------------------------------------------------------


DROP TABLE IF EXISTS wb_access;
CREATE TABLE `wb_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `pid` int(11) NOT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO wb_access VALUES ('1','16','2','','12');
INSERT INTO wb_access VALUES ('1','14','2','','12');
INSERT INTO wb_access VALUES ('1','11','2','','12');
INSERT INTO wb_access VALUES ('1','10','2','','12');
INSERT INTO wb_access VALUES ('1','9','2','','12');
INSERT INTO wb_access VALUES ('1','12','1','','0');
INSERT INTO wb_access VALUES ('1','8','2','','26');
INSERT INTO wb_access VALUES ('2','12','1','','0');
INSERT INTO wb_access VALUES ('2','56','0','','26');
INSERT INTO wb_access VALUES ('2','26','1','','0');
INSERT INTO wb_access VALUES ('3','56','0','','26');
INSERT INTO wb_access VALUES ('2','13','2','','25');
INSERT INTO wb_access VALUES ('1','26','1','','0');
INSERT INTO wb_access VALUES ('1','19','2','','25');
INSERT INTO wb_access VALUES ('1','18','2','','25');
INSERT INTO wb_access VALUES ('1','17','2','','25');
INSERT INTO wb_access VALUES ('1','15','2','','25');
INSERT INTO wb_access VALUES ('1','13','2','','25');
INSERT INTO wb_access VALUES ('1','25','1','','0');
INSERT INTO wb_access VALUES ('1','24','2','','1');
INSERT INTO wb_access VALUES ('1','23','2','','1');
INSERT INTO wb_access VALUES ('1','22','2','','1');
INSERT INTO wb_access VALUES ('1','7','2','','1');
INSERT INTO wb_access VALUES ('1','6','2','','1');
INSERT INTO wb_access VALUES ('1','5','2','','1');
INSERT INTO wb_access VALUES ('1','4','2','','1');
INSERT INTO wb_access VALUES ('1','3','2','','1');
INSERT INTO wb_access VALUES ('1','1','1','','0');
INSERT INTO wb_access VALUES ('1','2','2','','1');
INSERT INTO wb_access VALUES ('1','28','2','','26');
INSERT INTO wb_access VALUES ('1','56','0','','26');
INSERT INTO wb_access VALUES ('1','27','2','','26');
INSERT INTO wb_access VALUES ('2','25','1','','0');
INSERT INTO wb_access VALUES ('3','11','2','','12');
INSERT INTO wb_access VALUES ('3','12','1','','0');
INSERT INTO wb_access VALUES ('3','26','1','','0');
INSERT INTO wb_access VALUES ('3','3','2','','1');
INSERT INTO wb_access VALUES ('2','59','1','','0');
INSERT INTO wb_access VALUES ('3','2','2','','1');
INSERT INTO wb_access VALUES ('3','1','1','','0');
INSERT INTO wb_access VALUES ('2','2','2','','1');
INSERT INTO wb_access VALUES ('2','1','1','','0');
INSERT INTO wb_access VALUES ('8','16','2','','12');
INSERT INTO wb_access VALUES ('8','14','2','','12');
INSERT INTO wb_access VALUES ('8','11','2','','12');
INSERT INTO wb_access VALUES ('8','10','2','','12');
INSERT INTO wb_access VALUES ('8','9','2','','12');
INSERT INTO wb_access VALUES ('8','12','1','','0');
INSERT INTO wb_access VALUES ('2','11','2','','12');
INSERT INTO wb_access VALUES ('19','26','1','','0');
INSERT INTO wb_access VALUES ('19','27','2','','26');
INSERT INTO wb_access VALUES ('19','56','0','','26');
INSERT INTO wb_access VALUES ('19','12','1','','0');
INSERT INTO wb_access VALUES ('19','11','2','','12');
INSERT INTO wb_access VALUES ('20','1','1','','0');
INSERT INTO wb_access VALUES ('20','2','2','','1');
INSERT INTO wb_access VALUES ('20','26','1','','0');
INSERT INTO wb_access VALUES ('20','27','2','','26');
INSERT INTO wb_access VALUES ('20','12','1','','0');
INSERT INTO wb_access VALUES ('20','11','2','','12');

DROP TABLE IF EXISTS wb_device;
CREATE TABLE `wb_device` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL DEFAULT '0',
  `user_id` varchar(32) NOT NULL DEFAULT '' COMMENT '账号',
  `device_code` varchar(32) NOT NULL DEFAULT '' COMMENT '广电号',
  `device_mac` varchar(32) NOT NULL DEFAULT '',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '分组ID',
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_mac` (`device_mac`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=478 DEFAULT CHARSET=utf8;

INSERT INTO wb_device VALUES ('440','27','147601780311001','019972001640383','50:01:6b:90:d0:9e','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('441','27','147601780311002','019972001650820','50:01:6b:90:b6:d2','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('442','27','147601780311003','019972001650689','50:01:6b:90:b6:d4','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('443','27','147601780311004','019972001651019','50:01:6b:90:b7:12','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('444','27','147601780311005','019972001652189','50:01:6b:90:b6:f0','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('445','27','147601780311006','019972001652113','50:01:6b:90:b6:a0','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('446','27','147601780311007','019972001650813','50:01:6b:90:d0:c6','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('447','27','147601780311008','019972001651047','50:01:6b:90:b7:5c','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('448','27','147601780311009','019972001650967','50:01:6b:90:b8:e8','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('449','27','147601780311010','019972001650849','50:01:6b:90:b6:f8','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('450','27','147601780311011','019972001650664','50:01:6b:90:b6:b4','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('451','27','147601780311012','019972001650920','50:01:6b:90:b6:fc','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('452','27','147601780311013','019972001650765','50:01:6b:90:b6:a6','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('453','27','147601780311014','019972001651073','50:01:6b:90:b6:82','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('454','27','147601780311015','019972001651069','50:01:6b:90:d0:c2','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('455','27','147601780311016','019972001651374','50:01:6b:90:a9:a0','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('456','27','147601780311017','019972001653119','50:01:6b:90:b9:18','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('457','27','147601780311018','019972001653006','50:01:6b:90:d0:80','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('458','27','147601780311019','019972001653179','50:01:6b:90:b7:02','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('459','27','147601780311020','019972001657640','50:01:6b:90:b8:64','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('460','27','147601780311021','019972001653462','50:01:6b:90:b7:04','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('461','27','147601780311022','019972001653755','50:01:6b:90:bf:6a','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('462','27','147601780311023','019972001653852','50:01:6b:90:82:70','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('463','27','147601780311024','019972001654029','50:01:6b:90:85:f2','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('464','27','147601780311025','019972001654053','50:01:6b:90:b8:9c','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('465','27','147601780311026','019972001657145','50:01:6b:90:b5:a4','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('466','27','147601780311027','019972001657485','50:01:6b:90:b6:a8','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('467','27','147601780311028','019972001654519','50:01:6b:90:b5:5e','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('468','27','147601780311029','019972001654937','50:01:6b:90:b5:3e','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('469','27','147601780311030','019972001654939','50:01:6b:90:b5:72','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('470','27','147601780311031','019972001654954','50:01:6b:90:b5:ee','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('471','27','147601780311032','019972001654947','50:01:6b:90:b6:6a','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('472','27','147601780311033','019972001654979','50:01:6b:90:d0:84','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('473','27','147601780311034','019972001654964','50:01:6b:90:b6:ac','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('474','27','147601780311035','019972001655013','50:01:6b:90:b9:1a','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('475','27','147601780311036','019972001654992','50:01:6b:90:b8:42','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('476','27','147601780311037','019972001657592','50:01:6b:90:b9:3a','2212','2017-12-07 14:42:57','');
INSERT INTO wb_device VALUES ('477','27','147601780311038','019972001655007','50:01:6b:90:b5:80','2212','2017-12-07 14:42:57','');

DROP TABLE IF EXISTS wb_device_group;
CREATE TABLE `wb_device_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(6) NOT NULL DEFAULT '0',
  `group_name` varchar(80) NOT NULL DEFAULT '',
  `district_id` tinyint(1) DEFAULT '0',
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO wb_device_group VALUES ('1','2212','鑫鸿昌酒店','0','');
INSERT INTO wb_device_group VALUES ('2','5621','丽景商务酒店','0','');
INSERT INTO wb_device_group VALUES ('3','11212','32','0','');
INSERT INTO wb_device_group VALUES ('4','32112','fdf','0','');
INSERT INTO wb_device_group VALUES ('5','5666','8977','0','');
INSERT INTO wb_device_group VALUES ('6','8921','ddfd','0','');
INSERT INTO wb_device_group VALUES ('7','7651','dddd','0','');
INSERT INTO wb_device_group VALUES ('8','2252','hhhhh','0','');
INSERT INTO wb_device_group VALUES ('10','2216','ddfsdf','0','');
INSERT INTO wb_device_group VALUES ('11','2212','vvvvvvv','0','');
INSERT INTO wb_device_group VALUES ('12','2448','rrrrr','0','222222');

DROP TABLE IF EXISTS wb_district;
CREATE TABLE `wb_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `desc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO wb_district VALUES ('1','泉州','福建省');
INSERT INTO wb_district VALUES ('2','晋江','');

DROP TABLE IF EXISTS wb_hotel;
CREATE TABLE `wb_hotel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_name` varchar(80) NOT NULL DEFAULT '',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `province` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `area` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` tinyint(3) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

INSERT INTO wb_hotel VALUES ('1','xxx','123422','天津市','0','','1','0','0000-00-00 00:00:00','ddddd');
INSERT INTO wb_hotel VALUES ('2','龙岩长汀-豪门宾馆（鑫佳）','1232','','','','1','0','0000-00-00 00:00:00','xxdsdssss');
INSERT INTO wb_hotel VALUES ('3','汉庭酒店','1212','河北省','邢台市','桥东区','1','0','0000-00-00 00:00:00','ddddd');
INSERT INTO wb_hotel VALUES ('4','eewww','1216','','','','1','0','2017-12-07 12:54:54','dddddd');
INSERT INTO wb_hotel VALUES ('5','ddddd','1212','山西省','大同市','灵丘县','1','0','2017-12-07 12:56:11','dddddd');
INSERT INTO wb_hotel VALUES ('6','ddd','0','0','','','1','0','2017-12-07 14:03:19','sdsd');
INSERT INTO wb_hotel VALUES ('7','yyyyyyy','111','天津市','和平区','','1','0','2017-12-07 14:15:03','ddddd');
INSERT INTO wb_hotel VALUES ('8','dddddd','1111','天津市','0','','1','0','2017-12-07 14:16:15','ddddd');
INSERT INTO wb_hotel VALUES ('9','gggg','3321','河北省','0','','1','0','2017-12-07 14:22:00','ddddd');
INSERT INTO wb_hotel VALUES ('10','11111','2222','北京市','东城区','','1','0','2017-12-07 14:27:12','ddddd');
INSERT INTO wb_hotel VALUES ('11','22222','1121','黑龙江省','齐齐哈尔市','0','1','0','2017-12-07 14:29:03','dddddd');
INSERT INTO wb_hotel VALUES ('12','11212','2211','黑龙江省','齐齐哈尔市','0','1','0','2017-12-07 14:30:58','eeeeee');
INSERT INTO wb_hotel VALUES ('13','11212','2211','河北省','0','','1','0','2017-12-07 14:31:26','eeeeee');
INSERT INTO wb_hotel VALUES ('14','11212','2211','河北省','唐山市','0','1','0','2017-12-07 14:31:53','eeeeee');
INSERT INTO wb_hotel VALUES ('15','11212','2211','天津市','和平区','','1','0','2017-12-07 14:32:09','eeeeee');
INSERT INTO wb_hotel VALUES ('16','11212','2211','天津市','0','','1','0','2017-12-07 14:33:17','eeeeee');
INSERT INTO wb_hotel VALUES ('17','11212','2211','北京市','朝阳区','','1','0','2017-12-07 14:33:46','eeeeee');
INSERT INTO wb_hotel VALUES ('18','xxxxx','2211','北京市','西城区','','1','0','2017-12-07 14:34:00','eeeeee');
INSERT INTO wb_hotel VALUES ('19','11212','2211','天津市','0','','1','0','2017-12-07 14:35:08','eeeeee');
INSERT INTO wb_hotel VALUES ('20','11212','2211','天津市','0','','1','0','2017-12-07 14:36:11','eeeeee');
INSERT INTO wb_hotel VALUES ('21','11212','2211','天津市','0','','1','0','2017-12-07 14:36:50','eeeeee');
INSERT INTO wb_hotel VALUES ('22','11212','2211','天津市','0','','1','0','2017-12-07 14:37:08','eeeeee');
INSERT INTO wb_hotel VALUES ('23','22212','111','北京市','西城区','','1','0','2017-12-07 14:38:12','ssss');
INSERT INTO wb_hotel VALUES ('24','22212','111','0','','','1','0','2017-12-07 14:38:38','ssss');
INSERT INTO wb_hotel VALUES ('25','22212','111','0','','','1','0','2017-12-07 14:40:20','ssss');
INSERT INTO wb_hotel VALUES ('26','22212','111','天津市','河东区','','1','0','2017-12-07 14:42:15','ssss');
INSERT INTO wb_hotel VALUES ('27','22212','111','天津市','和平区','','1','0','2017-12-07 14:42:57','ssss111');

DROP TABLE IF EXISTS wb_log;
CREATE TABLE `wb_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `t` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `log` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS wb_node;
CREATE TABLE `wb_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remark` text NOT NULL,
  `sort` tinyint(3) NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL,
  `islink` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=gb2312;

INSERT INTO wb_node VALUES ('1','0','','酒店管理','1','','0','1','1');
INSERT INTO wb_node VALUES ('8','26','Databakup/index','数据备份','1','数据备份','3','2','1');
INSERT INTO wb_node VALUES ('9','12','Role/index','角色管理','1','角色管理','0','2','1');
INSERT INTO wb_node VALUES ('10','12','Node/index','菜单管理','1','菜单管理','0','2','1');
INSERT INTO wb_node VALUES ('11','12','User/password','修改密码','1','博客管理','0','2','1');
INSERT INTO wb_node VALUES ('12','0','','用户管理','1','用户管理','3','1','1');
INSERT INTO wb_node VALUES ('14','12','User/index','用户列表','1','友情链接管理','0','2','1');
INSERT INTO wb_node VALUES ('16','12','User/insert','添加用户','1','附件管理','0','2','1');
INSERT INTO wb_node VALUES ('64','1','Hotel/index','酒店列表','1','','0','2','1');
INSERT INTO wb_node VALUES ('63','1','District/index','地区列表','1','地区管理','3','2','1');
INSERT INTO wb_node VALUES ('26','0','','系统管理','1','','2','1','1');
INSERT INTO wb_node VALUES ('27','26','Public/main','系统信息','1','','0','2','1');
INSERT INTO wb_node VALUES ('28','26','Clear/index','缓存管理','1','','2','2','1');
INSERT INTO wb_node VALUES ('56','26','Config/config','基本设置','1','','1','2','1');
INSERT INTO wb_node VALUES ('61','1','Device/index','设备列表','1','设备列表','1','2','1');
INSERT INTO wb_node VALUES ('62','1','Group/index','分组列表','1','分组列表','2','2','1');

DROP TABLE IF EXISTS wb_role;
CREATE TABLE `wb_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(150) NOT NULL,
  `sort` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

INSERT INTO wb_role VALUES ('1','超级管理员','0','1','','2');
INSERT INTO wb_role VALUES ('3','产品管理员','0','1','','0');
INSERT INTO wb_role VALUES ('20','普通用户','0','1','','1');

DROP TABLE IF EXISTS wb_role_user;
CREATE TABLE `wb_role_user` (
  `role_id` mediumint(9) unsigned NOT NULL,
  `user_id` char(32) NOT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO wb_role_user VALUES ('20','2');
INSERT INTO wb_role_user VALUES ('1','1');

DROP TABLE IF EXISTS wb_user;
CREATE TABLE `wb_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `bindusername` varchar(50) NOT NULL,
  `lastlogintime` int(11) unsigned NOT NULL DEFAULT '0',
  `lastloginip` varchar(40) NOT NULL,
  `loginnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `verify` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `isadministrator` tinyint(1) NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  `updatetime` int(11) unsigned NOT NULL,
  `sort` tinyint(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO wb_user VALUES ('1','admin','管理员','32ba35fd9f71d24fa525a504080163e2','','1512439200','127.0.0.1','73','7102','644828230@qq.com','备注信息','1','1222907803','1326266696','0','1','0','');
INSERT INTO wb_user VALUES ('2','scott','scott','7d9455ff3abf1b12958a34e30a699773','','1511507112','127.0.0.1','33','9410','644828230@qq.com','','0','1509960070','0','0','1','0','');

