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
class GroupAction extends CommonAction {

    public function index() {
        $device_group = M('device_group');
        $where = '1=1 ';                 
        $broadband_id = isset($_POST['broadband_id']) ? I('broadband_id','','trim') : '';
        $olt_office = isset($_POST['olt_office']) ? I('olt_office','','trim') : '';
        $mac = isset($_POST['mac']) ? I('mac','','trim') : '';
        if ($broadband_id) {
            $where .= "and broadband_id='".$broadband_id."' ";
        }
        if($olt_office){
            $where .= "and olt_office='".$olt_office."' ";
        }
        if($mac){
            $where .= "and mac='".$mac."' ";
        }        
        //dump($where);
        import("ORG.Util.Page"); 
        
        $count = $device_group->where($where)->count(); //计算总数
        $pagesize = 12;
        $p = new Page($count, $pagesize);
        $list = $device_group->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select(); 
                
        //dump($list);exit;
        
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');

        $this->assign("page", $p->show());
        $this->assign('list', $list);        
                
        $this->display();
    }
		
    public function add(){
        
		$this->display('insert');
	}
	
	public function edit($id = 0) {
	    $id = intval($id);
	    if($id){
            $device_group = M('device_group');
    	    $device_group_info = $device_group->where("id=".$id)->find();
    	    $this->assign('vo',$device_group_info);
	    }
	    else{
	        $this->error('参数错误！');
	    }
	    
		$this->display('insert'); 
    }    
    
    public function insert(){
        if(IS_POST){
            $id = I('post.id',0,'trim');            
            $data['group_id'] = I('post.group_id',0,'strip_tags,trim');
            $data['group_name'] = I('post.group_name','','strip_tags,trim');
            $data['desc'] = (I('post.desc','','strip_tags,trim'));
            if(empty($data['group_id'])){
                $this->error('账号为空！');
            }
            else if(empty($data['group_name'])){
                $this->error('广电号为空！');
            }         
            
            $device_group = M('device_group');
            if(empty($id)){
                $result = $device_group->data($data)->add();
                if($result!=false){
                    $this->success('新增成功！',U('index'));
                }
                else{
                    $this->error('新增失败！');
                }
            }
            else{
                //dump($data);exit;
                $result = $device_group->data($data)->where("id=".$id)->save();
                if($result!=false){
                    $this->success('修改成功！',U('index'));
                }
                else{
                    $this->error('修改失败！');
                }                   
            }    
        }               
    }
    
    public function del($id = 0){
        //$this->error('此功能尚未开放...');
        $getid = I('id',0,'strip_tags,trim');
        $getids = implode(',',$getid);
        $id = is_array($getid) ? $getids : $getid;        
        $device_group = M('device_group');
        if($id){
            $result = $device_group->where("id in(".$id.")")->delete();
            if($result!=false){
                $this->success('删除成功！');
            }
            else{
                $this->error('删除失败！');
            }
        }
        else{
            $this->error('请勾选要删除项！');
        }                
    }
    
}
