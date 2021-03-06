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
class ReportAction extends CommonAction {
    public $begin;
    public $end;
    public function _initialize(){
        $timegap = I('timegap');
        if($timegap){
            $gap = explode(' - ', $timegap);
            $begin = $gap[0];
            $end = $gap[1];
        }else{
            $lastweek = '2017-12-11';//date('Y-m-d',strtotime("-1 month"));//30天前
            $begin = I('begin',$lastweek);
            $end =  I('end',date('Y-m-d'));
        }
        $this->begin = strtotime($begin);
        $this->end = strtotime($end)+86399;
        $this->assign('timegap',date('Y-m-d',$this->begin).' - '.date('Y-m-d',$this->end));
    }
    
    public function index() {       
        $hotel = M('Hotel');
        $device = M('Device');
        $online_soft = M('wb_user_online_soft');
        
        $timegap = I('timegap');
        $where = '1=1 and ';
        if($timegap){
            $gap = explode(' - ', $timegap);
            $begin = $gap[0];
            $end = $gap[1];
            $where .= "create_date>='".$begin." 00:00:00' and create_date<='".$end." 23:59:59' and ";
        }
        //dump($where);
        
        //酒店统计
        $sql_hotel = "select id,count(*) as hotel_count ,province, city, DATE_FORMAT(create_date,'%Y-%m-%d') as create_date  from wb_hotel where ".$where."status<>-1 group by DATE_FORMAT(create_date,'%Y-%m-%d') order by create_date asc";        
        $hotel_info = $hotel->query($sql_hotel);   
        
        //设备统计
        $sql_device = "select count(*) as device_count , DATE_FORMAT(create_date,'%Y-%m-%d') as create_date from wb_device where status<>-1 group by DATE_FORMAT(create_date,'%Y-%m-%d') order by create_date asc";
        $b = "select count(*) as device_count,DATE_FORMAT(a.create_date,'%Y-%m-%d') as create_date from wb_hotel as a left join wb_device as b  on a.id=b.hotel_id where a.status in(1) and b.status in(0,1) group by DATE_FORMAT(a.create_date,'%Y-%m-%d') order by a.create_date asc";
        
        $device_info = $device->query($b);  
        
        //上线设备统计
        $sql_device_online = "select count(*) as device_count_online , DATE_FORMAT(create_date,'%Y-%m-%d') as create_date from wb_device where device_mac<>'' and group_id=ysten_gid and status<>-1 group by DATE_FORMAT(create_date,'%Y-%m-%d') order by create_date asc";
        $c = "select count(*) as device_count_online ,DATE_FORMAT(a.create_date,'%Y-%m-%d') as create_date from wb_hotel as a left join wb_device as b  on a.id=b.hotel_id where a.status in(1) and b.status in(1) and b.device_mac<>'' and b.device_code<>'' and b.group_id=b.ysten_gid group by DATE_FORMAT(a.create_date,'%Y-%m-%d') order by a.create_date asc";
        $device_online = $device->query($c);
        
        //统计数据
        $date_time = array();
        $hotel_list = array();
        $device_count = array();
        $device_count_online = array();        
        foreach ($hotel_info as $hk=>$hv){
            $date_time[] = $hv['create_date'];
            //$date_list_info[] = '"'.$hv['create_date'].'"';
            $hotel_list[$hv['create_date']] = $hv['hotel_count'];
            foreach ($device_info as $dk=>$dv){               
                if($hv['create_date']==$dv['create_date']){
                    $device_count[$dv['create_date']] = $dv['device_count'];
                }
            }
            foreach($device_online as $ok=>$ov){
                if($hv['create_date']==$ov['create_date']){
                    $device_count_online[$ov['create_date']] = $ov['device_count_online'];
                }
            }
        }

        
        //开机日期
        $online_sql = 'select date from wb_user_online_soft group by date';
        $online_list = $online_soft->query($online_sql);
        $online_lists = array();
        foreach($online_list as $val){
            $online_lists[] = substr($val['date'], 0,4).'-'.substr($val['date'], 4,2).'-'.substr($val['date'], 6,2);
        }
        $date_list = array_unique(array_merge($date_time,$online_lists));  
        asort($date_list);

        //设备数
        foreach ($date_list as $k=>$v){
            if($device_count[$v]){
                $device_counts[$v] = $device_count[$v];              
            }
            else{
                 $device_counts[$v] = '0';
            }
        }
        
        //设备上线数
        foreach ($date_list as $k=>$v){
            if($device_count_online[$v]){
                $device_count_onlines[$v] = $device_count_online[$v];
            }
            else{
                $device_count_onlines[$v] = '0';
            }
        }
        
        //酒店数
        foreach ($date_list as $k=>$v){
            if($hotel_list[$v]){
                $hotel_lists[$v] = $hotel_list[$v];
            }
            else{
                $hotel_lists[$v] = '0';
            }
        }        

          //soft开机数               
          //$Cache = Cache::getInstance('Xcache',array('expire'=>'3600'));
          //$trunon_data = $Cache->get('trunon');
          $redis = new \Redis(); 
          $redis->connect(C("REDIS_HOST"),C("REDIS_PORT"));  
          $trunon_data = $redis->get("trunon"); 
          //if(!$trunon_data){
          if(!$redis->exists('trunon')){
            $sql_turnon = "select date,count(distinct mac) as count  from wb_user_online_soft  group by date";
            $turnon_list = $online_soft->query($sql_turnon);
            //dump($turnon_list);
            $turnon_lists = array();
            $turn_on_list = array();
            foreach ($turnon_list as $k=>$v){
                $turnon_lists[$v['date']] = $v['count'];
            }
            foreach($date_list as $k=>$v){
                $vv = str_replace('-', '',$v);
                if($turnon_lists[$vv]){
                    $turn_on_list[$v] = $turnon_lists[$vv];
                    $redis->set('trunon',json_encode($turn_on_list),18000);
                    
                }
                else{
                    $turn_on_list[$v] = '0';
                    $redis->set('trunon',json_encode($turn_on_list),18000);
                }
            }        
             //$Cache->set('trunon',$turn_on_list);
         }
         else{ 
             $turn_on_list = json_decode($trunon_data,TRUE);  //$trunon_data;
         }
         
        //dump($turn_on_list);exit; 
        
        foreach ($date_list as $k=>$v){
            $aa[$k] = '"'.$v.'"';
        }
        //dump($aa);exit;
        
        //统计曲线条件筛选
        $begin_time = isset($begin)?$begin:'2017-12-11';
        $end_time = isset($end)?$end:date('Y-m-d');
        foreach ($this->prDates($begin_time, $end_time) as $k=>$v){
            $bb[] = '"'.$v.'"';
            $hcount[$v] = isset($hotel_lists[$v])?$hotel_lists[$v]:'0';
            $dcount[$v] = isset($device_counts[$v])?$device_counts[$v]:'0';
            $oncount[$v] = isset($device_count_onlines[$v])?$device_count_onlines[$v]:'0';
            $turncount[$v] = isset($turn_on_list[$v])?$turn_on_list[$v]:'0';
        }
        //dump($bb);
        $date_lists = implode(',', $bb);
        $hotel_lists = implode(',', $hcount);
        $device_lists = implode(',', $dcount);
        $device_online_lists = implode(',', $oncount);
        $turn_on_lists = implode(',', $turncount); 
        
        //$date_lists = implode(',', $aa);
        //$hotel_lists = implode(',', $hotel_lists);
        //$device_lists = implode(',', $device_counts);
        //$device_online_lists = implode(',', $device_count_onlines);
        //$turn_on_lists = implode(',', $turn_on_list);
        
        //dump($turn_on_lists);exit;
        
        //统计数据列表显示
        $list = array();
        $hotel_total = 0;
        $device_total = 0;
        $devcicd_onlien_total = 0;
        foreach ($hotel_info as $hk=>$hv){
            $list[$hk]['create_date'] = $hv['create_date'];
            $list[$hk]['hotel_count'] = $hv['hotel_count'];            
            $list[$hk]['device_count'] = $device_counts[$hv['create_date']];
            $list[$hk]['device_online'] = $device_count_onlines[$hv['create_date']];  
            
            $hotel_number+= intval($hv['hotel_count']); //酒店总数
            $device_total+= intval($device_counts[$hv['create_date']]); //设备总数
            $device_online_total+= intval($device_count_onlines[$hv['create_date']]);//上线总数
        } 
        
        //$list = array_reverse($list);
        
        $hotel_infos = array('hotel_number'=>$hotel_number,'device_total'=>$device_total,'device_online_total'=>$device_online_total);
        $this->assign('hotel_info',$hotel_infos);
        //$this->assign('date_lists',implode(',', $date_list_info));
        $this->assign('date_lists',$date_lists);
        $this->assign('hotel_lists',$hotel_lists);
        $this->assign('device_lists',$device_lists);
        $this->assign('device_online_lists',$device_online_lists);
        $this->assign('turn_on_lists',$turn_on_lists);
              
        //统计列表分页数据     
        $sql_hotel = "select id,count(*) as hotel_count ,province, city, DATE_FORMAT(create_date,'%Y-%m-%d') as create_date  from wb_hotel where ".$where."status<>-1 group by DATE_FORMAT(create_date,'%Y-%m-%d') order by create_date desc";
        $count = count($hotel->query($sql_hotel));        
        
        import("ORG.Util.Page");
        $pagesize = 12;
        $p = new Page($count, $pagesize);             
        $a = "select id,count(*) as hotel_count ,province, city, DATE_FORMAT(create_date,'%Y-%m-%d') as create_date  from wb_hotel where ".$where."status<>-1 group by DATE_FORMAT(create_date,'%Y-%m-%d') order by create_date desc limit ".$p->firstRow . ',' . $p->listRows;
        $hotel_info = $hotel->query($a);
                    
        //设备统计
        $sql_device = "select count(*) as device_count , DATE_FORMAT(create_date,'%Y-%m-%d') as create_date from wb_device where status<>-1 group by DATE_FORMAT(create_date,'%Y-%m-%d') order by create_date asc";
        $b = "select count(*) as device_count,DATE_FORMAT(a.create_date,'%Y-%m-%d') as create_date from wb_hotel as a left join wb_device as b  on a.id=b.hotel_id where a.status in(1) and b.status in(0,1) group by DATE_FORMAT(a.create_date,'%Y-%m-%d') order by a.create_date desc";       
        $device_info = $device->query($b);        
        //上线设备统计
        $sql_device_online = "select count(*) as device_count_online , DATE_FORMAT(create_date,'%Y-%m-%d') as create_date from wb_device where device_mac<>'' and group_id=ysten_gid and status<>-1 group by DATE_FORMAT(create_date,'%Y-%m-%d') order by create_date desc";
        $c = "select count(*) as device_count_online ,DATE_FORMAT(a.create_date,'%Y-%m-%d') as create_date from wb_hotel as a left join wb_device as b  on a.id=b.hotel_id where a.status in(1) and b.status in(1) and b.device_mac<>'' and b.device_code<>'' and b.group_id=b.ysten_gid group by DATE_FORMAT(a.create_date,'%Y-%m-%d') order by a.create_date desc";
        $device_online = $device->query($c);
        
        //dump($hotel_info);exit;
        
        
        //统计数据
        //$date_list = array();
        $hotel_list = array();
        $device_count = array();
        $device_count_online = array();
        foreach ($hotel_info as $hk=>$hv){
            //$date_list[] = $hv['create_date'];
            $date_list_info[] = '"'.$hv['create_date'].'"';
            $hotel_list[$hv['create_date']] = $hv['hotel_count'];
            foreach ($device_info as $dk=>$dv){
                if($hv['create_date']==$dv['create_date']){
                    $device_count[$dv['create_date']] = $dv['device_count'];
                }
            }
            foreach($device_online as $ok=>$ov){
                if($hv['create_date']==$ov['create_date']){
                    $device_count_online[$ov['create_date']] = $ov['device_count_online'];
                }
            }
        }
        
        //dump($date_list);exit;
        //设备数
        foreach ($date_list as $k=>$v){
            if($device_count[$v]){
                $device_counts[$v] = $device_count[$v];
            }
            else{
                $device_counts[$v] = '0';
            }
        }
        
        //设备上线数
        foreach ($date_list as $k=>$v){
            if($device_count_online[$v]){
                $device_count_onlines[$v] = $device_count_online[$v];
            }
            else{
                $device_count_onlines[$v] = '0';
            }
        }
        
        //统计数据列表显示
        $list = array();
        $hotel_total = 0;
        $device_total = 0;
        $devcicd_onlien_total = 0;
        foreach ($hotel_info as $hk=>$hv){
            $list[$hk]['create_date'] = $hv['create_date'];
            $list[$hk]['hotel_count'] = $hv['hotel_count'];
            $list[$hk]['device_count'] = $device_counts[$hv['create_date']];
            $list[$hk]['device_online'] = $device_count_onlines[$hv['create_date']];
            $list[$hk]['turnon_count'] = $turn_on_list[$hv['create_date']];
        }              
        
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');       
        $this->assign("page", $p->show());        
        $this->assign('list',$list);

                
        $this->display();
        
    }
    
