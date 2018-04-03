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
class NodeAction extends CommonAction {
    
	public function index(){
		$listsarr = $this->lists();
		$lists = $listsarr['lists'];
//		dump($listsarr);exit;
// 		foreach ($lists as $k => $v) {
// 			$lists[$k]['class']= M('Node')->where(array('id' =>$v['pid']))->getField('title');
// 		}	
		
		$this->assign('page', $listsarr['page']);
        $this->assign('Nodelist', $lists);
        $this->display();
	}
	   
	public function lists($where='',$pagesize=25,$order='id desc') {
		$Modulename = $this->getActionName();
		$M = M($Modulename);
		$modleid = $this->getmodleid($Modulename);//获取模型ID		
        import("ORG.Util.Page");
		$count = $M->where($where)->count(); //计算总数
		$p = new Page($count, $pagesize);
		//$lists = $M->where($where)->order('ord asc,id desc')->select();
		//dump($count);exit;
        $menu_list = $this->get_node(0);
		//dump($menu_list);exit;
		
		$p->setConfig('header', '条');
		$p->setConfig('prev', "<");
		$p->setConfig('next', '>');
		$p->setConfig('first', '<<');
		$p->setConfig('last', '>>');
		$this->assign('tpl', $tpl = "index");//模板标识，用于判断是什么类型的模板以加载相应的JS和CSS
		//$listsarr = array('lists'=> $menu_list,'page'=>$p->show());
		$listsarr = array('lists'=> $menu_list,'page'=>'共 '.$count.' 条');
		
		return $listsarr;
	}

	public function get_node($map ='1'){
	    $Node = M('Node');
	    if($map){
	       $map = 'status="'.$map.'"';  
	    }
	    else{
	        $map = '';
	    }
	    $lists = $Node->field('id,pid,name,title,status,sort')->where($map)->order('sort asc,id desc')->select();	    
	    $menu_list = array();
	    if($lists){
	        foreach ($lists as $k=>$v){
	            if($v['pid']==0){
	                $menu_list[$k] = $v;
	                for($i=0;$i<count($lists);$i++){
	                    if($v['id']==$lists[$i]['pid']){
	                        $menu_list[$k]['sub_node'][] = $lists[$i];
	                    }
	                }
	            }
	        }
	    }
	    
	    return $menu_list;
	}	
	
		
	public function insert($id = 0){
	    $id = intval($id);
	    if($id){
    	    $Node = M("Node");
    	    $node_info = $Node->getById($id);
    	    if(!$node_info){
    	        $this->error('没有此条信息');
    	    }
	    }	    
	    
	    //获取节点列表
		$select_node = $this->get_node();	
		
		//dump($select_node);exit;
		$select_tag = '<select name="pid">';
		$select_tag.= '<option value="0">顶级节点</option>';
		if($select_node){
    		foreach ($select_node as $k=>$val){	
    		    $selected = '';
    		    if($val['id']==$node_info['pid']){
    		        $selected ='selected="selected"';
    		    }
    		    $select_tag.= '<option value="'.$val['id'].'"'.$selected.'>'.$val[title].'</option>';
    		   
        		foreach ($val['sub_node'] as $vv){
       		    
        		     $select_tag.= '<option value="'.$vv['id'].'">|__'.$vv['title'].'</option>';    		
        		}
    		}
		}
		$select_tag.= '</select>';
		
		//dump($node_info);exit;		

	    $this->assign('node_info',$node_info);
	    $this->assign('select',$select_tag);
		$this->display();
	}
	
	public function add(){
	    $id = I('post.noid');
	    
        $data['title'] = I('post.title','','trim');
        $data['name'] = I('post.name','','trim');
        $data['sort'] = I('post.sort',0,'intval');
        $data['status'] = I('post.status',0,'intval');
        $data['pid'] = I('post.pid',0,'intval');
	    
	    $D = D('Node');
	    
	    if(empty($data['title'])){
	        $this->error('应用名必填');
	    }
	    
	    //判断是否选择父节点
	    $fpid = $D->where('id='.$data['pid'])->getField('pid');
	    if($fpid!=0){
	        $this->error('上级节点选择错误！');
	    }
	    	    
	    //dump($vo);exit;
	    $data['level'] = empty($data['pid'])?'1':'2';
        $data['remark'] = I('post.remark','',array('strip_tags','trim'));
	
	    //dump($data);exit;
	    //更新
	    if($id){
	        //dump($data);
	        //dump($_POST);exit;
	        $list = $D->data($data)->where(array('id'=>$id))->save();
	        if($list!==false){
	            $vo['message'] = '更新成功!';
	        }
	        else{
	            $vo['message'] = '更新失败!';
	        }
	    }
	    else{
	        //新增
	    	$list = $D->data($data)->add();
	        if($list!==false){
	            $vo['message'] = '新增成功!';
	        }
	        else{
	            $vo['message'] = '新增失败!';
	        }
	    }

	   if ($list!==false) {
	        //数据保存触发器
	        /*if(in_array($module, array('News','Blog','Resources'))){
	         if (method_exists($this, '_trigger')) {
	         $this->_trigger($vo, $list); //$list成功添加数据后，返回的是该记录的ID
	         }
	         }*/
	        $this->success($vo['message'], "__GROUP__/" . MODULE_NAME . "/index/");
	    }else {
	        $this->error($vo['message']);
	    }
	}	
	
