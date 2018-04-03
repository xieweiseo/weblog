<?php
// +----------------------------------------------------------------------
// | WBlog
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://www.w3note.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 网菠萝果
// +----------------------------------------------------------------------
// $Id$
class ConfigAction extends CommonAction {
	function config(){
		//echo CONF_PATH;
		$this->display();
	}
	
	function blogconfig(){
		$this->display();
	}
	
	function server(){
		$this->display();
	}
	
	function save(){
		$data = $_POST;
		$file = $data['file'];
		$file_db = $data['file_db'];
		$db['db_name'] = $data['db_name'];
		$db['db_host'] = $data['db_host'];
		$db['db_user'] = $data['db_user'];
		$db['db_pwd'] = $data['db_pwd'];
		
		unset($data['file']);
		unset($data['__hash__']);
		unset($data['db_name']);
		unset($data['db_host']);
		unset($data['db_user']);
		unset($data['db_pwd']);
		//dump($data);exit;
		if($file=="siteconfig.inc.php"){	
			if($data["sitename"]=='')$data["sitename"]='WBlog博管系统' ;
			if($data["domainname"]=='')$data["domainname"]='http://www.w3note.com' ;
			if($data["rooturl"]=='')$data["cssurl"]='' ;
			if($data["cssurl"]=='')$data["cssurl"]='Public/wblog/css/' ;
			if($data["jsurl"]=='')$data["jsurl"]='Public/Js/' ;
			if($data["imgurl"]=='')$data["imgurl"]='Public/images/' ;
			if($data["upurl"]=='')$data["upurl"]='Public/Uploads/' ;
			if($data["metakeys"]=='')$data["metakeys"]='网志博客' ;
			if($data["metadesc"]=='')$data["metadesc"]='本系统采用目前流行的开源框架THINKPHP开发' ;
			if($data["wblogname"]=='')$data["sitename"]='网志博管系统' ;
			if($data["wblogkeys"]=='')$data["metakeys"]='网志博客' ;
			if($data["wblogdesc"]=='')$data["metadesc"]='本系统采用目前流行的开源框架THINKPHP开发' ;
			if($data["pagesize"]=='')$data["pagesize"]='15' ;
			if($data["email"]=='')$data["email"]='644828230@qq.com' ;
			if($data["contact"]=='')$data["contact"]='w3note' ;
			if($data["company"]=='')$data["company"]='w3note' ;
			if($data["phone"]=='')$data["phone"]='0779-******' ;
			if($data["address"]=='')$data["address"]='广西北海' ;		
		}
		if($file_db=="db_config.inc.php"){
		    if($db['db_type']=='')$db['db_type'] = 'mysql';
		    if($db['db_host']=='')$db['db_host'] = '127.0.0.1';
		    if($db['db_user']=='')$db['db_user'] = 'root';
		    if($db['db_pwd']=='')$db['db_pwd'] = 'root';
		    if($db['db_port']=='')$db['db_port'] = '3306';
		    if($db['db_name']=='')$db['db_name'] = 'weblog';
		    if($db['db_prefix']=='')$db['db_prefix'] = 'wb_';
		    if($db['rbac_role_table']=='')$db['rbac_role_table'] = 'wb_role';
		    if($db['rbac_user_table']=='')$db['rbac_user_table'] = 'wb_role_user';
		    if($db['rbac_access_table']=='')$db['rbac_access_table'] = 'wb_access';
		    if($db['rbac_node_table']=='')$db['rbac_node_table'] = 'wb_node';
		    if($db['keycode']=='')$db['keycode'] = 'x53pc9';		  		    
		}
		
		//dump($db);exit;
		$content = "<?php\r\n//w3note.com 网站配置文件\r\nif (!defined('THINK_PATH')) exit();\r\nreturn array(\r\n";
        //获取数组
		foreach ($data as $key=>$value){
			$key=strtoupper($key);
			if(strtolower($value)=="true" || strtolower($value)=="false" || is_numeric($value))
				$content .= "\t'$key'=>$value, \r\n";
			else
				$content .= "\t'$key'=>'$value',\r\n";
				
			C($key,$value);
		}
		$content .= ");\r\n?>";
		
		//数据库配置项
		$db_content = "<?php\r\nif (!defined('W3CORE_PATH')) exit();\r\nreturn array(\r\n";
		foreach ($db as $key=>$value){
		    $key=strtoupper($key);
		    if(strtolower($value)=="true" || strtolower($value)=="false" || is_numeric($value))
		        $db_content .= "\t'$key'=>$value, \r\n";
		    else
		        $db_content .= "\t'$key'=>'$value',\r\n";
		    
		    C($key,$value);		    
		}
		
		$db_content .= ");\r\n?>";
		
		//dump($db_content);exit;
      	$r = @chmod($file,0777);
      	$r_db = @chmod($file_db,0777);      
		$hand= file_put_contents(CONF_PATH.'siteconfig.inc.php',$content);
		$hand_db = file_put_contents(CONF_PATH.'db_config.inc.php',$db_content);
		if (!$hand && !$hand_db) {
			$this->error($file.'配置文件写入失败！');
		}else{
		   $cachefile = RUNTIME_PATH.'~runtime.php';
		   if(is_file($cachefile)) $result = unlink($cachefile);
       	   if($result){
			   $this->success('配置文件保存成功!更新成功!');
		   }else{
			   $this->success('配置文件保存成功!');
		   }
		}
	}
}
?>