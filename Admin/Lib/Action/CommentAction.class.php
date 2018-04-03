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
class CommentAction extends CommonAction{
	
    public function index() {
		$field="id,username,author,pid,nid,inputtime,email,url,isreply,ip,content,path,concat(path,'-',id) as bpath";
		//$where['isreply'] = array('neq',1);
		$where['nid'] = isset($_GET['nid'])?$_GET['nid']:array('gt',0);
		$arr = D('Comment')->gclist($field,$where);
		//dump($arr);exit;
		$commentlist = $arr['list'];
		
	    foreach ($commentlist as $k => $v) {	        
			$commentlist[$k]['title'] =M('News')->where(array('id' =>$v['nid']))->getField('title');
			$commentlist[$k]['nid'] =M('News')->where(array('id' =>$v['nid']))->getField('nid');
		} 
        
		$this->assign("page", $arr['page']);
        $this->assign('commentlist', $commentlist);
        $this->display();
     }
	
	 public function bindex(){ 
	    $field="id,username,author,pid,bid,inputtime,email,url,isreply,ip,content,path,concat(path,'-',id) as bpath";
		$where['isreply']=array('neq',1);
		$where['bid']=array('gt',0);
		$arr= D('Comment')->gclist($field,$where);
		$commentlist=$arr['list'];
		
	    foreach ($commentlist as $k => $v) {
			$commentlist[$k]['title'] =M('Blog')->where(array('id' =>$v['bid']))->getField('title');
			$commentlist[$k]['bid'] =M('News')->where(array('id' =>$v['nid']))->getField('bid');
		} 
        
		$this->assign("page", $arr['page']);
        $this->assign('commentlist', $commentlist);
        $this->display();
	 }
	 
	 public function reply(){   
		$Comment = D('Comment');
		$vo = $Comment->relation(true)->getById($_GET['id']);
		if($vo){
			if($vo['nid']){
                $nt = M('News')->where(array('id'=>$vo['nid']))->find();
				if($nt){ 
				$this->assign('t',$nt);
				}
				}
			if($vo['bid']){
                $bt = M('Blog')->where(array('id'=>$vo['bid']))->find();
				if($bt){
					 $this->assign('t',$bt);
				}
				}
		}
		//print_r($vo['Reply']);
		$this->assign('vo',$vo);
		$this->assign('Reply',$vo['Reply']);
		$this->display();
    }

	
	public function answer(){ 
		$Reply = D('Comment');
		if($vo=$Reply->create()){
			$result=$Reply->add();
			//dump($vo);
		}
		$this->message($result);
    }
	
	public function act(){
		$this->action();
	}
}
?>