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
if (empty($_SESSION[C('USER_AUTH_KEY')])) exit();
class ClearAction extends Action {
    
	 public function index(){
         
          $this->display();

	 }
	 
	 public function del(){   
		$type=trim($_GET['type']);
		if(empty($type)) $this->error('请选择缓存类型！');
		
            switch($type) {
            case 1:// 全部清空             
				 $path   =   RUNTIME_PATH;
                break;
            case 2:// 文件缓存目录
                $path   =   RUNTIME_PATH.'Temp';
                break;
			case 3://  前后台数据缓存目录
                $path   =   RUNTIME_PATH.'Data'.DIRECTORY_SEPARATOR.'_fields';
				 break;
		    case 7://  后台数据缓存目录
		        $path   =   RUNTIME_PATH.'Data'.DIRECTORY_SEPARATOR;
		        break;				 
            case 4:// 全部模板缓存
                 $path  =  RUNTIME_PATH.'Cache';
                break;
            case 5:// 前台模板缓存             
				 $path   =  C('CACHE_HOME');
                break;
            case 6:// 后台模板缓存   
                $path   =  C('CACHE_ADMIN');
                break;
            }
       
         import("@.ORG.Dir");
		
	    if(!Dir::isEmpty($path)){
		 Dir::del($path);
		 //echo $path."aa";
		 $this->success('更新成功');
		 }else{
			 //echo $path."dd";
			 //Dir::del($path);
			 $this->error('已清空！');
		}
    }
    
    //清除缓存
    public function clear()
    {
        $cache = Cache::getInstance();
        $cache->clear();
        $this->rmdirr(RUNTIME_PATH);
        $this->success('系统缓存清除成功！', U('Public/main'));
    }
    
    //递归删除缓存信息    
    public function rmdirr($dirname)
    {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        $dir = dir($dirname);
        if ($dir) {
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
    }    
}

?>