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
class UserAction extends CommonAction {
	
	public function index() {
		$user = M('User');
		$prefix = C('DB_PREFIX');
		if (isset ($_POST['catid']) && !empty ($_POST['catid'])) {
			$where['catid'] = intval($_POST['catid']);						
		} else {
            $where='';
		}
				
        import("ORG.Util.Page");
        $db = Db::getInstance(C('RBAC_DB_DSN'));
        $pagesize = 12;
        $user_sql_count = "SELECT count(*) as count from {$prefix}user a left join {$prefix}role_user b ON a.id=b.user_id left join {$prefix}role c ON c.id=b.role_id ".$where." ORDER BY a.id ASC";
        $user_count = $db->query($user_sql_count);
		$count = $user_count[0]['count']; //计算总数
		$p = new Page($count, $pagesize);
		$user_sql_info = "SELECT a.id,a.username,a.lastlogintime,a.lastloginip,a.status,c.name from {$prefix}user a left join {$prefix}role_user b ON a.id=b.user_id left join {$prefix}role c ON c.id=b.role_id ".$where." ORDER BY a.id ASC limit ".$p->firstRow . ',' . $p->listRows;
		$lists = $db->query($user_sql_info);		
		$p->setConfig('header', '条');
		$p->setConfig('prev', "<");
		$p->setConfig('next', '>');
		$p->setConfig('first', '<<');
		$p->setConfig('last', '>>');
		
		$this->assign("page", $p->show());
		$this->assign('lists', $lists);
        $this->display();
    }
    
	public function act(){
	    
		$this->action('User');
		
	}
	
	public function insert(){	    
		$a=$_SESSION['verify'];		
		$this->assign("a", $a);
		$this->display();
	}
	
	public function edit($id = 0, $role_id = 0){
	    $id =  intval($id);
	    $role_id = intval($role_id);
	    if($id){
    	    $User = D('User');
    	    $user_info = $User->where("id=".$id)->find();
    	    //dump($user_info);exit;
    	    if(IS_POST){
    	        $data['username'] = I('post.username','',array('strip_tags','trim'));
    	        $data['nickname'] = I('post.nickname','',array('strip_tags','trim'));
    	        $data['status'] = I('post.status',0,array('intval'));
    	        $data['email'] =  I('post.email','',array('strip_tags','trim'));
    	        if($_REQUEST['password']){
    	          $data['password'] = pwdHash(I('post.password','',array('strip_tags','trim')));
    	        }
    	        
    	        $reslt_user = $User->where("id=".$id)->data($data)->save(); 
    	        $role_user = M('Role_user');
    	        
    	        //角色是否存在
    	        $count = $role_user->where("user_id=".$id)->count();
    	        if(!empty($count)){
    	           //修改角色
    	           $reslt_role_user = $role_user->where("user_id=".$id)->data(array('role_id'=>$role_id))->save();
    	        }
    	        else{
    	           //添加角色
    	           $reslt_role_user = $role_user->data(array('role_id'=>$role_id,'user_id'=>$id))->add();
    	        }

    	        if(false!==$reslt_user && false!==$reslt_role_user){
    	            $this->success('修改成功！');exit;
    	        }
    	        else{
    	            $this->error('抱歉，修改失败！');exit;
    	        }	   	        
    	    } 
	    }
	    else{
	        $this->error('参数错误！');
	    }
	    
	    $role_id = M('role_user')->where('user_id='.$user_info['id'])->getField('role_id');
	    $this->assign("select",$this->get_role_select($role_id));
	    $this->assign("vo",$user_info);
	    
	    $this->display();
	}	
	
	// 插入数据
	public function add() {	    
		//$this->addata('User');exit;
        $data['username'] = I('post.username','','strip_tags','trim');
        $data['nickname'] = I('post.nickname','','strip_tags','trim');
        $data['email'] =  I('post.email','','strip_tags','trim');
        $data['password'] =  pwdHash(I('post.password','','strip_tags','trim'));
        $data['status'] = 1;
        $data['createtime'] = time();
        
        if(empty($data['username'])){
            
        }
        elseif(empty($data['password'])){
            
        }
        elseif($data['username']=='admin'){
            $this->error('管理员账号已存在');
        }
        
        $user = M('User');
        $count = $user->where("username='".$data['username']."'")->count();
        
        if($count){
            $this->error('用户名已占用');
        }
        
        $reslt = $user->data($data)->add();
        
        if($reslt!=false){
            $this->success('添加成功');
        }
        else{
            $this->error('添加失败');
        }
	}  
	
	public function password(){
	    
	    $this->display();
	}
	
	// 更换密码
	public function changePwd(){
	    $verify = I('post.verify','',array('strip_tags','trim'));
	    $oldpassword = I('post.oldpassword','',array('strip_tags','trim'));
	    $password = I('post.password','',array('strip_tags','trim'));
	    
	    if($_SESSION['verify'] != md5($verify)) {
	        $this->error('验证码错误！');
	    }
	    
	    $map = array();
	    $map['password']= pwdHash($oldpassword);
	    
	    if(isset($_SESSION[C('USER_AUTH_KEY')])) {
	        $map['id']	=  $_SESSION[C('USER_AUTH_KEY')];
	    }
	    
	    //检查用户
	    $User =  M("User");
	    //dump($map);exit;
	    if(!$User->where($map)->field('id')->find()) {
	        $this->error('旧密码不符！');
	    }else {
	        $User->password	=	pwdHash($password);
	        if($User->save()!==false){
	            $this->success('密码修改成功！');
	        }
	        else{
	            $this->error('密码修改失败！');
	        }
	    }
	}
	
	public function get_role_select($id = 0){
	    $prefix = C('DB_PREFIX');
	    $db = Db::getInstance(C('RBAC_DB_DSN'));
	    $role_sql = "SELECT a.id, a.name from {$prefix}role as a left join (select role_id,user_id from {$prefix}role_user group by role_id) as b ON a.id=b.role_id order by a.id ASC";
	    //dump($role_sql);exit;
	    $role_info = $db->query($role_sql);
	    $username = I('username','','strip_tags,trim');
	    
	    if($username=='admin'){
	        $disabled = 'disabled="disabled"';
	    }
	    
	    //dump($role_info);exit;
	    
	    $select = '<select name="role_id" id="role_id" '.$disabled.'>';
	    $select .= '<option value="0">--选择角色--</option>';
	    if($role_info){	        
	        foreach ($role_info as $v){
	            if($id==$v['id']){	                
	                $selected = 'selected ="selected"';
	            }
	            else{
	                $selected = '';
	            }
	            $select .='<option value="'.$v[id].'" '.$selected.'>'.$v['name'].'</option>';
	        }
	    }
	
	    $select .='</select>';

	    return $select;
	}	
}
?>