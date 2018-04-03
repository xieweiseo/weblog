<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {
    // 框架首页
	 public function index(){
         $this->display();
	 }
	 
	 public function crons(){
	     addlog('定时任务:'.'app...');
	     echo date("Y-m-d H:i:s")."执行定时任务！" . "\r\n<br>";
	 }
	 
	 public function phpinfo(){
	     phpinfo();
	 }
}