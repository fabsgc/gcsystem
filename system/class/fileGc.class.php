<?php
	/*\
	 | ------------------------------------------------------
	 | @file : fileGc.class.php
	 | @author : fab@c++
	 | @description : class gérant les opérations sur les fichiers, très complète
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    class fileGc{		
		private $_filePath                                   ;
		private $_fileName                                   ;
		private $_fileExt                                    ;
		private $_error                             = array();
		private $_isExist                           = false  ;
		private $_info                              = array();
		
		public function __construct($filepath){
			if($filepath == NULL) { $filepath = 'empty.txt'; $this->_setFileDefault($filepath); }
			if(is_file($filepath)){
				$this->setFile($filepath);
			}
			else{
				$this->_addError('le fichier n\'est pas accessible');
			}
		}
		
		public function getFilePath(){
			return $this->_filePath;
		}
		
		public function getFileName(){
			return $this->_fileName;
		}
		
		public function getFileExt(){
			return $this->_fileExt;
		}
		
		public function getSize(){
			if($this->_isExist == true){
				return $this->_fileInfo['size'];
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		public function getLasAccess(){
			if($this->_isExist == true){
				return $this->_fileInfo['atime'];
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		public function getLastUpdate(){
			if($this->_isExist == true){
				return $this->_fileInfo['ctime'];
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		public function getFile(){
			if($this->_isExist == true){
				return file_get_contents($this->_filePath);
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		public function getChmod(){
			if($this->_isExist == true){
				return file_get_contents($this->_filePath);
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		public function setFile($filepath){
			if($filepath == NULL) $filepath = 'empty.txt'; $this->_setFileDefault($filepath);
			$filepath = strval($filepath);
			if(is_file($filepath)){
				$this->_setFilePath($filepath);
				$this->_setFileName($filepath);
				$this->_setFileExt($filepath);
				$this->_setFileInfo($filepath);
				$this->_isExist = true;
			}
			else{
				$this->_addError('le fichier n\'est pas accessible');
			}
		}
		
		public function setChmod($chmod){
			if($this->_isExist == true){
			
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		public function moveTo($dir){
			if($this->_isExist == true){
				if(copy($this->_filePath, $dir.$this->_fileName)){
					if(unlink($this->_filePath)){
						$this->setFile($dir.$this->_fileName);
						return true;
					}
					else{
						$this->_addError('le fichier n\'a pas pu être déplacé du répertoire original');
						return false;
					}
				}
				else{
					$this->_addError('le fichier n\'a pas pu être déplacé');
					return false;
				}
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		public function copyTo($dir){
			if($this->_isExist == true){
				if(copy($this->_filePath, $dir.$this->_fileName)){
					return true;
				}
				else{
					$this->_addError('le fichier n\'a pas pu être copié');
					return false;
				}
			}
			else{
				$this->_addError('Aucun fichier n\'a été difini');
				return false;
			}
		}
		
		private function _setFileDefault($file){
			$fileCreate = fopen($file, 'a');
			fclose($fileCreate);
		}
		
		private function _setFilePath($file){
			if(is_file($file)){
				$this->_filePath = $file;
				return true;
			}
			else{
				$this->_addError('le fichier n\'est pas accessible');
				return false;
			}
		}
		
		private function _setFileName($file){
			if(is_file($file)){
				$this->_fileName = basename($file);
				return true;
			}
			else{
				$this->_addError('le fichier n\'est pas accessible');
				return false;
			}
		}
		
		private function _setFileExt($file){
			if(is_file($file)){
				$extension = explode('.', basename($file));
				$this->_fileExt = $extension[count($extension)-1];
				return true;
			}
			else{
				$this->_addError('le fichier n\'est pas accessible');
				return false;
			}
		}
		
		private function _setFileInfo($file){
			if(is_file($file)){
				$this->_fileInfo = stat($file);
				return true;
			}
			else{
				$this->_addError('le fichier n\'est pas accessible');
				return false;
			}
		}
		
		private function _addError($error){
			array_push($this->_error, $error);
		}
		
		public function showError(){
			foreach($this->_error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}

		public function __destruct(){
		}
	}