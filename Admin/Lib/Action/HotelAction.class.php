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
class HotelAction extends CommonAction {

    public function index() {
        $hotel = M('Hotel');
        $where = '1=1 and status=1 ';  
        $hotel_name = isset($_POST['hotel_name']) ? I('hotel_name','','trim') : '';
        $user_id = isset($_POST['user_id']) ? I('user_id','','trim') : '';
        $device_code = isset($_POST['device_code']) ? I('device_code','','trim') : '';
        $device_mac = isset($_POST['device_mac']) ? I('device_mac','','trim') : '';
        $group_id = isset($_POST['group_id']) ? I('group_id',0,'trim') : '';
        $province = isset($_POST['province']) ? I('province','','trim') : '';
        $city = isset($_POST['city']) ? I('city','','trim') : '';
        
        $start_num = intval(isset($_POST['start_num']) ? I('start_num',0,'trim') : 0);
        $end_num = intval(isset($_POST['end_num']) ? I('end_num',0,'trim') : 0);
        
        if($hotel_name){
            $where .= "and hotel_name like '%{$hotel_name}%' ";
        }
        if ($user_id) {
            $where .= "and user_id='".$user_id."' ";
        }
        if($device_code){
            $where .= "and device_code='".$device_code."' ";
        }
        if($device_mac){
            $hotel_ids = M('Device')->field('hotel_id')->where("device_mac='".$device_mac."' and status=1")->select();
            $ids_list = array();
            if($hotel_ids){
                foreach ($hotel_ids as $k=>$v){
                    $ids_list[$k] = $v['hotel_id'];
                }
            }           
            $where .= "and id in(".implode(',', $ids_list).") ";
        }
        if($group_id){
            $where .= "and group_id='".$group_id."' ";
        }
        if($province){
            $where .= "and province='".$province."' ";
        }
        if($city){
            $where .= "and city='".$city."' ";
        }
        $timegap = I('timegap');
        //dump($timegap);
        if($timegap){
            $gap = explode(' - ', $timegap);
            $begin = $gap[0];
            $end = $gap[1];
            $where .= "and create_date>='".$begin."' and create_date<='".$end." 59:59:59'";
        }                
               
        //dump($where);        
        
        import("ORG.Util.Page");      
        $db = Db::getInstance(C('RBAC_DB_DSN'));
        $prefix = C('DB_PREFIX');     
        $count = $hotel->where($where)->count(); //计算总数
      
        $pagesize = empty($_POST['online'])?180:580;
        $p = new Page($count, $pagesize); 
        $htlist = $hotel->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        //dump($hotel->getLastSql());
        $device = M('device');
        $lists =  array();
        $i = isset($_GET['p'])? intval(I('p'))*$pagesize-$pagesize+1 : 1;
        foreach ($htlist as $k=>$v){
            $lists[$k] = $v;
            $lists[$k]['hid'] = $i;
            $lists[$k]['count'] = $device->where('status in(1,0) and hotel_id='.$v['id'])->count();
            $lists[$k]['ysten_gid'] = $device->where('status in(1) and ysten_gid<>0 and hotel_id='.$v['id'])->group('ysten_gid')->getField('ysten_gid');
            $lists[$k]['online_count'] = $device->where("group_id<>0 and ysten_gid<>0 and group_id=ysten_gid and status in(1) and hotel_id=".$v['id'])->count();
            $i++;
        }

        //dump($lists);
        //$dcount = 0;  //设备数
        //$donline = 0; //上线数
        
  
        //房间数查询
        $online_count = $_POST['online_count'];        
        if($online_count=='rc'){
            if($start_num && $start_num>$end_num){
               //dump('aaaaaaaaaaa');
               foreach ($lists as $k=>$v){
                   if($v['count']>=$start_num){
                           $aa[$k]=$v; 
                           $dcount += $v['count'];
                           $donline += $v['online_count'];
                   }
               }      
            }

            if($end_num && $start_num<$end_num){
                //dump('ccccccccccc');
                foreach ($lists as $k=>$v){
                    if($v['count']<=$end_num && $v['count']>=$start_num){
                        $aa[$k]=$v;
                        $dcount += $v['count'];
                        $donline += $v['online_count'];  
                        //dump('$dcount-->'.$dcount);
                        //dump('$donline-->'.$donline);                        
                    }
                }
            } 
            if($start_num && $end_num && $start_num==$end_num){
                //dump('ddddddddddddd');
                foreach ($lists as $k=>$v){
                    if($v['count']==$end_num || $v['count']==$start_num){
                        $aa[$k]=$v;
                        $dcount += $v['count'];
                        $donline += $v['online_count'];
                    }
                }
            }            
            
        }
        
        //dump($aa);
        
        //上线数查询
        if($online_count=='oc'){ 
            if($start_num && $start_num>$end_num){
                foreach ($lists as $k=>$v){
                    if($v['online_count']==$start_num){
                        $aa[$k]=$v;
                        $dcount += $v['count'];
                        $donline += $v['online_count'];
                    }
                }
            }
            if($start_num && $end_num && $start_num == $end_num){
                foreach ($lists as $k=>$v){
                    if($v['online_count']==$start_num || $v['online_count']==$end_num){
                        $aa[$k]=$v;
                        $dcount += $v['count'];
                        $donline += $v['online_count'];

                    }
                }
            }

            if($end_num && $end_num>$start_num){
                foreach ($lists as $k=>$v){
                    if($v['online_count']<=$end_num && $v['online_count']>=$start_num){
                        $aa[$k]=$v;
                        $dcount += $v['count'];
                        $donline += $v['online_count'];

                    }
                }
            }
        
        }  
        
        
        //dump($aa);
        
        //已上线未上线酒店
        $online = $_POST['online'];
        if($online){
            if($aa){
                $lists = $aa;
                $bb = array();
                unset($aa);
                unset($dcount);
                unset($donline);
                
            }            
            //dump($lists);
            if($online==1){
                foreach ($lists as $k=>$v){
                    if($v['online_count']>0){
                        $bb[$k]=$v;
                        $dcount += $v['count'];
                        $donline += $v['online_count'];
                        
                        //dump('$dcount==>'.$dcount);
                        //dump('$donline==>'.$donline);
                    }
                }
            }
            if($online==2){
                //dump($lists);
                foreach ($lists as $k=>$v){
                    if($v['online_count']==0){
                        $bb[$k]=$v;
                        $dcount += $v['count'];
                        $donline += $v['online_count'];
                    }

                }
            }
             
            $aa = $bb;
        }        
        
        //dump($aa);
        
        //设备总数及上线总数
        $device_count = 0;
        $count_list = $hotel->where($where)->order('id desc')->select();  
        foreach ($count_list as $k=>$v){
            $device_count+= $device->where('status in(0,1) and hotel_id='.$v['id'])->count();
            $device_online+= $device->where("group_id<>0 and ysten_gid<>0 and group_id=ysten_gid and status in(1) and hotel_id=".$v['id'])->count();
        }
        

        //统计总数显示
        $statistics_page = array('total'=>$aa?count($aa):0,'count'=>$dcount?$dcount:0,'online'=>$donline?$donline:0);
        $statistics_show = array('total'=>$count,'count'=>$device_count,'online'=>$device_online);
        $list_info = is_array($aa) ? $aa : $lists;
        $dispage = is_array($aa) ? $statistics_page : $statistics_show;               
        $show_page = $aa ? '' : $p->show();
        
        //四位分组号酒店总数
        $hfc = $hotel->query('select group_id,status from wb_hotel where status=1');
        $four_groupid = 0;
        foreach ($hfc as $k=>$v){
            if(strlen($v['group_id'])==4 && $v['status']=='1'){
                $four_groupid++;
            }
        } 
        
        //dump($lists);exit;
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');

        $this->assign("page", $show_page);
        $this->assign('list', $list_info);        
        $this->assign('info', $dispage);  
        $this->assign('four_groupid',$four_groupid);
        $this->display();
    }
		
