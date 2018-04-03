<?php
class ColumnsModel extends Model{
		protected $_auto=array(
			array('colPath','colPath',3,'callback'),
			//array('colPid','colPid',3,'callback'),
			array('model','getmodel',3,'callback'),
				
		);
				
		
	   function colPath(){
		$colPid=isset($_POST['colPid'])?(int)$_POST['colPid']:0;
		$colId=$_POST['colId'];
			if($colPid==0){				
				return 0;
			}
			
			$fat=$this->where("colId=$colPid")->find();//查询的是父级ID
			$data=$fat['colPath'].'-'.$fat['colId'];//得到父级的colPath，连上父级ID，返回的是子级的colPath				
			return $data;
	    }
		
	  function Catlist($model, $modelid= '') {
		$Module = M($model);
		$condition = '1=1 ';
		if($modelid){
		   $condition.= "and modelid='".$modelid."'";
		}
		$list=$this->field("concat(colPath,'-',colId) AS bpath, colId,colPid,colPath, colTitle, description,ord,model")->where($condition)->order('bpath,colId')->select();
		//dump($list);exit;
		foreach ($list as $k => $v) {
			$list[$k]['count'] = count(explode('-', $v['bpath']));
			$list[$k]['total'] = $Module->where(array('catid'=> $v['colId']))->count();
			$str = '';
			if ($v['colPid'] <> 0) {
				for ($i = 0; $i < $list[$k]['count'] * 2; $i++) {
					$str .= '&nbsp;';
				}
				$str .= '|-';
			}
			$list[$k]['space'] = $str;
		}

		return $list;
	}
	
	function getmodel(){
		  $modelid=isset($_POST['modelid']) ? $_POST['modelid']: 0;
		 
			  switch($modelid){
                case 1:
				        $model="文章";
                        break;
                case 2:
				         $model="图片";  
                        break;
				case 3:
				       $model="下载"; 
                        break;
				case 4:
				        $model="博客"; 
                        break;
				case 5:
				       $model="资源"; 
                        break;
                
				default: $model="未知模型";  
                                        
          }
			 $data=$this->array_iconv($model);
			return $data; 
		  
		  }
	function array_iconv($data, $input = 'gbk', $output = 'utf-8') {
	if (!is_array($data)) {
		return iconv($input, $output, $data);
	} else {
		foreach ($data as $key=>$val) {
			if(is_array($val)) {
				$data[$key] = array_iconv($val, $input, $output);
			} else {
				$data[$key] = iconv($input, $output, $val);
			}
		}
		return $data;
	}
}
}

?>