    //单个酒店每日开机量统计
    public function single_hotel(){      
        $hotel_name = I('hotel_name') ? I('hotel_name','','strip_tags,trim') : '';
        $turnon_date = I('turnon_date') ? I('turnon_date','','strip_tags,trim') : date('Y-m-d'); //date('Y-m-d',strtotime(date('Y-m-d').'-2 day'));
        
        //dump($turnon_date);exit;
        $device = M('Device');
        $uos= M('user_online_soft');
        $hotel = M('hotel');
        $where = 'status=1 ';
        if($hotel_name){
            $where .= "and hotel_name like '%".$hotel_name."%' ";
        }

        //dump($hotel_ids);exit;
        
        //开机总数//------------------------------------------------------->
        $redis = new \Redis();
        $redis->connect(C("REDIS_HOST"),C("REDIS_PORT"));
        $turn_on_total= S("turn_on_total".$turnon_date.$hotel_name);
        $hotel_num_total= S("hotel_num_total".$turnon_date.$hotel_name);
        if(!$turn_on_total){
            $hotel_ids = $hotel->field('id,hotel_name')->where($where)->select();
            foreach ($hotel_ids as $k=>$v){
                $lists = $device->field('id,user_id,device_code,device_mac')->where("hotel_id=".$v['id']." and (status=1 or status=0)")->select();
                $device_list[$v['hotel_name']] = $lists;
                $device_count[$v['hotel_name']] = count($lists);
            }
            foreach ($device_list as $kv=>$vv){
                if(is_array($vv)){
                    foreach($vv as $ak=>$av){
                        $list[$kv] .= !empty($av['device_code'])?"'".$av['device_code']."',":null;
                    }
                }
                else{
                    $list[$kv] = null;
                }
            }
            $turn_on_total = 0; //开机设备总数
            $hotel_num_total = 0; //开机酒店总数
            foreach ($list as $tk=>$tv){
                $data[$tk]['date'] = str_replace('-', '', $turnon_date); //日期搜索
                $map = "date='".$data[$tk]['date']."' and device_id in (".rtrim($tv,',').")";
                $result = $uos->where($map)->count('distinct device_id');
                $data[$tk]['hotel_name'] = $tk;
                $data[$tk]['decount']= $device_count[$tk];
                $data[$tk]['tocount'] = empty($result)?0 : $result;
                $turn_on_total+= $data[$tk]['tocount'];
                
                if($result){
                    $hotel_num_total = $hotel_num_total+1;
                }
            }
            $expire_time = 3600;
            S('turn_on_total'.$turnon_date.$hotel_name,json_encode($turn_on_total),$expire_time);
            S('hotel_num_total'.$turnon_date.$hotel_name,json_encode($hotel_num_total),$expire_time);
        }
        //end//-------------------------------------------------------------->
        
        //dump($hotel_num_total);
        
        //每个酒店下的设备
        import("ORG.Util.Page");
        $count = $hotel->where($where)->count(); //计算总数
        $pagesize = 22;
        $p = new Page($count, $pagesize);        
        
        $hotel_ids = $hotel->field('id,hotel_name')->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();        
        
        foreach ($hotel_ids as $k=>$v){
            $lists = $device->field('id,user_id,device_code,device_mac')->where("hotel_id=".$v['id']." and (status=1 or status=0)")->select();
            $device_list[$v['hotel_name']] = $lists;
            $device_count[$v['hotel_name']] = count($lists);
        }
        //dump($device_count);
        //dump($device_list);exit;
        foreach ($device_list as $kv=>$vv){
            if(is_array($vv)){
                foreach($vv as $ak=>$av){
                        $list[$kv] .= !empty($av['device_code'])?"'".$av['device_code']."',":null;
                }
            }
            else{
                $list[$kv] = null;
            }
        }
        //dump($a);exit;
        //dump($list);
        //dump($turnon_date);
        //dump(str_replace('-', '', $turnon_date));exit;
        foreach ($list as $tk=>$tv){
            $data[$tk]['date'] = str_replace('-', '', $turnon_date); //日期搜索
            $map = "date='".$data[$tk]['date']."' and device_id in (".rtrim($tv,',').")";  
            $result = $uos->where($map)->count('distinct device_id');
            $data[$tk]['hotel_name'] = $tk;
            $data[$tk]['decount']= $device_count[$tk];
            $data[$tk]['tocount'] = empty($result)?0 : $result;
            //dump($tv);
            //dump($uos->getLastSql());
        }
        
//         $count = 0;
//         foreach ($hotel_ids as $hk=>$hv){
//             $device_list = $device->field('device_code')->where('hotel_id='.$hv['id'].' and status=1 or status=0')->select();
            
//             //dump($device->getLastSql());
//             $data[$hk]['date'] = '20180319';
//             $data[$hk]['hotel_name'] = $hv['hotel_name'];           
//             foreach ($device_list as $k=>$v){
//                 $map['device_id'] = $v['device_code'];
//                 $map['date'] = $data[$hk]['date'];   
//                 $istrue = $uos->where($map)->count();
//                 dump($uos->getLastSql());   
//                 if($istrue){
//                     $count++;
//                 }
//             }
//             $data[$hk]['trun_on_number'] = $count;
            
//         }
        
        //dump($data);
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');        
        $this->assign("page", $p->show());        
        $this->assign('data',$data);
        $this->assign('turnon_date',$turnon_date);
        $this->assign('turn_on_total',$turn_on_total);
        $this->assign('hotel_num_total',$hotel_num_total);
        
        $this->display('single');
        
    }
    
