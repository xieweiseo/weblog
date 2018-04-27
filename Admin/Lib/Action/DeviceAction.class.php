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
class DeviceAction extends CommonAction {

    public function index() {
        $device = M('Device');
        $where = '1=1 and status in(1,0) ';  
        $hotel_id = I('hotel_id','','trim');
        $group_id = I('group_id','','trim');
        $user_id = I('user_id','','trim');
        $device_code = I('device_code','','trim');
        $device_mac = I('device_mac','','trim');
        $ysten_gid = I('ysten_gid','','trim');
        $serial_no = I('serial_no','','trim');
        if ($hotel_id) {
            $where .= "and hotel_id=".$hotel_id." ";
        }
        if ($user_id) {
            $where .= "and user_id='".$user_id."' ";
        }
        if($serial_no){
            $where .= "and serial_no='".$serial_no."' ";
        }
        if($device_code){
            $where .= "and device_code='".$device_code."' ";
        }
        if($device_mac){
            $where .= "and device_mac='".$device_mac."' ";
        }
        if($ysten_gid=='-1'){
            $where .= "and group_id<>ysten_gid ";
        }
        if($ysten_gid=='1'){
            $where .= "and group_id=ysten_gid ";
        }

        dump($where);
        import("ORG.Util.Page"); 
        $count = $device->where($where)->count(); //计算总数
        $pagesize = 18;
        $p = new Page($count, $pagesize);
        $list = $device->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select(); 
        $hotel_name = M('hotel')->where('id='.$hotel_id)->getField('hotel_name');
     
        $lists =  array();
        $i = isset($_GET['p'])? intval(I('p'))*$pagesize-$pagesize+1 : 1;
        foreach ($list as $k=>$v){
            $lists[$k] = $v;
            $lists[$k]['devid'] = $i;
            $i++;
        }               
        
        //导出
        $export = isset($_GET['export']) ? $_GET['export'] : '';
        if($export){
            $mac_list = $device->where($where)->order('id desc')->select();
            //dump($mac_list);
            $this->expload($mac_list,$hotel_name);
        }              
        
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');

        $this->assign("page", $p->show());
        $this->assign('list', $lists); 
        $this->assign('hotel_id',$hotel_id);
        $this->assign('group_id',$group_id);
        $this->assign('hotel_name',$hotel_name);        
        $this->display();
    }
				
	public function edit($id = 0) {
	    $id = intval($id);
	    $group_id = I('group_id','','strip_tags,trim');
	    $hotel_id = I('hotel_id','','strip_tags,trim');
	    if($id){
            $device = M('Device');
    	    $device_info = $device->where("id=".$id)->find();
    	    $this->assign('vo',$device_info);
	    }
	    else{
	        //$this->error('参数错误！');
	        $this->assign('vo',array('group_id'=>$group_id,'hotel_id'=>$hotel_id));	        
	    }
	    
		$this->display('insert'); 
    }    
    
    public function insert(){
        if(IS_POST){
            $id = I('post.id',0,'trim');            
            $data['user_id'] = I('post.user_id',0,'trim');
            $data['device_code'] = I('post.device_code','','strip_tags,trim');
            $data['device_mac'] = strtolower(I('post.device_mac','','strip_tags,trim'));
            $data['group_id'] = I('post.group_id',0,'trim');
            $data['hotel_id'] = I('post.hotel_id',0,'trim');
  
            if(empty($data['user_id'])){
                $this->error('账号为空！');
            }
            else if(preg_match("^([+-]?)\\d*\\.?\\d+$",$data['user_id'])){
                $this->error("账号只能为数字格式!");
            }
            else if(empty($data['device_code'])){
                $this->error('广电号为空！');
            }
            else if(empty($data['device_mac'])){
                $this->error('Mac为空！');
            }           
            
            $device = M('Device');
            $user_id = M('user_id');
            if(empty($id)){
                //新增
                $data['create_date'] = date('Y-m-d H:i:s');
                $data['at_time'] = date('Y-m-d H:i:s');
                $data['status'] = 0;
                $result = $device->data($data)->add();
                if($result!=false){
                    addlog('添加DE:'.$result);
                    $data['at_time'] = $data['create_date'];
                    $user_id->data($data)->add(); //在user_id表中新增数据
                    $this->success('恭喜，新增成功！',U('index',array('hotel_id'=>$data['hotel_id'])));
                }
                else{
                    $this->error('新增失败！');
                }
            }
            else{
                $data['update_date'] = date('Y-m-d H:i:s');
                
                //修改mac
                $macs = $device->field('device_mac,retain_mac')->where("id=".$id)->find();
                if($data['device_mac']!=$macs['device_mac']){
                    $data['retain_mac']= $data['device_mac'].'|'.$macs['retain_mac'];
                }
                
                //dump($macs);exit;
                $result = $device->data($data)->where("id=".$id)->save();   
                if($result!=false){
                    addlog('修改DE:'.$id);
                    $user_id_list['device_code'] = $data['device_code'];
                    $user_id_list['device_mac'] = $data['device_mac'];
                    $user_id_list['up_time'] = $data['create_date'];
                    $user_id->data($user_id_list)->where("user_id='".$data['user_id']."' and status=1")->save(); //在user_id表中新增数据 
                    $this->success('恭喜，修改成功！',U('index',array('hotel_id'=>$data['hotel_id'])));
                }
                else{
                    $this->error('修改失败！');
                }                   
            }    
        }               
    }
    
