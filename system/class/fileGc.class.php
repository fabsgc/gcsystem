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
		protected $_filePath                                   ;
		private $_fileName                                   ;
		private $_fileExt                                    ;
		private $_fileContent                                ;
		private $_fileChmod                                  ;
		private $_error                             = array();
		private $_info                              = array();
		private $_isExist                           = false  ;
		
		const NOFILE   = 'Aucun fichier n\'a été difini'     ;
		const NOACCESS = 'le fichier n\'est pas accessible'  ;
		const NOREAD   = 'le fichier n\'est pas lisible'     ;
		
		const CHMOD0644                              = 0644  ;
		const CHMOD0755                              = 0755  ;
		
		public function __construct($filepath){
			if($filepath == NULL) { $filepath = 'empty.txt'; $this->_setFileDefault($filepath); }
			if(is_file($filepath)){
				$this->setFile($filepath);
			}
			else{
				$this->_addError(self::NOACCESS);
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
		
		public function getFileInfo(){
			return $this->_fileInfo;
		}
		
		public function getFileContent(){
			return $this->_fileContent;
		}
		
		public function getFileChmod(){
			return $this->_fileContent;
		}
		
		public function getSize(){
			if($this->_isExist == true){
				return $this->_fileInfo['size'];
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getLastAccess(){
			if($this->_isExist == true){
				return $this->_fileInfo['atime'];
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getLastUpdate(){
			if($this->_isExist == true){
				return $this->_fileInfo['ctime'];
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getChmod(){
			if($this->_isExist == true){
				return file_get_contents($this->_filePath);
			}
			else{
				$this->_addError(self::NOFILE);
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
				$this->_setFileContent($filepath);
				$this->_setFileChmod($filepath);
				$this->_isExist = true;
			}
			else{
				$this->_addError(self::NOACCESS);
			}
		}
		
		public function setChmod($chmod =self::CHMOD644){
			chmod($thid->_filePath, $chmod);
			$this->_setFileChmod($this->_filePath);
		}
		
		public function setContent($content){
			file_put_content($this->_fileContent, $content);
			$this->_setFileContent($this->_filePath);
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
				$this->_addError(self::NOFILE);
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
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function contentTo($file){
			if(is_file($file)){
				if(is_readable($file)){
					file_put_contents($file, $this->_fileContent);
				}
				else{
					$this->_addError(self::NOAREAD);
					return false;
				}
			}
			else{
				$this->_addError(self::NOACCESS);
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
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
		private function _setFileName($file){
			if(is_file($file)){
				$this->_fileName = basename($file);
				return true;
			}
			else{
				$this->_addError(self::NOACCESS);
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
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
		private function _setFileContent($file){
			if(is_file($file)){
				if(is_readable($file)){
					$this->_fileContent = file_get_contents($file);
					return true;
				}
				else{
					$this->_addError(self::NOAREAD);
					return false;
				}
			}
			else{
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
		private function _setFileInfo($file){
			if(is_file($file)){
				$this->_fileInfo = stat($file);
				return true;
			}
			else{
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
		private function _setFileChmod($file){
			if(is_file($file)){
				$this->_fileChmod = substr(sprintf('%o', fileperms($file)), -4);;
				return true;
			}
			else{
				$this->_addError(self::NOACCESS);
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