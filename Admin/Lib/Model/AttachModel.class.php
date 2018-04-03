<?php

class AttachModel extends RelationModel{
	protected $_link = array(
        'Page'=>array(
           'mapping_type'=>BELONGS_TO,
           'class_name'=>'Page',
           'foreign_key'=>'recordId',//Attach表recordId
           'as_fields'=>'downCount,name',
       ),
         'Reply'=>array(//这个关联无效，为什么？
           'mapping_type'=>HAS_ONE ,//不能是HAS_MANY 
           'class_name'=>'Reply',
           'foreign_key'=>'cid',
           'mapping_fields'=>'rauthor,remail,recontent,rtime',
       ),
    );

	
}
?>