    //设备panel点击量    
    public function device_click01(){
        $hotel_name = I('hotel_name','','trim')? I('hotel_name','','trim') : '';
        $turnon_date = I('turnon_date','','trim') ? I('turnon_date','','strip_tags,trim'): date('Y-m-d');
        //搜索条件
        $map = 'status=1 ';
        if($hotel_name){
            $map .= 'and hotel_name like "%'.$hotel_name.'%" ' ;
        }
               
        $db_config = C('DB_CONFIG');
        $prefix = 'user_panel_click_sum_';         
        $Model = new Model();
        $db = $Model->db(1,$db_config);
        
        $device = M('Device');
        $hotel = M('Hotel');
        import("ORG.Util.Page");
        $count = $hotel->where($map)->count(); //计算总数      
        $pagesize = 11;
        $p = new Page($count, $pagesize);        
        $hotel_list = $hotel->field('id,hotel_name')->where($map)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        //dump($hotel_list);exit;
        
            
        $date = str_replace('-', '', $turnon_date);       
        foreach ($hotel_list as $hk=>$hv){
            $device_list = $device->field('device_code')->where("hotel_id=".$hv['id']." and device_code<>'' and (status=0 or status=1)")->select();
            //dump($device_list);
            foreach($device_list as $v){
                $dlist .= $v['device_code'].",";
            }
         
            //搜索日期
            $preym = $date ?substr($date,0,6):date('Ym');
            $panel_sql =  "select panel_no,block_no,sum(click_count),date from user_panel_click_sum_soft_".$preym." as t where t.date='".$date."' and t.device_id in (".rtrim($dlist,',').") group by panel_no,block_no";
            //dump($panel_sql);
            $panel_list[$hv['hotel_name']] = $db->query($panel_sql);

        }
        
        //dump($panel_list);
        
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        
        $this->assign("page", $p->show());
        $this->assign('turnon_date',$turnon_date);
        $this->assign('list',$panel_list);
        $this->display('panel');
    }
    
