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
class BlogAction extends CommonAction {

    // 首页
    public function index() {
        $this->lists();
        $this->display();
    }
	
    public function insert(){
		     $this->getinsert();
			 $this->display(); 			
		  }

    public function add(){
			$this->addata($this->getActionName());
		}
	
	 // 编辑数据
	 public function edit() {
           $this->getedit();
		   $this->display(); 
      }
	  public function act(){
		$this->action();
	}
	 
	  
      public  function update() {
          $this->getupdate();
      }
	
   }

?>