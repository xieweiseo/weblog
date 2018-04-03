<?php
class CommonModel extends RelationModel{
	    protected $_link = array(
				'Comment' => array(
				'mapping_type' => HAS_MANY,
				'class_name' => 'Comment',//Ҫ������ģ������
				'mapping_name' => 'Comment',//������ӳ�����ƣ����ڻ�ȡ������
				'foreign_key' => 'nid',//������������ƣ����������۱����nid�ֶζ�ӦNews���id�ֶ�
				'mapping_fields' => 'id',//����Ҫ��ѯ���ֶΣ�����ָ���۵�ID
				'as_fields' => 'id',
				)
			   
	          );
		protected $_auto=array(
		             array('status','1'),  
					 array('posid','posid',3,'callback'),
		             array('inputtime','time',1,'function'),
		             array('udatetime','time',2,'function'),
		             //array('thumb','thumb',3,'callback'),
		             array('username','getusername',1,'callback'),
			         array('ctime','ctime',1,'callback'),
	               );
		
     public function ctime(){
		$data=date("Y-m-d",time());
		return $data;
	}
	public function posid(){
		$posid=isset($_POST['posid'])?(int)$_POST['posid']:0;
		$data=$posid;
		return $data;
	}
	 public function getusername(){
		$data=$_SESSION['loginUserName'];
		return $data;
	}
	
}

?>

