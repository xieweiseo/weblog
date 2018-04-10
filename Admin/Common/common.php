<?php
/***********************************************************
    [w3note] (C)2012 w3note
	
	@function 后台函数库
	
    @Filename common.php $

    @Author WBlog $(http://www.w3note.com)

    @Date 2012-6-23 20:25:11 $
*************************************************************/
/**
* 转换字节数为其他单位
*/
function sizecount($filesize) {
	if ($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
	} elseif ($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize.' Bytes';
	}
	return $filesize;
}
//删除目录函数
function deldir($dirname){
	if(file_exists($dirname)){
		$dir = opendir($dirname);
		while($filename = readdir($dir)){
		 if($filename != "." && $filename != ".."){
			$file = $dirname."/".$filename;
			if(is_dir($file)){
				deldir($file); //使用递归删除子目录	
			}else{
			  @unlink($file);
			}
		  }
	    }
			closedir($dir);
			rmdir($dirname);
	}
}
//判断文件夹是否为空，空则返回真
function empty_dir($directory){

$handle = opendir($directory);
while (($file = readdir($handle)) !== false){

if ($file != "." && $file != ".."){

closedir($handle);
return false;
}
}
closedir($handle);
return true;
} 

//加密算法
function pwdHash($password) {
	 return md5(md5(trim($password)).C('KEYCODE'));
	 //return md5(trim($password));
}
	

//返回目录的大小
function dirSize($directory) {     	
		$dir_size=0;              	

		if($dir_handle=@opendir($directory)) {           		
			while($filename=readdir($dir_handle)) {      		
				if($filename!="." && $filename!="..") {  	
				    $subFile=$directory."/".$filename;   	
					if(is_dir($subFile))               	
						$dir_size+=dirSize($subFile);   
					if(is_file($subFile))               	
						$dir_size+=filesize($subFile);  
				}
			}
			closedir($dir_handle);                    
			return $dir_size;                        
		}
	}
//返回彩色的字符
function color_txt($str){

    if(function_exists('iconv_strlen')) {
    	$len  = iconv_strlen($str);
    }else if(function_exists('mb_strlen')) {
    	$len = mb_strlen($str);
    }
    $colorTxt = '';
    for($i=0; $i<$len; $i++) {
               $colorTxt .=  '<span style="color:'.rand_color().'">'.msubstr($str,$i,1,'utf-8','').'</span>';
     }

    return $colorTxt;
}
//随机获取颜色
function rcolor() {
$rand = rand(0,255);
return sprintf("%02X","$rand");
}
function rand_color(){

	return '#'.rcolor().rcolor().rcolor();
}
function getTitleSize($count){

    $size = (ceil($count/10)+11).'px';
    return $size;
}

