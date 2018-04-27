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
class PanelAction extends CommonAction{

    public function index() {
    	$hotel_id = I('hotel_id','','trim');
    	$hotel_name = M('Hotel')->where('id='.$hotel_id.'and status=1')->getField('hotel_name');
    	$lists = M('panel')->field('wb_panel.*,wb_hotel.hotel_name')->join('LEFT JOIN wb_hotel ON wb_hotel.id = wb_panel.hotel_id')->where('wb_panel.hotel_id='.$hotel_id.' and wb_panel.status=1')->order('wb_panel.sort ASC,wb_panel.id DESC')->select();
        //dump(M('panel')->getLastSql());
	    $panel_list = array();
	    if($lists){
	        foreach ($lists as $k=>$v){
	            if($v['pid']==0){
	                $panel_list[$k] = $v;
	                for($i=0;$i<count($lists);$i++){
	                    if($v['id']==$lists[$i]['pid']){
	                        $panel_list[$k]['block_name'][] = $lists[$i];
	                    }
	                }
	            }
	        }
	    }        

	    //dump($panel_list);
	    $this->assign('hotel_id',$hotel_id);
	    $this->assign('hotel_name',$hotel_name);
	    $this->assign('panel_list',$panel_list);
        $this->display();
    }

    //修改
	public function insert($id = 0){
	    $id = intval($id);
	    $hotel_id = I('hotel_id');
	    if($id){
    	    $panel= M("Panel");
    	    $panel_info = $panel->getById($id);
    	    if(!$panel_info){
    	        $this->error('此panle不存在');
    	    }
	    }	    
	    
	    //获取panel列表
	    $map = 'pid=0' ;
		$select_panel = $panel->field('*')->where($map)->order('sort asc,id desc')->select();	
		//dump($select_panel);exit;
		$select_tag = '<select name="pid">';
		$select_tag.= '<option value=0>新增面板</option>';
		if($select_panel){
    		foreach ($select_panel as $k=>$val){	
    		    $selected = '';
    		    if($val['id']==$panel_info['pid'] || $val['id']==$panel_info['id']){
    		        $selected ='selected="selected"';
    		    }
    		    $select_tag.= '<option value="'.$val['id'].'"'.$selected.'>'.$val[panel_name].'</option>';
    		}
		}
		$select_tag.= '</select>';
		
		//dump($panel_info);exit;		
        $this->assign('hotel_id',$hotel_id);
	    $this->assign('panel_info',$panel_info);
		$this->display();
	}
	
	public function add(){
	    $id = I('post.id');
	    $pid = I('post.pid');
	    $hotel_id = I('hotel_id');
	    //dump($hotel_id);exit;
	    
        $data['panel_name'] = I('post.panel_name','','trim');
        if($pid==0){
        	$data['panel_no'] = I('post.block_no','','trim');
        	if(empty($data['panel_no'])){
        		$this->error('数字标识必填！');
        	}
        }
        else{
        	$data['block_no'] = I('post.block_no','','trim');
        	    if(empty($data['block_no'])){
        		$this->error('数字标识必填！');
        	}
        }
                
        //$data['sort'] = I('post.sort',0,'intval');
        //$data['status'] = I('post.status',0,'intval');
	    
	    $panel = D('panel');	    
	    if(empty($data['panel_name'])){
	        $this->error('应用名必填');
	    }

        $data['remark'] = I('post.remark','',array('strip_tags','trim'));
	
	    if($id){
	    	//dump($data);exit;
	    	//更新
	        $list = $panel->data($data)->where(array('id'=>$id))->save();
	        if($list!==false){
	            $vo['message'] = '修改成功！';
	        }
	        else{
	            $vo['message'] = '修改失败!';
	        }
	    }
	    else{
	        //新增
	        $panel_name = $panel->where("panel_name='".$data['panel_name']."' and pid=0")->getField('panel_name');
	        if(empty($panel_name)){
		        $data['pid'] = $pid;
		        $data['hotel_id'] = I('post.hotel_id','intval');
		        //dump($data);exit;
		    	$list = $panel->data($data)->add();
		        if($list!==false){
		            $vo['message'] = '新增成功!';
		        }
		        else{
		            $vo['message'] = '新增失败!';
		        }
	       }
	       else{
	       	   $vo['message'] = '已存在!';
	       }
	    }

	   if ($list!==false) {
	       $this->success($vo['message'], "__GROUP__/" . MODULE_NAME . "/index/hotel_id/".$hotel_id);
	    }else {
	        $this->error($vo['message']);
	    }
	}	

	//新增
	public function create(){
	    $hotel_id = I('hotel_id','','trim');
	    //获取panel列表
	    $map = 'pid=0 and status=1 and hotel_id='.$hotel_id;
	    $panel = M('Panel');
		$select_panel = $panel->field('*')->where($map)->order('sort asc,id desc')->select();	
		//dump($select_panel);exit;
		$select_tag = '<select name="pid" id="pid" style="width:160px">';
		$select_tag.= '<option value=0>新增面板</option>';
		if($select_panel){
    		foreach ($select_panel as $k=>$val){	
    		    $selected = '';
//     		    if($val['id']==$panel_info['pid'] || $val['id']==$panel_info['id']){
//     		        $selected ='selected="selected"';
//     		    }
    		    $select_tag.= '<option value="'.$val['id'].'"'.$selected.'>'.$val[panel_name].'</option>';
    		}
		}
		$select_tag.= '</select>';
		
		//dump($node_info);exit;	
		$hotel_id = I('hotel_id','intval');	
		$this->assign('hotel_id',$hotel_id);
	    //$this->assign('panel_info',$panel_info);
	    $this->assign('select',$select_tag);
		$this->display();
	}


	public function del(){
	    $id = I('id','','intval');
	    
	    if(!$id) {
	        $this->error("请勾选删除项!");
	    }
	    
	    else if(!is_array($id)){
	        $ids['0'] = $id;
	    }
	    else{
	        $ids = $id;
	    }
	    //dump($ids);exit;
	    $panel= D('Panel');
	    $count = 0;
	    foreach ($ids as $val){
	       $count+= intval($panel->where(array('pid'=>$val))->count());
	    }
	    //dump($node_info);exit;
	    if($count>0){
	        //dump($count);exit;
	        $this->error('不能删除下面有子项!');
	    }
	    else{
	        $panel->where('id in ('.implode(',',$ids).')')->delete();
	        //$panel->data('status=-1')->where('id in ('.implode(',',$ids).')')->save();
	        $this->success("删除成功");	        
	    }
	}


}
?>