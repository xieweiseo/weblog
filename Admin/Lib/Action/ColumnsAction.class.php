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
class ColumnsAction extends CommonAction{	
	   public function index(){

			   $newscat=D('Columns')->Catlist("News",1);	
			   $picturecat=D('Columns')->Catlist("Picture",2);
			   $downloadcat=D('Columns')->Catlist("Download",3);
			   $blogcat=D('Columns')->Catlist("Blog",4);	
			   $resourcescat=D('Columns')->Catlist("Resources",5);
			   $catarray=array($newscat,$picturecat,$downloadcat,$blogcat,$resourcescat);
			   $this->assign('catarray',$catarray);

			   $this->display();	
	           }
		
	    public function insert(){
			  
			   if(isset($_POST['modelid'])){//判断是哪个模型
			       $modelid=trim($_POST['modelid']);
			       $model=$this->getMname($modelid);	
				   }
			   $mlist=$this->modelist();
		       $alist=D('Columns')->Catlist($model, $modelid);	
			   $this->assign('modelid',$modelid);	
			   $this->assign('alist',$alist);	
			   $this->assign('mlist',$mlist);
			   $this->display();
		      }
		  
	     public function edit(){
    	    if(!$_GET['colId']) $this->error("该栏目不存在或已删除！");
			
		    $colId=$_GET['colId'];
			
		    $vo = M('Columns')->where(array('colId'=>$colId))->find();
		    $modelid=$vo['modelid'];  
		    $mlist=$this->modelist();	
		    $alist=D('Columns')->Catlist($this->getMname($modelid), $modelid);	
		    $this->assign('colId',$colId);
		    $this->assign('modelid',$modelid);
		    $this->assign('alist',$alist);	
		    $this->assign('mlist',$mlist);
            $this->assign('vo',$vo);
		
		$this->display();
    }
		public function getMname($modelid){
			$Marray = array (
			     1 => 'News',
			     2 => 'Picture',
			     3 => 'Download',
			     4 => 'Blog'
		       );
			$model=$Marray[$modelid];  
			return  $model;
			}
		public function modelist(){
			$Columns=new Model;
			$mlist=$Columns->query(" select *, count(distinct model) from ".C('DB_PREFIX')."columns group by modelid");
			 return $mlist;
			}
		public function add(){
				$this->addata('Columns');
		}
		public function addcol(){
				if (empty($_GET['colId'])) $this->error('栏目ID不存在');	
                $vo = M('Columns')->where(array('colId'=>trim($_GET['colId'])))->find();
                if ($vo) {
                   $this->assign('vo', $vo);
                   $this->display();
                  } else {
					$this->error();
                  }             
		}

		
       // 删除数据
	    public function del() {
		  if(!empty($_REQUEST['colId'])) {
			  $getid=trim($_REQUEST['colId']);
			  $Columns	=	M("Columns");
			  $vo = $Columns->where(array('colId'=>$getid))->find();
			  $M = M($this->getMname($vo['modelid']));
			  if($Columns->where(array('colPid'=>$getid))->select()){
			         $this->error("请先删除子栏目!");
		      }elseif($M->where(array('catid'=>$getid))->select()){
			    $this->error('请先清空栏目下信息!');
		      }elseif($Columns->where(array('colId'=>$getid))->delete()){
			   $this->success("删除成功!");
		     }
		    }else{
			  $this->error('删除项不存在！');
		   }
	      }
	   public  function update() {
		      $colPid=isset($_POST['colPid'])?(int)$_POST['colPid']:0;
		       $colId=$_POST['colId'];
		       $Column = M('Columns');
			   $fat=$Column->where(array('colId'=>$colPid))->find();
			   $son=$Column->where(array('colId'=>$colId))->find();
			if(in_array($son['colId'],explode("-", $fat['colPath']))){
								
				    $this->error("父级不能移到子级栏目!");
				  }

                $this->getupdate();
             }
	   		 
	 public function act(){
		$this->action();
	}	
}
?>