//截取字符串
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr")) {
      if($suffix) {
   
         if($str==mb_substr($str, $start, $length, $charset)) { 
        
            return mb_substr($str, $start, $length, $charset); 
         }
         else {
        
            return mb_substr($str, $start, $length, $charset)."..."; 
         }  
    }
        else  { 

     return mb_substr($str, $start, $length, $charset);
    }
    }
    elseif(function_exists('iconv_substr')) {
      if($suffix) {
   
         if($str==iconv_substr($str,$start,$length,$charset))  {
        
            return iconv_substr($str,$start,$length,$charset); 
         }
         else {
        
            return iconv_substr($str,$start,$length,$charset)."..."; 
         } 
     }
        else { 
 
     return iconv_substr($str,$start,$length,$charset);
    }
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

function str_cut($string, $length, $dot = '...'){
    //截字符串函数    GBK,UTF8
    $charset = 'utf-8';

    if(strlen($string) <= $length)
    {   //边界条件
        return $string;
    }

    $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if(strtolower($charset) == 'utf-8') {
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {

            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t <= 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }

            if($noc >= $length) {
                break;
            }
        }
        if($noc > $length)
        {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);
        $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);

    } else{

        for($i = 0; $i < $length; $i++)
        {
            $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        }
    }
    $strcut = str_replace(array('&', '"', '<', '>'), array('&', '"', '<', '>'), $strcut);
    return $strcut.$dot;
}
function is_badword($string) {
	$badwords = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
	foreach($badwords as $value){
		if(strpos($string, $value) !== FALSE) {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * 检查用户名是否符合规定
 */
function is_username($username) {
	$strlen = strlen($username);
	if(is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
		return false;
	} elseif ( 20 < $strlen || $strlen < 2 ) {
		return false;
	}
	return true;
}


/**
 * 检查密码长度是否符合规定
 */
function is_password($password) {
	$strlen = strlen($password);
	if($strlen >= 6 && $strlen <= 20) return true;
	return false;
}

function create_keycode($lenth) {
	return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function addlog($log, $name = false)
{
    $Model = M('log');
    if (!$name) {
        session_start();
        $id = $_SESSION[C('USER_AUTH_KEY')];
        if ($id) {
            $user = M('user')->field('username')->where(array('id' => $id))->find();
            $data['name'] = $user['username'];
        } else {
            $data['name'] = '';
        }
    } else {
        $data['name'] = $name;
    }
    $data['t'] = time();
    $data['ip'] = $_SERVER["REMOTE_ADDR"];
    $data['log'] = $log;
    $Model->data($data)->add();
}


//通过电视账号同步mac
function synchro_userid()
{
    $device = M('device');
    $user_id = M('user_id');
    
    $device_list = $device->field('user_id')->where("device_code='' and device_mac=''")->order('id desc')->select();
    
    if(!empty($device_list)){
        foreach ($device_list as $k=>$v){
            $uid[] = $v['user_id'];
        }
        $user_ids = implode(',', $uid);
        
        //dump($user_ids);exit;
        $where = "user_id in (".$user_ids.") and device_code<>'' and device_mac<>''";
        $user_id_list = $user_id->field('user_id,device_code,device_mac')->where($where)->order('user_id desc')->select();
        
        //dump($user_id_list);exit;
        foreach ($user_id_list as $k=>$v){
            $map['device_code'] = $v['device_code'];
            $map['device_mac'] = $v['device_mac'];
            $map['status'] = 1;
            $device->data($map)->where("device_code='' and device_mac='' and user_id='".$v['user_id']."'")->save();
            //dump($device->getLastSql());
        }
    }
   
    //通过mac同步user_id,device_code
    $device_list = $device->field('device_mac')->where("device_code='' and device_mac<>''")->order('id desc')->select();
    if(!empty($device_list)){
        foreach ($device_list as $k=>$v){
            $mac[] = "'".$v['device_mac']."'";
        }
        $mac_ids = implode(',', $mac);    
        
        //dump($mac_ids);exit;
        $device_sql = "device_mac in (".$mac_ids.") and device_code<>'' and user_id<>'' and status=1";        
        $mac_lists = $user_id->field('user_id,device_code,device_mac')->where($device_sql)->order('id desc')->select(); 
   }
   foreach ($mac_lists as $k=>$v){
       $map['device_code'] = $v['device_code'];
       $map['user_id'] = $v['user_id'];
       $map['status'] = 1;
       $device->data($map)->where("user_id='' and device_code='' and device_mac='".$v['device_mac']."'")->save();
       //dump($device->getLastSql());
   }   
   
   //通过device_code同步user_id,mac
   $decode_list = $device->field('device_code')->where("device_code<>'' and device_mac='' and user_id=''")->order('id desc')->select();
   if(!empty($decode_list)){
       foreach ($decode_list as $k=>$v){
           $decode[] = "'".$v['device_code']."'";
       }
       //dump($decode);exit;
       $decode_ids = implode(',', $decode);
   
       //dump($decode_ids);exit;
       $device_sql = "device_code in (".$decode_ids.") and user_id<>'' and device_mac<>'' and status=1";
       $decode_lists = $user_id->field('user_id,device_code,device_mac')->where($device_sql)->order('id desc')->select();
   }
   //dump($decode_lists);exit;
   foreach ($decode_lists as $k=>$v){
       $map['device_mac'] = $v['device_mac'];
       $map['device_code'] = $v['device_code'];
       $map['user_id'] = $v['user_id'];
       $map['status'] = 1;
       $device->data($map)->where("device_code='".$v['device_code']."'")->save();
       //dump($device->getLastSql());
   }  

}
?>