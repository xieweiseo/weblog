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
class RoleAction extends CommonAction {
    
    public function index(){
	       $Role = M('Role');
	       import("ORG.Util.Page");
	       $count = $Role->where($where)->count(); //计算总数
	       $p = new Page($count, $pagesize);
	       //dump($count);exit;
	       $lists = $Role->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('sort asc,id desc')->select();
	       //$lists = ($recordnum = $this->recordnum($lists, $Modulename)) ? $recordnum : $lists;//附加字段
	       //dump($lists);exit;
	       $p->setConfig('header', '条');
	       $p->setConfig('prev', "<");
	       $p->setConfig('next', '>');
	       $p->setConfig('first', '<<');
	       $p->setConfig('last', '>>');
	       if(in_array($Modulename,array('News','Blog','Download','Picture','Resources'))){
	           $this->assign("catid", intval($_POST['catid']));
	           $this->assign("catlist", D('Columns')->Catlist($Modulename));
	       }
	       $this->assign("page", $p->show());
	       $this->assign('tpl', $tpl = "index");//模板标识，用于判断是什么类型的模板以加载相应的JS和CSS
	       $this->assign('lists', $lists);          
           $this->display();    	
     }   
	   
	 public function setrole(){
    	   if(!$_GET['user_id']) $this->error("该用户不存在或已被删除!");
    	   $user_id = $_GET['user_id'];
    	   $Role_user = M('Role_user');
    	   $condition['user_id'] = $user_id;
    	   $Role_userlist = $Role_user->where($condition)->select();
    	   if(!false==$Role_userlist){
        	   $role_ids=array();
        	   
        	   foreach ($Role_userlist as $k => $v) {
        	       
        			$role_ids[]=$v['role_id'];
        		  }		
    	   }
    		
    	   $Role = M('Role');
    	   $Rolelist = $Role->order('id desc')->select(); 	
    	   foreach ($Rolelist as $key => $value) {
    			$Rolelist[$key]['checked'] = in_array($value['id'],$role_ids) ? 1 :0;
    	   }	
    	   //print_r($Nodelist);
    	   
           $this->assign('rolelist', $Rolelist);
    	   $this->assign('user_id', $user_id);
    		
           $this->display();
		
	   }
	   
       public function saverole(){ 
              if(!$_POST['user_id']){
                  $this->error("该用户不存在或已被删除!");
              }
         
              $user_id = $_POST['user_id']; 
              $role_ids = $_POST['id']; 
               //dump($role_id);
               //dump($node_id);
              $Role_user= M('Role_user');
              $condition['user_id'] = $user_id;
              $delets=$Role_user->where($condition)->delete(); //$delets是返回被删除的记录数
                             
              foreach($role_ids as $v){
            		$data['user_id'] = $user_id;
            		$data['role_id'] = $v;
            		$result = $Role_user->add($data);
            	}
            	if(false !== $result){
            	    $this->success("操作成功！");
            	}
            	else{
            		$this->error("操作失败！");
            	}
       }
    
       public function add(){
            //创建数据对象
            $Role	 =	 D('Role');
            if(!$Role->create()) {
            	$this->error($Role->getError());
            }else{
            	// 写入帐号数据
            	if($result	 =	 $Role->add()) {
            		//$this->addRole($result);
            		$this->success('操作成功！',U('index'));
            	}else{
            		$this->error('操作失败！');
            	}
             }        
        }

        public function insert() {
        	$Role = M("Role");
        	$Rolelist = $Role->field('id,name')->select();
        	$this->assign("rolelist", $Rolelist);
        	$this->display();
        	
        }
        
        public function edit() {
                        
            $Role = M("Role");              
            $id = $_GET['id'];            
        	if(!$id) $this->error("编辑项不存在或已删除!");			   
        	 $Rolelist = $Role->field('id,name')->select();	  			               
             $vo = $Role->getById($id);              
        	 $this->assign("rolelist", $Rolelist);			   
        	 $this->assign('vo', $vo);		 
                           
             $this->display();              
        }
            
       public  function update() {
           $Role = D("Role");
            if (false === $Role->create()) {
                    $this->error($Role->getError());
              }                  
                    $list = $Role->save();
              if (false !== $list) {                    
                    $this->success('修改成功!',U('index'));
              } else {                      
                       $this->error('修改失败!');
              }
        }
                        
        public function del(){   
        	$condition['id'] = $_REQUEST['id'];
        	$Role = M('Role');
        	$role_user = M('role_user');
        	$role_id = $role_user->where('user_id !<>1 and user_id='.$_SESSION[C('USER_AUTH_KEY')])->getField('role_id');
        	if(empty($role_id)){
            	if($Role->where($condition)->delete()){
            		$this->success("删除成功！");
            	}else{
            	    $this->error("删除失败!");
            	}
        	}
        	else{
        	    $this->error('此角色下存在用户不能删除！');
        	}
        }
            
        public function act(){
            
        	$this->action();
        }
        
        public function ajax_sort(){
            $id = I('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = I('post.o', 0, 'intval');
            $result = M('Role')->data(array('sort' => $o))->where("id='{$id}'")->save();
            if($result!=false){
                die('1');
            }
            else{
                die('-1');
            }                       
        }
}