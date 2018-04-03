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
class AccountAction extends CommonAction {

    public function index() {
        $user_id_mode = M('user_id');
        $where = '1=1 and status<>-1 ';  
        $user_id = I('user_id','','trim');
        $device_code = I('device_code','','trim');
        $device_mac = I('device_mac','','trim');
        $status = I('status','','trim');
                     
        if ($user_id) {
            $where .= "and user_id='".$user_id."' ";
        }
        if($device_code){
            $where .= "and device_code='".$device_code."' ";
        }
        if($device_mac){
            $where .= "and device_mac='".$device_mac."' ";
        }
        if(!empty($status)){
            if($status==-1){
                
                $where .= "and device_code='' and device_mac='' and status=0 ";
            }
            else{
               $where .= "and status=".$status." ";
            }
        }        
        
        //dump($where);
        import("ORG.Util.Page"); 
        $count = $user_id_mode->where($where)->count(); //计算总数
        $pagesize = 120;
        $p = new Page($count, $pagesize);
        $list = $user_id_mode->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select(); 
     
        $hotel_model = M('hotel');
        $device_model = M('device');
        foreach ($list as $k=>$v){
            $lists[$k] = $v;
            $hotel_id = $device_model->where("user_id='".$v['user_id']."'")->getField('hotel_id');
            $hotel_name = $hotel_model->where('id='.$hotel_id)->getField('hotel_name');
            $lists[$k]['hotel_name'] = $hotel_name;
        }
        
        //dump($lists);exit;
        
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');

        $this->assign("page", $p->show());
        $this->assign('list', $lists);       
        $this->display();
    }				

    public function insert(){
        if(IS_POST){
            $id = I('post.id',0,'trim');
            $data['user_id'] = I('post.user_id',0,'trim');
            if(empty($data['user_id'])){
                $this->error('账号不能为空！');
            }
            if(preg_match("^([+-]?)\\d*\\.?\\d+$",$data['user_id'])){
                $this->error("账号只能为数字格式!");
            }
            $user_id = M('user_id');
            if(empty($id)){
                //新增
                $data['at_time'] = date('Y-m-d H:i:s');
                $data['status'] = 0;
                $count = $user_id->where("user_id='".$data['user_id']."' and status<>-1")->count();
                //用户是否存在
                if(!empty($count)){
                    $this->error('用户账号已存在!');
                }
                
                $result = $user_id->data($data)->add();
                if($result!=false){
                    addlog('添加UID:'.$result);
                    $this->success('新增成功！',U('index'));
                }
                else{
                    $this->error('新增失败！');
                }
            }
            else{
                $data['at_time'] = date('Y-m-d H:i:s');
                $result = $user_id->data($data)->where("id=".$id)->save();
                if($result!=false){
                    addlog('修改UID:'.$id);
                    $this->success('修改成功！');
                }
                else{
                    $this->error('修改失败！');
                }
            }
        }
        else{
            $this->display(); 
        }
    }
    
    public function del($ids = 0){
        //$this->error('此功能尚未开放...');
        $getid = I('ids',0,'strip_tags,trim');
        $getids = implode(',',$getid);
        $ids = is_array($getid) ? $getids : $getid;
        //dump($ids);exit;
        $user_id = M('user_id');
        if($ids){
            $uid = I('user_id',0,'strip_tags,trim'); //user_id           
            $result= $user_id->where("id in('".$ids."')")->data('status=-1')->save();
            if(!empty($result)){
                addlog('删除电话账号ID:'.$ids);
                $device_result = M('device')->where("user_id in('".$uid."')")->data('status=-1')->save(); //删除酒店设备
                if(!empty($device_result)){
                    addlog('删除同步数据UID:'.$uid);
                }
                               
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
    
    
    public function import(){
    
        $hotel_id = I('hotel_id',0,'strip_tags,trim');
        $group_id = I('group_id',0,'strip_tags,trim');
        $hotel_name = I('hotel_name','','strip_tags,trim');
    
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
                $this->error('请上传导入文件！');
                //$this->error($upload->getError());
            }else{
                Vendor("PHPExcel/PHPExcel/");
                Vendor("PHPExcel/PHPExcel/IOFactory"); 
                if(strtolower($exts)=='xls')
                {
                    Vendor("PHPExcel/PHPExcel/Reader/Excel5"); 
                    $objReader = PHPExcel_IOFactory::createReader('Excel5');
                }else if(strtolower($exts)=='xlsx'){
                    Vendor("PHPExcel/PHPExcel/Reader/Excel2007");
                    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                }
    
                $PHPExcel = $objReader->load($filename);                    
                //dump($PHPExcel);exit;
    
                $currentSheet = $PHPExcel->getSheet(0);                     
                $allColumn = $currentSheet->getHighestColumn();              // 获取总列数
                $allRow = $currentSheet->getHighestRow();                    // 获取总行数
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
                    for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
                        $address = $currentColumn . $currentRow;             // 数据坐标
                        $ExlData[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();// 读取到的数据，保存到数组$arr中
                    }
                }
    
                //dump($ExlData);exit;
                $data = $this->importExcel_RAW($ExlData);
                if ($data>0) {
                    $this->success("成功导入".$data."条！",U('index'));
                }
                else if($data==0){
                    $this->success("导入".$data."条！",U('index'));
                }
                else {
                    $this->error("导入失败，可能是excel中有些已经导入，或表格格式错误！");// 提示错误
                }
            }
        }
        else{
            $this->assign('hotel_id',$hotel_id);
            $this->assign('group_id',$group_id);
            $this->assign('hotel_name',$hotel_name);
            $this->display();
        }
    }
     
    public function  importExcel_RAW($ExlData){   // 将导入表中的数据添加到  数据库数组中去
        $user_id = M('user_id');
        $at_time = date('Y-m-d H:i:s');
        //dump(sizeof($ExlData));exit;
        $import_number = 0;
        //验证不能为空
        for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
            if(empty($ExlData[$i]['A'])){
                $this->error("第:".$i++."行不能为空!");
            }
//             if(preg_match("/([\x{4e00}-\x{9fa5}])/u",$ExlData[$i]['A'])){
//                 $this->error("第:".$i++."行格式不正确!");
//             }            
            if(stripos($ExlData[$i]['A'], 'E+')){
                $this->error("第:".$i++."行不为文本格式!");
            }
        }
    
        for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
            $dataList[] = array(
                'user_id' =>trim($ExlData[$i]['A']),
                'at_time' => $at_time,
                'status'  => 0,
            );
            try{
                //不重复添加
                $map['user_id'] = trim($dataList[$j]['user_id']);
                $user_id_count = $user_id->where('user_id='.$map['user_id'])->count();
                if(empty($user_id_count)){
                    $user_info_id = $user_id->data($dataList[$j])->add();
                    $import_number++;
    
                }
               
                 
            }catch (\Exception $e){
                echo $e->getMessage();
            }
    
        }
    
        //dump($dataList);exit;
         
        return $import_number;
    }    
    
}