    //===============================================
    public function device_click2(){    
        $hotel_name = '香草园'; //I('hotel_name','','trim')? I('hotel_name','','trim') : '';
        $timegap = '2018-03-01 - 2018-03-31' ;//I('turnon_date','','trim') ? I('turnon_date','','strip_tags,trim'): date('Y-m-d'). ' - ' .date('Y-m-d');
        //搜索条件
        $map = 'status=1 ';
        if($hotel_name){
            $map .= 'and hotel_name like "%'.$hotel_name.'%" ' ;
        }
         
        $db_config = C('DB_CONFIG');
        $prefix = 'user_panel_click_sum_';
        $Model = new Model();
        $db = $Model->db(1,$db_config);
        
        $device = M('Device');
        $hotel = M('Hotel');
        import("ORG.Util.Page");
        $count = $hotel->where($map)->count(); //计算总数
        $pagesize = 11;
        $p = new Page($count, $pagesize);
        $hotel_list = $hotel->field('id,hotel_name')->where($map)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        //dump($hotel_list);exit;
        
        if($timegap){
            $gap = explode(' - ', $timegap);
            if(is_array($gap) && count($gap)==2){
                $begin = str_replace('-', '', $gap[0]);
                $end = str_replace('-', '', $gap[1]);
            }
            else{
                $begin = str_replace('-', '', $timegap);
                $end = $begin;
            }
        }
        
        $date_list = $this->prDates($gap[0], $gap[1]);
        
        $data_str = str_replace('-', '', implode(',', $date_list));
        //dump($data_str);
        
        
        //dump($begin.'---'.$end);
        //$date = str_replace('-', '', $turnon_date);
        
        //一个月份一张表，限制只能选择同一个月份的日期
        $ybegin = substr($begin, 4,2);
        $yend = substr($end, 4,2);
        if($ybegin!=$yend){
            $this->error('只能选择同一个月份的日期！');
        }
        
        foreach ($hotel_list as $hk=>$hv){
            $device_list = $device->field('device_code')->where("hotel_id=".$hv['id']." and device_code<>'' and (status=0 or status=1)")->select();
            //dump($device_list);
            foreach($device_list as $v){
                $dlist .= $v['device_code'].",";
            }
             
            //搜索日期
            $preym = $end ?substr($end,0,6):date('Ym');
            $panel_sql =  "select panel_no,block_no,sum(click_count),date from user_panel_click_sum_soft_".$preym." as t where t.date in (".$data_str.") and t.device_id in (".rtrim($dlist,',').") group by panel_no,block_no,date order by date desc,panel_no,block_no ASC";
            //dump($panel_sql);
            $panel_list[$hv['hotel_name']] = $db->query($panel_sql);
        
        }
        
        dump($panel_list);
        $list = $this->getPanel();
        $panel_modle = M('panel');
        $panle_no1 = $panel_modle->field('wb_panel.*,wb_hotel.hotel_name')->join('LEFT JOIN wb_hotel ON wb_hotel.id = wb_panel.hotel_id')->where('wb_panel.pid=0')->select();
        $panle_no2 = $panel_modle->field('wb_panel.*,wb_hotel.hotel_name')->join('LEFT JOIN wb_hotel ON wb_hotel.id = wb_panel.hotel_id')->where('wb_panel.pid<>0')->select();
        
        foreach($panel_list as $k=>$v){
            foreach($v as $kk=>$vv){                
                //在panel里查找对应中文
                foreach($panle_no1 as $pa=>$pb){
                    //查找对应的酒店面板
                    if($pb['hotel_name']==$k && $pb['panel_no']==$vv['panel_no']){
                        dump($pb['panel_no'].'--'.$vv['panel_no'].'-->'.$pb['panel_name']);       
                            //dump($vv['panel_no'.'---'.$pb['panel_name']]);
                            $vv['panel_no'] = $pb['panel_name'];
                            $id = $pb['id'];
                        }
                } 
                
                //查找对应酒店推荐位
                foreach ($panle_no2 as $ba=>$bv){
                    if($bv['hotel_name']==$k && $bv['pid']==$id && $bv['block_no']==$vv['block_no']){
                        $vv['block_no'] = $bv['panel_name'];
                    }
                }
                $panel[$k][] = $vv;
             }
                
        }
        
        dump($panel);
 
        
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        
        $this->assign("page", $p->show());
        $this->assign('turnon_date',$timegap);
        $this->assign('list',$panel);
        $this->display('panel');
    }    
    
