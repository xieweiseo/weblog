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
class InterfaceAction extends CommonAction {

    public function index() {
        
        $this->display('index');
    }

    function save(){
        $data = $_POST;
        $file = $data['file'];

        //dump($data);exit;
        if($file=="interface_config.inc.php"){
            if($data["device_code"]=='')$data["device_code"]='http://tms.fja.bcs.ottcn.com:8080/yst-tms/deviceInit.action?deviceId=&mac=' ;
            if($data["service_set"]=='')$data["service_set"]='http://bimsboot.fja.bcs.ottcn.com:8081/yst-bims-facade/stb/bootstrap.xml?&deviceId=&mac=&ystenid=&kuridecode' ;
            if($data["panel"]=='')$data["panel"]='http://panel.fjfz.cm.sllhtv.com/yst-bims-panel-api/panel/2.0/data_' ;
        }
    
        //dump($db);exit;
        $content = "<?php\r\n//接口参数配置\r\nreturn array(\r\n";
        //获取数组
        foreach ($data as $key=>$value){
            $key=strtoupper($key);
            if(strtolower($value)=="true" || strtolower($value)=="false" || is_numeric($value))
                $content .= "\t'$key'=>$value, \r\n";
            else
                $content .= "\t'$key'=>'$value',\r\n";
    
            C($key,$value);
        }
        $content .= ");\r\n?>";
    
        //dump($content);exit;
        $r = @chmod($file,0777);
        $r_db = @chmod($file_db,0777);
        $hand= file_put_contents(CONF_PATH.'interface_config.inc.php',$content);
        if (!$hand) {
            $this->error($file.'保存失败！');
        }else{
            $cachefile = RUNTIME_PATH.'~runtime.php';
            if(is_file($cachefile)) $result = unlink($cachefile);
            if($result){
                $this->success('文件保存成功!更新成功!');
            }else{
                $this->success('文件保存成功!');
            }
        }
    }
    
}