    public function add(){
             
		$this->display('insert');
	}
	
	public function edit($id = 0) {
	    $id = intval($id);
	    if($id){
            $hotel = M('Hotel');
    	    $hotel_info = $hotel->where("id=".$id)->find();
    	    $this->assign('vo',$hotel_info);
	    }
	    else{
	        $this->error('参数错误！');
	    }
	    
		$this->display('insert'); 
    }
      
    public function insert(){
        if(IS_POST){
            $id = I('post.id',0,'trim');            
            $data['hotel_name'] = I('post.hotel_name','','strip_tags,trim');
            $data['province'] = isset($_POST['province'])?I('post.province','','strip_tags,trim'):'';
            $data['city'] = isset($_POST['city'])?strtolower(I('post.city','','strip_tags,trim')):'';
            $data['area'] = isset($_POST['area'])?strtolower(I('post.area','','strip_tags,trim')):'';
            $data['description'] = strtolower(I('post.description','','strip_tags,trim'));
            $data['group_id'] = I('post.group_id',0,'trim');
            
            $hotel = M('Hotel');
            $count = $hotel->where("hotel_name='".$data['hotel_name']."' and status in (1)")->count();
            //dump($count);exit;
            if(empty($data['hotel_name'])){
                $this->error('酒店名不能为空！');
            }
            else if(empty($data['group_id'])){
                $this->error('分组不能为空！');
            }
                               
            if(empty($id)){
                if(!empty($count)){
                    $this->error('酒店已存在！');
                }                
                
                //新增
                $data['create_date'] = date('Y-m-d H:i:s');
                $data['status'] = 1;
                $result = $hotel->data($data)->add();
                $hotel_id = $hotel->getLastInsID();
                // 导入设备
                if(!empty($_FILES["upload_excel"][size]) && !empty($hotel_id) && !empty($data['group_id'])){
                    $this->import_device($_FILES, $hotel_id, $data['group_id']);
                }
                if($result!=false){
                    addlog('添加HL:'.$hotel_id);
                    $this->success('恭喜，新增成功！',U('index'));
                }
                else{
                    $this->error('新增失败！');
                }
            }
            else{
                $data['update_date'] = date('Y-m-d H:i:s');
                $result = $hotel->data($data)->where("id=".$id)->save();                  
                //修改导入设备分组
                $device = M('Device');
                $device->data("group_id=".$data['group_id'])->where("hotel_id=".$id)->save();
                if($result!=false){
                    addlog('修改HL:'.$id);
                    $this->success('恭喜，修改成功！',U('index'));
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
        $ids = is_array($getid) ? $getids : $getid;   
        $hotel = M('Hotel');
        $device = M('Device');
        $user_id = M('user_id');
        //dump($ids);exit;
        if($ids){
            $map = "id in (".$ids.")";
            //$hotel_result = $hotel->where($map)->delete();  
            $hotel_result = $hotel->where($map)->data('status=-1')->save();
            //echo $hotel->getLastSql();exit;
            if($hotel_result!=false){
                $device->where("hotel_id in (".$ids.")")->data('status=-1')->save();
                addlog('删除HL:'.$ids);
                
                //删除user_id表中数据
                $uid_list = $device->field('user_id')->where("hotel_id in(".$ids.")")->select();
                foreach ($uid_list as $val){
                    $uids[] = $val['user_id'];
                }
                $user_list = implode(',', $uids);  
                
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
    
    //导入酒店文件
    public function import(){   
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

               //dump($ExlData);exit;
               $data = $this->importExcel_RAW($ExlData);                      
               if ($data>0) { 
                   $this->success("恭喜，酒店导入".$data."条！",U('index'));     
               }
               else if($data==0){
                   $this->success("酒店成功导入".$data."条！",U('index'));
               }
               else { 
                   $this->error("导入失败，原因可能是表格格式错误！");// 提示错误                          
               }     
           }      
       }
       else{
           $this->display();
       }
   }
   
   //导入酒店数据
   public function  importExcel_RAW($ExlData, $hotel_id = 0){   // 将导入表中的数据添加到  数据库数组中去  
       $hotel = M('Hotel');
       $create_time = date('Y-m-d H:i:s');
       //dump($ExlData);exit;
       //dump(sizeof($ExlData));exit;
       $import_number = 0;
       //验证不能为空       
       for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
           if(preg_match("/^[\x{2460}-\x{2468}]+$/u",$ExlData[$i]['A'])){
               $this->error("第:".$i++."行格式不正确xx!");
           }
           if(empty($ExlData[$i]['A'])){
               $this->error("第:".$i++."行 hotel_name不能为空!");
           }
           if(empty($ExlData[$i]['B'])){
               $this->error("第:".$i++."行 group_id不能为空!");
           }
           if(empty($ExlData[$i]['C'])){
               $this->error("第:".$i++."行 province不能为空!");
           }
           if(empty($ExlData[$i]['D'])){
               $this->error("第:".$i++."行 city不能为空!");
           }

       }
       //dump($ExlData);exit;
       for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){  
           $dataList[] = array(
               'hotel_name'=>trim($ExlData[$i]['A']),
               'group_id'=> trim($ExlData[$i]['B']),
               'province'=>trim($ExlData[$i]['C']),   
               'city'=>strtolower(trim($ExlData[$i]['D'])),                  
               'status'=>1,                  
               'create_date'=>$create_time,
           );
           try{
               //不重复添加
               $map['hotel_name'] = trim($dataList[$j]['hotel_name']);
               $hotel_count = $hotel->where($map)->count();
               //dump($device_count);exit;
               if(empty($hotel_count)){
                  $hotel_id = $hotel->data($dataList[$j])->add();
                  $import_number++;
               }
               
           }catch (\Exception $e){
               echo $e->getMessage();
           }
          
       }
       
       //dump($import_number);exit;
       
       return $import_number;  
   }   

   //导入设备
   public function import_device($files,$hotel_id,$group_id){  
       $import_number = 0;
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
       if(!$info) {
           $this->error('请上传导入文件！');
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

           $PHPExcel = $objReader->load($filename);                     // 载入文件
           //dump($PHPExcel);exit;

           $currentSheet = $PHPExcel->getSheet(0);                      
           $allColumn = $currentSheet->getHighestColumn();              // 获取总列数
           $allRow = $currentSheet->getHighestRow();                    // 获取总行数
           for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) { 
               for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
                   $address = $currentColumn . $currentRow;             // 数据坐标
                   $ExlData[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
               }
           }
           
           //添加到wb_device和wb_user_id中
           $device = M('Device');
           $user_id = M('user_id');
           $hotel = M('hotel');
           $create_time = date('Y-m-d H:i:s');
           //dump(sizeof($ExlData));exit;           
           //验证不能为空
           for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
               if(empty($ExlData[$i]['A'])){
                   $hotel->where('id='.$hotel_id)->delete();
                   $this->error("设备导入格式不正确！");
               }
               if(preg_match("/([\x{4e00}-\x{9fa5}])/u",$ExlData[$i]['A'])){
                   $hotel->where('id='.$hotel_id)->delete();
                   $this->error("设备导入格式不正确！");
               }
               if(stripos($ExlData[$i]['A'], 'E+')){
                   $hotel->where('id='.$hotel_id)->delete();
                   $this->error("设备导入格式不正确！");
               }
           }
            
           for($i = 2,$j=0;$i<=sizeof($ExlData)+1;$i++,$j++){
               $dataList[] = array(
                   'user_id' =>trim($ExlData[$i]['A']),
                   'hotel_id' => intval($hotel_id),
                   'group_id' =>$group_id,
                   'create_date'  =>trim($create_time),
                   'at_time'  =>trim($create_time),
                   'status' => 0
               );
               try{
                   //不重复添加
                   $userid = trim($dataList[$j]['user_id']);
                   $hotel_id = intval($hotel_id);
                   $device_count = $device->where("user_id='".$userid."' and status in(1)")->count();
                   $user_id_count = $user_id->where('user_id='.$userid)->count();
                   //dump($device_count);exit;
                   if(empty($device_count)){
                       $device_info_id = $device->data($dataList[$j])->add();
                       if(empty($user_id_count)){
                           $user_info_id = $user_id->data($dataList[$j])->add();
                       }
                        
                       $import_number++;
                   }
       
               }catch (\Exception $e){
                   echo $e->getMessage();
               }
       
           }
       }
       
       return $import_number;
   }
   
   public function template_download(){
        header("Content-type:text/html;charset=utf-8");
        $save_name = 'hotel_template.xlsx';
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