    //##############################################
    public function device_click(){
        $hotel_name = I('hotel_name','','trim')? I('hotel_name','','trim') : '';
        $timegap = I('turnon_date','','trim') ? I('turnon_date','','strip_tags,trim'): date('Y-m').'-01'. ' - ' .date('Y-m-d');
        
        //搜索条件
        $map = 'status=1 ';
        if($hotel_name){
            $map .= 'and hotel_name like "%'.$hotel_name.'%" ' ;
        }
         
        $db_config = C('DB_CONFIG');
        $prefix = 'user_panel_click_sum_';
        $Model = new Model();
        $db = $Model->db(1,$db_config);
    
        $device = M('Device');
        $hotel = M('Hotel');
        import("ORG.Util.Page");
        $count = $hotel->where($map)->count(); //计算总数
        $pagesize = 20;
        $p = new Page($count, $pagesize);
        $hotel_list = $hotel->field('id,hotel_name')->where($map)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        //$hotel_list = $hotel->field('id,hotel_name')->where($map)->order('id desc')->select();
        //dump($hotel_list);exit;
    
        if($timegap){
            $gap = explode(' - ', $timegap);
            //dump($gap);exit;
            if(is_array($gap) && count($gap)==2){
                $begin = str_replace('-', '', $gap[0]);
                $end = str_replace('-', '', $gap[1]);
            }
            else{
                $begin = str_replace('-', '', $timegap);
                $end = $begin;
            }
        }
    
        //dump($begin.'---'.$end);exit;
        //$date = str_replace('-', '', $turnon_date);
    
        //一个月份一张表，限制只能选择同一个月份的日期
        $ybegin = substr($begin, 4,2);
        $yend = substr($end, 4,2);
        if($ybegin!=$yend){
            $this->error('只能选择同一个月份的日期！');
        }
    
        $panel_list = '';
        if($hotel_list){           
            foreach ($hotel_list as $hk=>$hv){
                $device_list = $device->field('device_code')->where("hotel_id=".$hv['id']." and device_code<>'' and (status=0 or status=1)")->select();
                //dump($device_list);
                if($device_list){
                    foreach($device_list as $v){
                        $dlist .= $v['device_code'].",";
                    }  
                    //dump($dlist);
                    //搜索日期
                    $preym = $end ?substr($end,0,6):date('Ym');
                    $panel_sql =  "select panel_no,block_no,sum(click_count),date from user_panel_click_sum_soft_".$preym." as t where t.date BETWEEN '".$begin."' and '".$end."' and t.device_id in (".rtrim($dlist,',').") group by panel_no,block_no,date order by date desc,panel_no,block_no ASC";
                    $panel_list[$hv['hotel_name']] = $db->query($panel_sql);
                    //dump($panel_list);
               }         
            }
        }
    
        //dump($panel_list);exit;
        
        //panel推荐位转成对应中文
        $panel = '';
        if($panel_list){
            $panel_modle = M('panel');
            $panle_no1 = $panel_modle->field('wb_panel.*,wb_hotel.hotel_name')->join('LEFT JOIN wb_hotel ON wb_hotel.id = wb_panel.hotel_id')->where('wb_panel.pid=0')->select();
            $panle_no2 = $panel_modle->field('wb_panel.*,wb_hotel.hotel_name')->join('LEFT JOIN wb_hotel ON wb_hotel.id = wb_panel.hotel_id')->where('wb_panel.pid<>0')->select();     
            foreach($panel_list as $k=>$v){
                foreach($v as $kk=>$vv){
                    //在panel里查找对应中文
                    foreach($panle_no1 as $pa=>$pb){
                        //查找对应的酒店面板
                        if($pb['hotel_name']==$k && $pb['panel_no']==$vv['panel_no']){
                            //dump($vv['panel_no'.'---'.$pb['panel_name']]);
                            $vv['panel_no'] = $pb['panel_name'];
                            $id = $pb['id'];
                        }
                    }               
                    //查找对应酒店推荐位
                    foreach ($panle_no2 as $ba=>$bv){
                        if($bv['hotel_name']==$k && $bv['pid']==$id && $bv['block_no']==$vv['block_no']){
                            $vv['block_no'] = $bv['panel_name'];
                        }
                    }
                    $panel[$k][] = $vv;
                }           
            }
        }
              
         //panel推荐位转成对应中文
//         foreach($panel_list as $k=>$v){
//             foreach($v as $kk=>$vv){
//                 $pr = get_panel_list($k, $vv["panel_no"], $vv["block_no"]);
//                 if(is_array($pr) && !empty($pr['panel']) && !empty($pr['recomend'])){
//                     $vv["panel_no"] = $pr['panel'];
//                     $vv["block_no"] = $pr['recomend'];
//                 }
//                 $panel[$k][] = $vv;
//             }
//         }    
                
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
    
        $this->assign("page", $panel?$p->show():'');
        $this->assign('turnon_date',$timegap);
        $this->assign('list',$panel);
        $this->display('panel');
    }    
    
    
    //两个日期之间的所有日期
    private function prDates($start,$end){
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $arr = array();
        while ($dt_start<=$dt_end){
            $arr[] = date('Y-m-d',$dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }
    
        return $arr;
    }
    
    private function getPanel(){
        $lists = M('panel')->field('wb_panel.*,wb_hotel.hotel_name')->join('LEFT JOIN wb_hotel ON wb_hotel.id = wb_panel.hotel_id')->where('wb_panel.hotel_id=79')->order('wb_panel.sort ASC,wb_panel.id DESC')->select();
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
        return $panel_list;
    }
   
}
