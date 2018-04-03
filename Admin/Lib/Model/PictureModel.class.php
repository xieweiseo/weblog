<?php

class PictureModel extends CommonModel{
	protected $_auto=array(
		array('status','1'),  
		array('inputtime','time',1,'function'),		
		array('udatetime','time',2,'function'),
		
		 array('username','getusername',1,'callback'),
	);
	protected $_link = array(
				'Attach' => array(
				'mapping_type' => HAS_MANY,
				'class_name' => 'Attach',//要关联的模型类名
				'mapping_name' => 'Attach',//关联的映射名称，用于获取数据用
				'foreign_key' => 'recordId',//关联的外键名称，这里是评论表里的nid字段对应News表的id字段
				'mapping_fields' => 'id',//关联要查询的字段，这里指评论的ID
				'as_fields' => 'id',
				)
			   
	          );
	
	 public function getusername(){
		$data=$_SESSION['loginUserName'];
		return $data;
	}
	
}
?>