	public function del($id){
        //dump(I('id'));exit;
	    $id = I('id','','intval');
	    
	    //dump($id);exit;
	    if(!$id) {
	        $this->error("删除项不存在或已删除!");
	    }
	    
	    else if(!is_array($id)){
	        $ids['0'] = $id;
	    }
	    else{
	        $ids = $id;
	    }
	    
	    $Node= D('Node');
	    $count = 0;
	    foreach ($ids as $val){
	       $count+= intval($Node->where(array('pid'=>$val))->count());
	    }
	    //dump($node_info);exit;
	    if($count>0){
	        //dump($count);exit;
	        $this->error('所删节点下有子节点不能删除!');
	    }
	    else{
	        $Node->where('id in ('.implode(',',$ids).')')->delete();
	        //dump($count);exit;
	        $this->success("删除成功");	        
	    }
	}
		
	public function edit($id) {
	   $id = intval($id);
	   if(!$id) {
	       $this->error("编辑项不存在或已删除!");
	   }
	   //dump($id);exit;
       $Node = M("Node"); 
       $vo = $Node->getById($id);
       
       dump($Node->field('id,title')->select());
       exit;
		 		                 
			  $this->assign('vo', $Node->getById($_GET['id'])); 
			  $this->assign("nodelist", $Node->field('id,title')->select());   
              $this->display();     
                     	  
    }                
 
    public function update() {				
		$this->getupdate();
    }
    
	public function act(){
		$this->action();
	}
	
	public function setaccess(){
	    if(!$_GET['role_id']) $this->error("该角色不存在或已被删除!");
	    $role_id =$_GET['role_id'];
		$node_ids = M('Access')->where(array('role_id'=>$role_id))->getField('node_id',true);
		$Nodelist = M('Node')->order('sort ASC')->select(); 
		
		//dump($role_id);
		//dump($node_ids);exit;
	    foreach ($Nodelist as $key => $value) {
			 $Nodelist[$key]['checked']=in_array($value['id'],$node_ids) ? 1 :0;
	    }
	   
	    //dump($Nodelist);exit;
	    if($Nodelist){
	       $nodes = $Nodelist;
	       foreach ($nodes as $k=>$v){
	           if($v['pid']==0){
	               $node_list[$k] = $v;
	               for($i=0;$i<count($nodes);$i++){
	                   if($v['id']==$nodes[$i]['pid']){
	                       $node_list[$k]['sub_node'][] = $nodes[$i];
	                   }
	               }
	           }
	       }
	   }	   
	   			
	   //dump($node_list);exit;	
       $this->assign('Nodelist', $node_list);
	   $this->assign('role_id', $role_id);
       $this->display();
    }
    
	public function saveaccess(){ 
	      if(!$_POST['role_id']) $this->error("该角色不存在或已被删除gggg!");
	      $role_id = $_POST['role_id']; 
		  $node_ids = $_POST['id']; 
		  //dump($role_id);
		  //dump($node_ids);exit;

		   $Access=M('Access');
		  // $condition['role_id']=$role_id;
		   $delets=$Access->where(array('role_id'=>$role_id))->delete(); //$delets是返回被删除的记录数
	
	      foreach($node_ids as $v){
				$data['role_id'] = $role_id;
				$data['node_id'] = $v;
				$vo = M('Node')->getById($v);
				$data['pid']=$vo['pid'];
				$data['level']=$vo['level'];
				$result[]=$Access->add($data);
			}
			
			$this->message($result);			
    }
    
    public function ajax_sort(){
        $id = I('post.id', 0, 'intval');
        if (!$id) {
            die('0');
        }
        $o = I('post.o', 0, 'intval');
        $result = M('Node')->data(array('sort' => $o))->where("id='{$id}'")->save();
        if($result!=false){
            die('1');
        }
        else{
            die('-1');
        }
    }    
    
}
?>