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
    	$hotel_name = M('Hotel')->where('id='.$hotel_id)->getField('hotel_name');
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
	    $hotel_name = M('Hotel')->where('id='.$hotel_id)->getField('hotel_name');
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
        $this->assign('hotel_name',$hotel_name);
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
	        //$panel_name = $panel->where("panel_name='".$data['panel_name']."' and pid=0")->getField('panel_name');
	        //if(empty($panel_name)){
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
	       //}
	       //else{
	       //	   $vo['message'] = '已存在!';
	       //}
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
	    $hotel_name = M('Hotel')->where('id='.$hotel_id)->getField('hotel_name');
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
	    $this->assign('hotel_name',$hotel_name);
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
	       //dump($panel->getLastSql());
	    }
	    //exit;
	    //dump($node_info);exit;
	    if($count>0){
	        //dump($count);exit;
	        $this->error('不能删除，下面有子项!');
	    }
	    else{
	        $panel->where('id in ('.implode(',',$ids).')')->delete();
	        //$panel->data('status=-1')->where('id in ('.implode(',',$ids).')')->save();
	        $this->success("删除成功");	        
	    }
	}
	
	//导入Panel文件
	public function import(){
	    $hotel_id = I('hotel_id');
	    $hotel = M('Hotel');
	    $hotel_info = $hotel->field('id,hotel_name')->where('id='.$hotel_id)->find();

	    if (!empty ( $_FILES)){
	        import("ORG.Net.UploadFile");
	        $upload = new \UploadFile();
	        $upload->maxSize   =  1048576000 ;
	        $upload->allowExts =  array('xls','xlsx');
	        $upload->savePath  = './Public/attached/excel/';
	        $upload->uploadReplace = ture;//存在同名文件是否是覆盖
	       
	        // 上传文件
	        $upload->upload();
	        $info = $upload->getUploadFileInfo();
	        $exts   = $info[0]['extension'];
	        $filename = $upload->savePath.$info[0]['savename'];
	        //dump($filename);exit;
	        if(!$info) {
	            //$upload->getError();
	            $this->error('请上传导入文件！');
	        }else{
	            Vendor("PHPExcel/PHPExcel/");//引入phpexcel类(留意路径,不了解路径可以查看下手册)
	            Vendor("PHPExcel/PHPExcel/IOFactory"); //引入phpexcel类(留意路径)
	            if(strtolower($exts)=='xls')//判断excel表类型为2003还是2007
	            {
	                Vendor("PHPExcel/PHPExcel/Reader/Excel5"); //引入phpexcel类(留意路径)
	                $objReader = PHPExcel_IOFactory::createReader('Excel5');
	            }else if(strtolower($exts)=='xlsx'){
	                Vendor("PHPExcel/PHPExcel/Reader/Excel2007");//引入phpexcel类(留意路径)
	                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
	            }
	            
	            $PHPExcel = $objReader->load($filename);                     // 载入文件
	            //dump($PHPExcel);exit;
	            
	            $currentSheet = $PHPExcel->getSheet(0);                      // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
	            $allColumn = $currentSheet->getHighestColumn();              // 获取总列数
	            $allRow = $currentSheet->getHighestRow();                    // 获取总行数
	            for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {// 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
	                for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {// 从哪列开始，A表示第一列
	                    $address = $currentColumn . $currentRow;             // 数据坐标
	                    $ExlData[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();// 读取到的数据，保存到数组$arr中
	              }
	            }
	            
	            //dump($hotel_id);
	            $panelData = array();
	            foreach ($ExlData as $k=>$v){
	                //dump($v['A']);
	                //传hotel_id导单个酒店，不传则导所有酒店;	                
	                if(!empty($v['A'])){
	                    //$hotel_id = $hotel->where('hotel_name="'.$v['A'].'"')->getField('id');
	                    //dump($hotel_id);
	                    
	                }    	                
                    $panelData[$k] = $v;
                    $panelData[$k]['A'] = $hotel_id;                
	            }
	                
	            //dump($panelData); 
	            //echo "<hr/>";
	            //dump($ExlData);exit;
	            $result = $this->importExcel_RAW($panelData,$hotel_id);
	            if ($result['error']==0) {
	                $this->redirect('panel/index',array('hotel_id'=>$result['hotel_id']));
	            }
	            else {
	                $this->error("导入失败！");// 提示错误
	            }
	        }
	    }
	    else{
	        //dump($hotel_info);
	        $this->assign('hotel_info',$hotel_info);
	        $this->display();
	    }
	}
	
	//导入Panel数据
	public function  importExcel_RAW($ExlData, $hotel_id = 0){   // 将导入表中的数据添加到  数据库数组中去
	    $hotel = M('Hotel');
	    $panel = M('Panel');
	    $create_time = date('Y-m-d H:i:s');
	    $import_number = 0;
	    //验证不能为空
	    for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
	        if(empty($ExlData[$i]['A'])){
	            $this->error("第:".$i++."行不能为空!");
	        }
	        if(empty($ExlData[$i]['B'])){
	            $this->error("第:".$i++."行不能为空!");
	        }
	        if(empty($ExlData[$i]['C'])){
	            $this->error("第:".$i++."行不能为空!");
	        }
	        if(empty($ExlData[$i]['D'])){
	            $this->error("第:".$i++."行不能为空!");
	        }
	        if(empty($ExlData[$i]['E'])){
	            $this->error("第:".$i++."行不能为空!");
	        }
	        
	    }
	    //新增修改面板
	    $panelData = array();
	    foreach ($ExlData as $k=>$v){
	          if(in_array($v['B'], $panelData)==false){
	               $panelData[$v['D']] = $v['B'];
	               $id = $panel->where('panel_name="'.$v['B'].'" and hotel_id='.$hotel_id)->getField('id');
	               $data['hotel_id'] = $hotel_id;
	               $data['panel_no'] = $v['D'];
	               $data['panel_name'] = $v['B'];
	               if($id){
	                   //更新
	                   //dump($data);
	                   //$panel->data($data)->where('id='.$id)->save();
	               }
	               else{
	                   //新增	                   
	                   $panel->data($data)->add();
	               }
	               
	          }
	    }
	    //dump($ExlData);exit;
	    //新增修改推荐位
	    $result = array('error'=>0,'messge'=>'','hotel_id'=>$hotel_id);
	    for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
	        $dataList[] = array(
	            'hotel_id'=>trim($ExlData[$i]['A']),
	            'panel_name'=> trim($ExlData[$i]['B']),
	            'block_name'=>trim($ExlData[$i]['C']),
	            'panel_no'=>strtolower(trim($ExlData[$i]['D'])),
	            'block_no'=>strtolower(trim($ExlData[$i]['E'])),
	            'status'=>1,
	            'sort' =>100,
	            'remark'=>'',
	        );
	        try{
	            //不存在则添加	            
	            $panel_id = $panel->where('hotel_id='.trim($ExlData[$i]['A']).' and panel_name="'.trim($ExlData[$i]['B']).'"')->getField('id');
	            $block_id = $panel->where('hotel_id='.trim($ExlData[$i]['A']).' and panel_name="'.trim($ExlData[$i]['C']).'" and pid='.$panel_id)->getField('id');
	            
	            $dataList[$j]['pid'] = $panel_id;
	            $dataList[$j]['block_no'] = $ExlData[$i]['E'];
	            $dataList[$j]['panel_name'] = $ExlData[$i]['C'];
	            
	            if(!$block_id){
	                //新增推荐位
	                $panel_id_pn = $panel->data($dataList[$j])->add();	               
	            }
	            else{
	                //更新推荐位	                
	                //$panel->data($dataList[$j])->where('hotel_id='.$dataList[$j]['hotel_id'].' and id='.$block_id)->save();
	            }
	           
	        }catch (\Exception $e){
	            //echo $e->getMessage();
	            $result['error'] = 1;
	            $result['message'] = $e->getMessage();
	        }
	        
	    }
	    //dump($result);exit;
	    return $result;
	}   
	
	public function panel_template(){
	    header("Content-type:text/html;charset=utf-8");
	    $save_name = 'panel_template.xlsx';
	    $file_path = $_SERVER['DOCUMENT_ROOT'].'/data/excel_template/'.$save_name;
	    ob_end_clean();
	    $hfile = fopen($file_path, "rb") or die("Can not find file: $file_path\n");
	    Header("Content-type: application/octet-stream");
	    Header("Content-Transfer-Encoding: binary");
	    Header("Accept-Ranges: bytes");
	    Header("Content-Length: ".filesize($file_path));
	    Header("Content-Disposition: attachment; filename=\"$save_name\"");
	    while (!feof($hfile)) {
	        echo fread($hfile, 32768);
	    }
	    fclose($hfile);
	    exit;
	} 

}
?>