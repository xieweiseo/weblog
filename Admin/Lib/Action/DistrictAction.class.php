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
class DistrictAction extends CommonAction {

    public function index() {
        $district = M('District');
        $where = '1=1 ';                       
     
        import("ORG.Util.Page");        
        $count = $district->where($where)->count(); //计算总数
        $pagesize = 12;
        $p = new Page($count, $pagesize);
        $list = $district->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id Asc')->select(); 
                
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
            $district = M('District');
    	    $district_info = $district->where("id=".$id)->find();
    	    $this->assign('vo',$district_info);
	    }
	    else{
	        $this->error('参数错误！');
	    }
	    
		$this->display('insert'); 
    }    
    
    public function insert(){
        if(IS_POST){
            $id = I('post.id',0,'trim');
            $data['name'] = I('post.name','','strip_tags,trim');
            $data['desc'] = (I('post.desc','','strip_tags,trim'));
            if(empty($data['name'])){
                $this->error('地区名为空！');
            }        
            
            $district = M('District');
            if(empty($id)){
                $result = $district->data($data)->add();
                if($result!=false){
                    $this->success('新增成功！',U('index'));
                }
                else{
                    $this->error('新增失败！');
                }
            }
            else{
                //dump($data);exit;
                $result = $district->data($data)->where("id=".$id)->save();
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
        $district = M('District');
        if($id){
            $result = $district->where("id in(".$id.")")->delete();
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
