<?php
use Think\Verify;
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
class PublicAction extends Action {
    
    function _initialize() {
        header("Content-type:text/html;charset=utf-8");
    } 
    
	// 检查用户是否登录
	protected function checkUser() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			 $this->error('没有登录',__GROUP__.'/Public/login');
		}
	}
    public function index(){
		//如果通过认证跳转到首页
		 redirect(__GROUP__);
	}
	
 
	// 登录检测
	public function checkLogin() {
	    
	    //dump($_POST);exit;
	    $username = I('post.username','',array('strip_tags','trim'));
	    $password = I('post.password','',array('strip_tags','trim'));
	    $code = I('post.code','',array('strip_tags','trim'));
	    
	    if(empty($username)){
	        $this->error('用户名必须！');
	    }
	    else if(empty($password)){
	        $this->error('密码必须！');
	    }
// 	    else if(empty($code)){
// 	        $this->error('验证码必须!');
// 	    }
	    	    
        //生成认证条件
        $map = array();
		$map['username']	= $username;
        $map["status"]	=	array('gt',0);//大于
               
		if(!$this->check_verify($code,'admin')) {
			$this->error('验证码错误！');
		}

		$authInfo = M('user')->where($map)->find();        
        		
		//dump($authInfo);
		
        if(false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }elseif($authInfo['status']==0){
			$this->error('帐号已禁用！');
		
		}else {
			$password = pwdHash($password);
            if($authInfo['password'] != $password) {
            	$this->error('密码错误！');
            }
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];//赋值给用户认证SESSION标记
            $_SESSION['email']	=	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['lastlogintime'];
			$_SESSION['loginnum']	=	$authInfo['loginnum'];
			$_SESSION['lastloginip']	=	$authInfo['lastloginip'];
			
            if($authInfo['isadministrator']==1) {//判断是否管理员
            	$_SESSION['administrator']	= true;
            }
            
            //dump($_SESSION[C('USER_AUTH_KEY')]);exit;
            //保存登录信息(相当于更新信息）
			$User	=	M('User');
			$ip		=	get_client_ip();
			$time	=	time();
            $data = array();
			$data['id']	=	$authInfo['id'];
			$data['lastlogintime']	=	$time;
			$data['loginnum']	=	array('exp','loginnum+1');//??
			$data['lastloginip']	=	$ip;
			$data['code']	=	$code;
			$User->save($data);

			// 缓存访问权限
            //RBAC::saveAccessList();
			//$this->success('登录成功！',__GROUP__.'/Index/index');
			addlog('登录成功。');
			$this->success('登录成功', U('Index/index'));
		}
	}
	
	public function main() {
	    //dump($_SESSION[C('USER_AUTH_KEY')]);    
	    $this->checkUser();
	    
	    $model = new Model();
	    $mysql = $model->query("select VERSION() as mysql");	    
	    
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            
            'MySQL版本'=>$mysql[0]['mysql'],
			'Host'=>gethostbyname($_SERVER['SERVER_NAME']),
            'Version'=>THINK_VERSION
        );
        

        
        import("ORG.Util.Page");
        $log = M('log');
        $where = '1=1 ';
        $count = $log->where($where)->count(); //计算总数
        $pagesize = 8;
        $p = new Page($count, $pagesize);
        $list = $log->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        //dump($list);exit;
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');       
        $this->assign("page", $p->show());
        $this->assign('list',$list);
        $this->assign('info',$info);
        $this->display();
    }
    
	public function main_top() {
	      $this->checkUser();
		  $this->display();
    }
    
	public function header() {	      
	      $this->checkUser();
		  $this->display();
    }
    
	public function top(){
	    $this->checkUser();
    	if($_SESSION[C('USER_AUTH_KEY')]){
    		$User	= M("User");
    		$condition['id'] = $_SESSION[C('USER_AUTH_KEY')];
    		$User = $User->where($condition)->field( 'username' )->find();
    		
    		$this->assign("id",$_SESSION['id']);
    		$this->assign("username",$User['username']);
    	}
    	
    	$this->display();
    }
    
	public function foot() {
	    $this->checkUser();
		$this->display();
	}
	
	public function menu() {
	    $this->checkUser();
	    $authId = $_SESSION[C('USER_AUTH_KEY')];
	    $db = Db::getInstance(C('RBAC_DB_DSN'));
	    $sql = "select access.role_id,access.node_id from wb_role_user as role_user,wb_role as role,wb_access as access ";
	    $sql.= "where role_user.user_id='{$authId}' and role_user.role_id=role.id and access.role_id=role.id";
	    $apps =   $db->query($sql);
	    
	    //dump($apps);exit;
	    $nodes_list =  array();
	    if($apps){
	        foreach ($apps as $v){
	            $nodes[] = $v['node_id'];
	        }
	    }
	    else{
	        //初始时没有添加任何角色
	        $nodes[0] = '26';
	        $nodes[1] = '27';
	    }
	    
	    //dump($nodes);exit;
	    $nodes_list = implode(',', $nodes);
	    //dump($nodes_list);exit;
	    $Node = M('Node');
	    $where = 'status=1 ';	
	    if($nodes_list){
	       $where.= 'and id in ('.$nodes_list.')';
	    }
	    //判断是否为超级管理员
	    $sql = "select a.* from wb_role as a join wb_role_user as b on a.id=b.role_id and b.user_id='".$authId."'";
	    $role_info = $db->query($sql);
	    
	    //dump($role_info[0]['name']);exit;
	    if($role_info[0]['name']==='超级管理员'){
	        $where = 'status=1';
	    }
	
	    //dump($where);exit;
	    $menu = $Node->field('*')->where($where)->order('sort,id ASC')->select();	
	    //dump($menu);exit;
	    if($menu){
    	    foreach ($menu as $k=>$v){
    	        if($v['pid']==0){
    	            $menu_list[$k] = $v;
    	            for($i=0;$i<count($menu);$i++){
    	                //echo 'id>'.$v['id'].'--'.'pid>'.$menu[$i]['pid']."<br/>";
    	                if($v['id']==$menu[$i]['pid']){
    	                    $menu_list[$k]['sub_node'][] = $menu[$i];
    	                }
    	            }
    	        }
    	    }
	    }
	    
	    //dump($menu_list);exit;
	    
		//$var = $_GET['nav'];
		//$this->assign('var',MODULE_NAME);
		$this->assign("count",count($menu_list));
		$this->assign("menu_list", $menu_list);
		$this->display();
	}
	
	// 用户登录页面
	public function login() {
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			
			 $this->redirect('Index/index');
		}else{		
			$this->display();
					
		}
	}

	public function logout(){
	    if(isset($_SESSION[C('USER_AUTH_KEY')])) {
	        unset($_SESSION[C('USER_AUTH_KEY')]);
	        unset($_SESSION);
	        session_destroy();
	        $url = U("public/login");
	        //addlog('注销成功。');
	        header("Location: {$url}");
	        exit(0);
// 	        $this->assign("jumpUrl",__URL__.'/login/');
// 	        $this->success('登出成功！');
	    }else {
	        $this->error('已经登出！');
	    }
	}
	
	public function verify(){
	    import('ORG.Util.Image');
	    Image::buildImageVerify(4,1,gif,48,22,'verify');
	}

	function check_verify($code, $id = '', $reset = true){
	    import('ORG.Verify');
	    $verify = new Verify(array('reset'=>$reset));
	    return $verify->check($code, $id);
	}	
	
	public function code(){
	    import('ORG.Verify');
	    $verify = new Verify();
	    $verify->useCurve = true;
	    $verify->useNoise = false;
	    $verify->bg = array(255, 255, 255);
	    $verify->length = 4;
	    
	    if (I('get.code_len')) $verify->length = intval(I('get.code_len'));
	    if ($verify->length > 8 || $verify->length < 2) $verify->length = 4;
	   
	    //if (I('get.font_size')) $verify->fontSize = intval(I('get.font_size'));
	    $verify->fontSize = I('get.font_size')?intval(I('get.font_size')):17;
	    if (I('get.width')) $verify->imageW = intval(I('get.width'));
	    if ($verify->imageW <= 0) $verify->imageW = 130;
	    
	    if (I('get.height')) $verify->imageH = intval(I('get.height'));
	    if ($verify->imageH <= 0) $verify->imageH = 50;
	    
	    $verify->entry('admin');	    
	}
	
}
?>