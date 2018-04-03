<?php

class TestWidget extends Action{    
       public function hello($name=''){  
	          echo ("hello,".$name."!");   
			    } 
	}

/*class TestWidget extends Action{    
      public function hello($name=''){ 
	       $this->assign('name',$name);   
		   $this->display('Test:hello');   
		     } 
		}*/

?>