    public function del($ids = 0){
        //$this->error('此功能尚未开放...');
        $getid = I('ids',0,'strip_tags,trim');
        $getids = implode(',',$getid);
        $ids = is_array($getid) ? $getids : $getid;   
        $device = M('Device');
        $user_id = M('user_id');
        if($ids){
            //读取user_id
            $uid_list = $device->field('user_id')->where("id in(".$ids.")")->select();
            foreach ($uid_list as $val){
                $uids[] = $val['user_id'];
            }           
            $user_list = implode(',', $uids);
            
            //删除酒店设备
            $result= $device->where("id in(".$ids.")")->data('status=-1')->save();
            if($result!=false){
                addlog('删除酒店设备DE:'.$ids);
                //删除user_id表中数据
                $user_result = $user_id->where("user_id in(".$user_list.")")->data('status=-1')->save();                
                if(!empty($user_result)){
                    addlog('删除同步数据UID:'.$user_list);
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
    
    /**
     * 全部同步 ysten_gid
     */
    public function synchro(){
        $hotel_id = I('hotel_id','','strip_tags,trim');
        $device = M('device');
        if(empty($hotel_id)){
          //全部  
          $device_list = $device->field('id,device_mac,group_id,ysten_gid')->where('group_id<>ysten_gid')->order('id desc')->select();
        }
        else{
            //指定hotel_id
            $device_list = $device->field('id,device_mac,group_id,ysten_gid')->where('group_id<>ysten_gid and hotel_id='.$hotel_id)->order('id desc')->select();
        }
        //dump($device_list);exit;
        foreach ($device_list as $k=>$v){
            $url = C('SERVICE_SET').$v['device_mac'];
            $data = $this->get_xml($url);
            $service_data = simplexml_load_string($data);
            
            $screen_id = $service_data->screenId;
            $screenId = json_decode(urldecode($screen_id));
            $devs['ysten_gid'] = $screenId->deviceGroupIds[0];    
            if($devs['ysten_gid']==$v['group_id']){
                $devs['status'] = 1; //上线状态
            }
            try {
                   //if($v['group_id']!=$v['ysten_gid']){
                      $device->data($devs)->where('id='.$v['id'])->save();  
                   //}
               }
            catch (Exception $exc){
                
                $this->error($exc);
            }        
        }
        
        $this->success('同步成功！');exit;
    }
    
    /**
     * 单个同步ysten_gid
     */
    public function sync(){
        $device_mac = I('mac','','strip_tags,trim');
        if($device_mac){
            $url = C('SERVICE_SET').$device_mac;
            $data = $this->get_xml($url);
            $service_data = simplexml_load_string($data);
            
            $device = M('device');
            $screen_id = $service_data->screenId;
            $screenId = json_decode(urldecode($screen_id));
            $devs['ysten_gid'] = $screenId->deviceGroupIds[0];
            try {
                $device_info = $device->field('id,ysten_gid')->where("device_mac='".$device_mac."'")->find();
                if($device_info['id'] && $devs['ysten_gid'] != $device_info['ysten_gid']){
                    $devs['status'] = 1;
                    $reslt = $device->data($devs)->where("device_mac='".$device_mac."'")->save();
                }
            }
            catch (Exception $exc){
            
                $this->error($exc);
            } 
        }
        else{
            $this->error('参数错误！');
        }
        
        //if($reslt!=false){
            $this->success('同步成功！');
        //}
        //else{
        //    $this->error('同步失败！');
        //}
    }
 
    /**
     * 多个同步ysten_gid
     */
    public function syncs(){
        $macs = I('macs','','strip_tags,trim');

        foreach ($macs as $device_mac){
            $url = C('SERVICE_SET').$device_mac;
            $data = $this->get_xml($url);
            $service_data = simplexml_load_string($data);
    
            $device = M('device');
            $screen_id = $service_data->screenId;
            $screenId = json_decode(urldecode($screen_id));
            $devs['ysten_gid'] = $screenId->deviceGroupIds[0];
            try {
                $device_info = $device->field('id,ysten_gid')->where("device_mac='".$device_mac."'")->find();
                if($device_info['id'] && $devs['ysten_gid'] != $device_info['ysten_gid']){
                    $devs['status'] = 1;
                    $reslt = $device->data($devs)->where("device_mac='".$device_mac."'")->save();
                }
            }
            catch (Exception $exc){
    
                $this->error($exc);
            }
        }
    
        if($reslt!=false){
            $this->success('同步成功！');
        }
        else{
            $this->error('同步失败！');
        }
    }
    
    public function service(){        
        $mac = I('mac','','trim');
        $hotel_name = I('hotel','','trim');
        
        if(empty($mac)){
            $this->error('参数不正确！');
        }
        $url = C('SERVICE_SET').$mac;
        $data = $this->get_xml($url); 
        $service_data = simplexml_load_string($data);
        
        $screen_id = $service_data->screenId;
        $screenId = json_decode(urldecode($screen_id));
        $config['group_id'] = $screenId->deviceGroupIds[0];
        $config['panel'] = $service_data->sysconfig->config[19];
        $config['mac'] = $mac;
        $config['data'] = urlencode($screen_id);

        //面板缩略图
        $url = C('PANEL').$screen_id.'.xml';
        $list = '';
        $f = fopen($url, 'r');
        while($img= fread($f,4096)){
            $list .= $img;
        }
        fclose($f);
        preg_match_all("/<imgurl>(.*?)<\/imgurl>/s",$list,$imgs);
        $panel_img = str_replace(']]>','',str_replace('<![CDATA[', '', $imgs[1][0]));
         
        $config['image'] = $panel_img; 
        $config['hotel_name'] = $hotel_name;
        $this->assign('config',$config);
        $this->display('service');
    }
    
    public function panel(){    
        $mac = I('mac','','trim');
        
        if($mac){
            $url = C('SERVICE_SET').$mac;
            $data = $this->get_xml($url);
            $service_data = simplexml_load_string($data);
        
            $screen_id = $service_data->screenId;
            $screenId = json_decode(urldecode($screen_id));
            $config['group_id'] = $screenId->deviceGroupIds[0];
            $config['panel'] = $service_data->sysconfig->config[19];
            $config['mac'] = $mac;
            $config['data'] = urlencode($screen_id);
             
            //面板缩略图
            $url = C('PANEL').$screen_id.'.xml';         
            $list = '';
            $f = fopen($url, 'r');
            while($img= fread($f,4096)){
                $list .= $img;
            }
            fclose($f);
            preg_match_all("/<imgurl>(.*?)<\/imgurl>/s",$list,$imgs);        
            $panel_img = str_replace(']]>','',str_replace('<![CDATA[', '', $imgs[1][0]));
             
            $config['image'] = $panel_img;   
            
            $this->assign('config',$config);
        }
        
        
        $this->display('panel');
    }    
    
   public function panel_view(){
       $mac = I('mac','','trim');
       $param = I('data','','trim');
       $url = C('PANEL').urldecode($param).'.xml';
       //$data = $this->get_xml($url);
       //$panel_data = simplexml_load_string($data);
       
       $config = '';
       $f = fopen($url, 'r');
       while($data = fread($f,4096)){
           $config .= $data;
       }
       fclose($f);
       preg_match_all("/<imgurl>(.*?)<\/imgurl>/s",$config,$imgs);
       
       $panel_img = str_replace(']]>','',str_replace('<![CDATA[', '', $imgs[1][0]));
       
       echo "<img src='".$panel_img."'>";
       
    }
       
    public function get_group_select($id = 0){
        $device_group = M('device_group');
        $select_list = $device_group->field('id,group_id,group_name')->order('id ASC')->select();

        $select = '<select name="group_id" id="group_id">';   
        $select .= '<option value="0">--选择分组--</option>'; 
        if($select_list){           
            foreach ($select_list as $v){
               if($id==$v['id']){
                   $selected = 'selected ="selected"';
               }
               else{
                   $selected = '';
               }
               $select .='<option value="'.$v[id].'" '.$selected.'>'.$v['group_id'].'</option>'; 
            }
        }

         $select .='</select>';
        
        return $select;
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
                       $ExlData[$currentRow][$currentColumn] = strtoupper($currentSheet->getCell($address)->getValue());// 读取到的数据，保存到数组$arr中       
                   }       
               }

               //dump($ExlData);exit;
               if(empty($group_id)){
                   $this->error('参数不正确,group_id 为空!');
               }
               
               //判断是否为mac导入
               $account_import = I('account_import');
               if($account_import==1){
                   //电视账号导入
                   $data = $this->importExcel_USER($ExlData,$group_id,$hotel_id);
               }
               else if($account_import==2){
                   //mac导入
                   $data = $this->importExcel_MAC($ExlData,$group_id,$hotel_id);
               }
               else if($account_import==3){
                   //广电号导入
                   $data = $this->importExcel_Device_Code($ExlData,$group_id,$hotel_id);
               }
               else if($account_import==4){
                   //串号导入
                   $data = $this->importExcel_Serial_Number($ExlData,$group_id,$hotel_id);
               }
               else{
                    $this->error('请选择导入账号类型');
               }


               
               if ($data>0) {
                   $this->success("恭喜，导入".$data."条！",U('index',array('hotel_id'=>$hotel_id,'group_id'=>$group_id)));     
               }
               else if($data==0){
                   $this->success("成功导入".$data."条！",U('index',array('hotel_id'=>$hotel_id,'group_id'=>$group_id)));
               }
               else { 
                   $this->error("导入失败，原因可能是excel表中有些节目已经导入，或表格格式错误！");// 提示错误                          
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
   
   /**
    * user_id 电视账号导入
    * @param array  $ExlData
    * @param number $group_id
    * @param number $hotel_id
    */
   public function  importExcel_USER($ExlData, $group_id = 0 ,$hotel_id = 0){   // 将导入表中的数据添加到  数据库数组中去         
       $device = M('Device');
       $user_id = M('user_id');
       $create_time = date('Y-m-d H:i:s');
       //dump(sizeof($ExlData));exit;
       $import_number = 0;
       
       //验证不能为空       
       for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
           if(empty($ExlData[$i]['A'])){
               $this->error("第:".$i++."行不能为空!");
           }
           if(preg_match("/([\x{4e00}-\x{9fa5}])/u",$ExlData[$i]['A']) || $this->mac_validate($ExlData[$i]['A'])){
               $this->error("第:".$i++."行user_id格式不正确!");
           }
           if(stripos($ExlData[$i]['A'], 'E+')){
               $this->error("第:".$i++."行不为文本格式!");
           }
           if(1==stripos($ExlData[$i]['A'], '0')){
               $this->error("第:".$i++."行user_id格式不正确!");
           }
       }
       
       
       //验证重复记录并输出重复数据
       foreach ($ExlData as $k=>$v){
           $tt[]= $v['A'];
       }
       $list = array_diff_assoc($tt,array_unique($tt));     
       if(!empty($list)){
           $hotel_name = M('hotel')->where('id='.$hotel_id)->getField('hotel_name');
           if($hotel_name){
               echo "表格中有(".count($list)."条)重复数据<br/>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               foreach ($list as $val){
                   echo $val."<br/>";
               }
               echo "<a href='".U('import',array('hotel_id'=>$hotel_id,'group_id'=>$group_id,'hotel_name'=>$hotel_name))."'>返 回</a>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               exit;
           }
       }
       
       //echo date("H:i:s");
       //echo "<pre>";      
       $sql_device = "INSERT INTO wb_device (user_id,hotel_id,group_id,create_date,status) VALUES ";  
       $sql_user_id = "INSERT INTO wb_user_id (user_id,at_time,status,type) VALUES ";
       for($i = 2;$i<=sizeof($ExlData)+1;$i++){
           $uid = $device->where("user_id='".$ExlData[$i]['A']."' and hotel_id='".$hotel_id."' and status<>-1")->count();
          if(!$uid){
                $sql_device.="('".$ExlData[$i]['A']."',".$hotel_id.",'".$group_id."','".$create_time."',0),";
                $sql_user_id.="('".$ExlData[$i]['A']."','".$create_time."',0,1),";
                $import_number++;
           }
       }
       
       $sql_device = substr($sql_device,0,strlen($sql_device)-1);
       $sql_user_id = substr($sql_user_id,0,strlen($sql_user_id)-1);
      
       try{
           $device_result = $device->execute($sql_device);
           $user_id_result = $user_id->execute($sql_user_id);
          
       }catch (\Exception $e){
           echo $e->getMessage();
       }
          
       //dump($import_number);exit;
       return $import_number;  
   }

   /**
    * mac 账号导入
    * @param array $ExlData
    * @param number $group_id
    * @param number $hotel_id
    * @return number
    */
   public function  importExcel_MAC($ExlData, $group_id = 0 ,$hotel_id = 0){   
       $device = M('Device');
       $user_id = M('user_id');
       $create_time = date('Y-m-d H:i:s');
       //dump(sizeof($ExlData));exit;
       $import_number = 0;
        
       //验证不能为空
       for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
           if(!$this->mac_validate($ExlData[$i]['A'])){
               $this->error("第:".$i++."行mac格式不正确!");
           }
           if(stripos($ExlData[$i]['A'], 'E+')){
               $this->error("第:".$i++."行不为文本格式!");
           }
       }        
        
       //验证重复记录并输出重复数据
       foreach ($ExlData as $k=>$v){
           $tt[]= strtoupper($v['A']);
       }
       $list = array_diff_assoc($tt,array_unique($tt));
       if(!empty($list)){
           $hotel_name = M('hotel')->where('id='.$hotel_id)->getField('hotel_name');
           if($hotel_name){
               echo "表格中有(".count($list)."条)重复数据<br/>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               foreach ($list as $val){
                   echo $val."<br/>";
               }
               echo "<a href='".U('import',array('hotel_id'=>$hotel_id,'group_id'=>$group_id,'hotel_name'=>$hotel_name))."'>返 回</a>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               exit;
           }
       }
        
       //echo date("H:i:s");
       //echo "<pre>";
       $sql_device = "INSERT INTO wb_device (device_mac,hotel_id,group_id,create_date,status) VALUES ";
       $sql_user_id = "INSERT INTO wb_user_id (device_mac,at_time,status,type) VALUES ";
       for($i = 2;$i<=sizeof($ExlData)+1;$i++){
           $uid = $device->where("device_mac='".$ExlData[$i]['A']."' and hotel_id='".$hotel_id."' and status<>-1")->count();
           if(!$uid){
               $sql_device.="('".$ExlData[$i]['A']."',".$hotel_id.",'".$group_id."','".$create_time."',0),";
               $sql_user_id.="('".$ExlData[$i]['A']."','".$create_time."',0,3),";
               $import_number++;
           }
       }
        
       $sql_device = substr($sql_device,0,strlen($sql_device)-1);
       $sql_user_id = substr($sql_user_id,0,strlen($sql_user_id)-1);
   
       try{
           $device_result = $device->execute($sql_device);
           $user_id_result = $user_id->execute($sql_user_id);
   
       }catch (\Exception $e){
           echo $e->getMessage();
       }
   
       return $import_number;
   }   
   
