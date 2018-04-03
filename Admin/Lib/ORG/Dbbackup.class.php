<?php

	/**
	 * ���ݿⱸ�ݳ���
	 * @author��w3note.com
	 * @lastupdate��2012-9-18
	 *ע�����ಿ����Դ������
	 */
	class dbbackup{

			private $dbhost;//���ݿ�����

			private $dbuser;//���ݿ��û���
		
			private $dbpwd;//���ݿ�����
		
			private $dbname;//���ݿ���
		
			private $coding;//���ݿ����,GBK,UTF8,gb2312

			private $conn;//���ݿ����ӱ�ʶ

			private $data_dir = 'data/';//�ļ���·������ű������ݣ�

			private $part = 2048;//�־��ȣ���λKB��

			public $bakfn;//�����ļ���


		public function __construct($dbhost, $dbuser, $dbpwd, $dbname, $coding = 'UTF8',$pconnect = 0){
			$this->init();
			$this->dbhost = $dbhost;
			$this->dbuser = $dbuser;
			$this->dbpwd =  $dbpwd;
			$this->dbname = $dbname;
			$this->coding = $coding;
			$this->connect();
			$this->part = $this->part * 1024; //���÷־���,��λΪKB
			$this->cre_dir();  				  //�����ļ���
		}

		
		private function init(){
			set_time_limit(0);					//����ִ�в���ʱ
			error_reporting(E_ERROR | E_PARSE); //������
		}

		
		private function connect(){
			
			if($pconnect==0){
				$this->conn =@mysql_pconnect($this->dbhost, $this->dbuser, $this->dbpwd);
				}else{
				$this->conn =@mysql_pconnect($this->dbhost, $this->dbuser, $this->dbpwd);	
			}
			if(!$this->conn){
				echo '<font color="red">������ʾ���������ݿ�ʧ�ܣ�</font>';
				exit();
			}

			if(!@mysql_select_db($this->dbname, $this->conn)){
				echo '<font color="red">������ʾ�������ݿ�ʧ�ܣ�</font>';
				exit();
			}

			if(!@mysql_query("SET NAMES $this->coding")){
				echo '������ʾ�����ñ���ʧ�ܣ�';
			}
		}

		
		private function cre_dir(){
			//�ļ��в������򴴽�
			if(!is_dir($this->data_dir)){
				mkdir($this->data_dir, 0777);
			}
		}

		
		public function gettbinfo(){
			if ($res=mysql_query("SHOW TABLE STATUS FROM ".$this->dbname."")){  

             while($row=mysql_fetch_array($res)) 
			    $arrtbinfo[]=$row;
			   
                } 

			
			return $arrtbinfo; //���ر���
		}

		
		public function get_backupdata($arrtb){
			$backupdata = "#\n# WBlog bakfile\n#Time: ".date('Y-m-d H:i',time())."\n# Type: \n# w3note: http://www.w3note.com\n# --------------------------------------------------------\n\n\n"; //�洢��������
			foreach($arrtb as $tb){
				//��ȡ��ṹ
				$query = mysql_query("SHOW CREATE TABLE $tb");
				$row = mysql_fetch_row($query);
				$backupdata .= "DROP TABLE IF EXISTS $tb;\n" . $row[1] . ";\n\n";
				//��ȡ������
				$query = mysql_query("select * from $tb");
				$numfields = mysql_num_fields($query); //ͳ���ֶ���
				
				while($row = mysql_fetch_row($query)){
					$comma = ""; 
					$backupdata .= "INSERT INTO $tb VALUES (";
					for($i=0; $i<$numfields; $i++){
											  	 
						$backupdata .= $comma . "'" . mysql_escape_string($row[$i]) . "'";
						$comma = ",";
					}
					$backupdata .= ");\n";
					
					if(strlen($backupdata) > $this->part){
						$arrbackupdata[] = $backupdata;
						$backupdata = ''; 
					}
				}
				$backupdata .= "\n"; 
			}
			
			if(is_array($arrbackupdata)){
				
				array_push($arrbackupdata, $backupdata);
				return $arrbackupdata; 
			}
			return $backupdata; 
		}

		
		private function wri_file($data){
			
			if(is_array($data)){
				$i = 1;
				foreach($data as $val){
					
					$filename = $this->data_dir . "wb_" . time() . "_part{$i}.sql"; //�ļ���
					if(!$fp = @fopen($filename, "w+")){ echo "�ڴ��ļ�ʱ��������,����ʧ��!"; return false;}
					if(!@fwrite($fp, $val)){
						echo "��д����Ϣʱ��������,����ʧ��!"; fclose($fp); //��ر��ļ�����ɾ��
						unlink($filename); //ɾ���ļ�
						return false;}
					$this->bakfn[] = "wb_" . time() . "_part{$i}.sql"; //���ݳɹ��򷵻��ļ�������
					$i++;
				}
			}else{ //��������
				$filename = $this->data_dir . "wb_" . time() . ".sql";
				if(!$fp = @fopen($filename, "w+")){ echo "�ڴ��ļ�ʱ��������,����ʧ��!"; return false;}
				if(!@fwrite($fp, $data)){
					echo "��д����Ϣʱ��������,����ʧ��!"; fclose($fp);
					unlink($filename);
					return false;}
				$this->bakfn = "wb_" . time() . ".sql"; 
			}
			fclose($fp);
			return true;
		}

		
		public function export($data){
			return $this->wri_file($data); //д������
		}

		public function get_backup(){
			$backup = scandir($this->data_dir); 
			for($i=0; $i<count($backup); $i++){
				if($backup[$i] != "." && $backup[$i] != ".."){
					if(stristr($backup[$i], 'wb_')) $arrbackup[] = $backup[$i];
									
				}
			}
			return $arrbackup;
		}

		public function import($filename){
			
			$Boolean = preg_match("/_part/",$filename); 		   
			if($Boolean){
				$fn = explode("_part", $filename);				  
				$backup = scandir($this->data_dir);	    		  
				for($i=0; $i<count($backup); $i++){
					$part = preg_match("/{$fn[0]}/", $backup[$i]); 
					if($part){
						$filenames[] = $backup[$i];
					}
				}
			}
			
			if(is_array($filenames)){
				foreach($filenames as $fn){
					$data .= file_get_contents($this->data_dir . $fn);  //��ȡ����
				}
			}else{
				$data = file_get_contents($this->data_dir . $filename);
			}
			
			
			$data = str_replace("\r", "\n", $data);
			$regular = "/;\n/";
			$data = preg_split($regular,trim($data));
		
			foreach($data as $val){
				mysql_query($val) or die('��������ʧ�ܣ�' . mysql_error());
			}
			return true;
			
		}

		
		public function del($delfn){
			
			if(is_array($delfn)){
				foreach($delfn as $fn){
					if(!unlink($this->data_dir.$fn)){ return false;}
				}
				return true;
			}
			
			return unlink($this->data_dir.$delfn);
		}

	}

?>





