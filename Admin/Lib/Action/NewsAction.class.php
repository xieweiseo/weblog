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
class NewsAction extends CommonAction {

    // 首页
    public function index() {
        //$this->lists();
        
        //$Modulename = $this->getActionName();
        $news = M('News');
        $where = '';                 
        $modleid = $this->getmodleid('News');//获取模型ID
        if (isset ($_POST['catid']) && !empty ($_POST['catid'])) {
            $where['catid'] = intval($_POST['catid']);
        }
        
        import("ORG.Util.Page");        
        $count = $news->where($where)->count(); //计算总数
        $pagesize = 12;
        $p = new Page($count, $pagesize);
        $list = $news->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('ord asc,id desc')->select();
        $list = ($recordnum = $this->recordnum($list, 'News')) ? $recordnum : $list;//附加字段        
        $columns = M('Columns')->field('colid,colTitle')->select();
                
        foreach ($list as $ka=>$va){
             $lists[$ka] = $va;
             foreach ($columns as $kb=>$vb){
                 if($va['catid']==$vb['colid']){
                     $lists[$ka]['catname'] = $vb['colTitle'];
                 }
             }
        }
        //dump($lists);exit;
        
        $p->setConfig('header', '条');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        if(in_array('News',array('News','Blog','Download','Picture','Resources'))){
            $this->assign("catid", intval($_POST['catid']));
            $this->assign("catlist", D('Columns')->Catlist('News'));
        }
        $this->assign("page", $p->show());
        $this->assign('tpl', $tpl = "index");//模板标识，用于判断是什么类型的模板以加载相应的JS和CSS
        $this->assign('lists', $lists);        
                
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