   /**
    * device_code 广电号导入
    * @param array $ExlData
    * @param number $group_id
    * @param number $hotel_id
    * @return number
    */
   public function  importExcel_Device_Code($ExlData, $group_id = 0 ,$hotel_id = 0){
       $device = M('Device');
       $user_id = M('user_id');
       $create_time = date('Y-m-d H:i:s');
       //dump(sizeof($ExlData));exit;
       $import_number = 0;
   
       //验证不能为空
       for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
           if(!$ExlData[$i]['A']){
               $this->error("第:".$i++."行Device_Code不存在!");
           }
           if(stripos($ExlData[$i]['A'], 'E+')){
               $this->error("第:".$i++."行不为文本格式!");
           }
           if(1!=stripos($ExlData[$i]['A'], '0')){
               $this->error("第:".$i++."行device_code格式不正确!");
           }           
           
       }
   
       //验证重复记录并输出重复数据
       foreach ($ExlData as $k=>$v){
           $tt[]= strtoupper($v['A']);
       }
       $list = array_diff_assoc($tt,array_unique($tt));
       if(!empty($list)){
           $hotel_name = M('hotel')->where('id='.$hotel_id)->getField('hotel_name');
           if($hotel_name){
               echo "表格中有(".count($list)."条)重复数据<br/>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               foreach ($list as $val){
                   echo $val."<br/>";
               }
               echo "<a href='".U('import',array('hotel_id'=>$hotel_id,'group_id'=>$group_id,'hotel_name'=>$hotel_name))."'>返 回</a>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               exit;
           }
       }
   
       //echo date("H:i:s");
       //echo "<pre>";
       $sql_device = "INSERT INTO wb_device (device_code,hotel_id,group_id,create_date,status) VALUES ";
       $sql_user_id = "INSERT INTO wb_user_id (device_code,at_time,status,type) VALUES ";
       for($i = 2;$i<=sizeof($ExlData)+1;$i++){
           $uid = $device->where("device_code='".$ExlData[$i]['A']."' and hotel_id='".$hotel_id."' and status<>-1")->count();
           if(!$uid){
               $sql_device.="('".$ExlData[$i]['A']."',".$hotel_id.",'".$group_id."','".$create_time."',0),";
               $sql_user_id.="('".$ExlData[$i]['A']."','".$create_time."',0,2),";
               $import_number++;
           }
       }
   
       $sql_device = substr($sql_device,0,strlen($sql_device)-1);
       $sql_user_id = substr($sql_user_id,0,strlen($sql_user_id)-1);
        
       try{
           $device_result = $device->execute($sql_device);
           $user_id_result = $user_id->execute($sql_user_id);
            
       }catch (\Exception $e){
           echo $e->getMessage();
       }
        
       return $import_number;
   }   
 
   /**
    * 串号导入
    * @return nubmer [description]
    */
   public function importExcel_Serial_Number($ExlData, $group_id = 0 ,$hotel_id = 0){
       //dump($ExlData);exit;
       $device = M('Device');
       $user_id = M('user_id');
       $create_time = date('Y-m-d H:i:s');
       //dump(sizeof($ExlData));exit;
       $import_number = 0;
       
       //dump($ExlData);exit;
       //验证不能为空
       for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
           if(!$ExlData[$i]['A']){
               $this->error("第:".$i++."行serial_number不存在!");
           }
           if(stripos($ExlData[$i]['A'], 'E+')){
               $this->error("第:".$i++."行不为文本格式!");
           }                   
       }
   
       //验证重复记录并输出重复数据
       foreach ($ExlData as $k=>$v){
           $tt[]= strtoupper($v['A']);
       }
       $list = array_diff_assoc($tt,array_unique($tt));
       if(!empty($list)){
           $hotel_name = M('hotel')->where('id='.$hotel_id)->getField('hotel_name');
           if($hotel_name){
               echo "表格中有(".count($list)."条)重复数据<br/>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               foreach ($list as $val){
                   echo $val."<br/>";
               }
               echo "<a href='".U('import',array('hotel_id'=>$hotel_id,'group_id'=>$group_id,'hotel_name'=>$hotel_name))."'>返 回</a>";
               echo '<hr style="height:1px;border:none;border-top:1px solid #eee;">';
               exit;
           }
       }
   
       //echo date("H:i:s");
       //echo "<pre>";
       $sql_device = "INSERT INTO wb_device (serial_no,hotel_id,group_id,create_date,status) VALUES ";
       $sql_user_id = "INSERT INTO wb_user_id (serial_no,at_time,status,type) VALUES ";
       for($i = 2;$i<=sizeof($ExlData)+1;$i++){
           $uid = $device->where("serial_no='".$ExlData[$i]['A']."' and hotel_id='".$hotel_id."' and status<>-1")->count();
           if(!$uid){
               $sql_device.="('".$ExlData[$i]['A']."',".$hotel_id.",'".$group_id."','".$create_time."',0),";
               $sql_user_id.="('".$ExlData[$i]['A']."','".$create_time."',0,4),";
               $import_number++;
           }
       }
   
       $sql_device = substr($sql_device,0,strlen($sql_device)-1);
       $sql_user_id = substr($sql_user_id,0,strlen($sql_user_id)-1);
        
       try{
           $device_result = $device->execute($sql_device);
           $user_id_result = $user_id->execute($sql_user_id);
            
       }catch (\Exception $e){
           echo $e->getMessage();
       }
        
       return $import_number;   
   }  
   
   
   //导出
   public function expload($expTableData = '',$hotel_name = ''){      
       Vendor("PHPExcel/PHPExcel/");
       Vendor("PHPExcel/PHPExcel/IOFactory"); 
       Vendor("PHPExcel/PHPExcel/Reader/Excel5"); 
       Vendor("PHPExcel/PHPExcel/Reader/Excel2007");
       
       $objPhpExcel = new \PHPExcel();
       $objPhpExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度
        
       //设置表格的宽度
       $objPhpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
       $objPhpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
       $objPhpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
       $objPhpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
       $objPhpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
   
       //设置标题
       $rowVal = array(0=>'电视账号', 1=>'设备序号', 2=>'广电号', 3=>'MAC', 4=>'分组ID');
        
       foreach ($rowVal as $k=>$r){
           $objPhpExcel->getActiveSheet()->getStyleByColumnAndRow($k,1)
           ->getFont()->setBold(true);//字体加粗
           $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($k,1,$r);
       }
   
       //设置当前的sheet索引 用于后续内容操作
       $objPhpExcel->setActiveSheetIndex(0);
       $objActSheet=$objPhpExcel->getActiveSheet();
   
       //设置当前活动的sheet的名称
       $title = isset($hotel_name)? $hotel_name.'-'.date('Ymd').'OMS' : date('Ymd').'OMS' ;
       $objActSheet->setTitle($title);
   
       //dump($expTableData);exit;
       $objPhpExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
       $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
       $objPhpExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
   
       if($expTableData){
           foreach($expTableData as $k => $v)
           {
               $num=$k+2;
               $objPhpExcel->setActiveSheetIndex(0)
               ->setCellValue('A'.$num, $v['user_id'])
               ->setCellValue('B'.$num, ' '.$v['serial_no'])
               ->setCellValue('C'.$num, ' '.$v['device_code'])
               ->setCellValue('D'.$num, $v['device_mac'])
               ->setCellValue('E'.$num, ' '.$v['group_id']);
           }
       }
       else{
           $this->error('数据源为空，请检查数据源...');
           exit;
       }
   
       $name = $title ;//date('Y-m-d');//设置文件名
       header("Content-Type: application/force-download");
       header("Content-Type: application/octet-stream");
       header("Content-Type: application/download");
       header("Content-Transfer-Encoding:utf-8");
       header("Pragma: no-cache");
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.urlencode($name).'.xls"');
       header('Cache-Control: max-age=0');
       $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel5');
       $objWriter->save('php://output');
       exit;
   }   
   
   public function get_xml($url = ''){
       if($url){
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($ch, CURLOPT_HEADER, 0);
           $data = curl_exec($ch);
           curl_close($ch);       
       }
       else{
           $data = '参数不正确!';
       }
       
      return $data;
   }
   
   public function template_download(){
       header("Content-type:text/html;charset=utf-8");
       $save_name = 'device_template.xlsx';
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
   
   private function mac_validate($mac = ''){
       if($mac){
           //校验mac格式
           $pattern_mac="/[0-9a-f][0-9a-f][:]" . "[0-9a-f][0-9a-f][:]" . "[0-9a-f][0-9a-f][:]" . "[0-9a-f][0-9a-f][:]" . "[0-9a-f][0-9a-f][:]" . "[0-9a-f][0-9a-f]/i";
           $result_mac = preg_match($pattern_mac, $mac);

           if($result_mac==false){
               return FALSE;
           }
           else{
               return TRUE;
           }
       }
       else{
           return FALSE;
       }
   }   
   
}
