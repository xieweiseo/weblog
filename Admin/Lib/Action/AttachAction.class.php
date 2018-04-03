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
class AttachAction extends CommonAction {
    // 首页
    public function index() {
        $Attach = M("Attach");
        import("ORG.Util.Page");                    
		if(isset($_GET['id'])&&isset($_GET['module'])){			   
			   $recordId=intval($_GET['id']);
			   $module=$_GET['module'];
			   $condition['recordId'] = $recordId;
			   $condition['module'] = $module;
		     }else if(isset($_REQUEST['module']))
		     {//根据模型管理文件
			     $module=$_REQUEST['module'];
			     $condition['module'] = $module;
			 }else{//默认情况下显示图片附件
			   $condition['module'] ='Picture';
			   $module='Picture';
		     }
		 import("ORG.Util.Page");    
		 $count = $Attach->where($condition)->count();    //计算总数
         $p = new Page($count, 12);
		 $Attachlist = $Attach->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select(); 
		 foreach ($Attachlist as $k => $v) {
			 $recordId=$recordId ? $recordId: $Attach->where(array('id'=>$v['id']))->getField('recordId');			
			 $Attachlist[$k]['title']=M($module)->where(array('id'=>$recordId))->getField('title');
				  
		}
		
		$p->setConfig('header', '条数据');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
		$this->assign('Modelist', $Attach->field('module')->select());
		$this->assign('page', $p->show());
		$this->assign('tpl', $tpl="index");
		$this->assign('modules', $module);
		$this->assign('recordId', $recordId=$recordId?$recordId:0);
        $this->assign('Attachlist', $Attachlist);
        $this->display();
     }
    
	 public function act(){		
		$ids = $_REQUEST['id'];		
		$getid = is_array($ids) ? $ids : explode(' ',$ids);
		if(empty($ids)){			
			$this->error('请勾选记录');		
		}
		$Attach = D('Attach');	//在同一模型中操作	
		$action = trim($_GET['action']);
		if($action == 'delete'){
			foreach($getid as $v){				
				$vo = $Attach->where('id='.$v)->find();           
                $savename = $vo['savename'];
				$result = $Attach->where('id='.$v)->delete();
			   $file="Public/Uploads/File/".$savename;
			   if(is_file($file)){
				$result2 = unlink($file);
				if(!$result2) $this->error("删除附件时出错!");
				}
			}
			
			$this->message($result);			
		}
	 }
	
     public function upload() {
	    $module=$_POST['module'];
		$recordId=$_POST['recordId'];
        if (!empty($_FILES)) {//如果有文件上传
            // 上传附件并保存信息到数据库
            //$this->_upload(MODULE_NAME);
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();
            //设置上传文件大小
           $upload->maxSize = 32922000;
           //设置上传文件类型
          $upload->allowExts = array('rar', 'zip', 'doc', 'swf', 'gif','jpg', 'ppt');
          $upload->savePath = 'Public/Uploads/File/';
		  $upload->uploadReplace = ture;//存在同名文件是否是覆盖
		  if (isset($_POST['_uploadSaveRule'])) {
            //设置附件命名规则
            $upload->saveRule = $_POST['_uploadSaveRule'];
        } else {
            $upload->saveRule = 'uniqid';
        }
			
			if (!$upload->upload()) {
            
                //捕获上传异常
                $this->error($upload->getErrorMsg());
           
        } else {
            
                //取得成功上传的文件信息
                $uploadList = $upload->getUploadFileInfo();
				//print_r($uploadList);
                $remark = $_POST['remark'];
                //保存附件信息到数据库
                $Attach = M('Attach');
                //启动事务
                //$Attach->startTrans();
                foreach ($uploadList as $key => $file) {
                    //记录模块信息
                    $file['module'] = $module ? $module : 0;
                    $file['recordId'] = $recordId ? $recordId : 0;
                    $file['updateTime'] =time();
                   $list= $Attach->add($file);

				}
				
          if ($list !== false) {
                $this->success('数据添加成功！');
            } else {
                $this->error('数据写入错误');
            }
			
		}														
      }
    }
  
    public function add(){
		$module=$_GET['module'] ? $_GET['module'] : 0;
		$recordId=$_GET['id'] ? $_GET['id'] : 0;
		$this->assign('module',$module);
		$this->assign('recordId',$recordId);
		$this->display();
	   }
   }
?>