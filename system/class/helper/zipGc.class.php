<?php
	/**
	 * @file : zipGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers compressés
	 * @version : 2.0 bêta
	*/
	
	class zipGc extends fileGc{
		protected $_zipContent          = array()                       ;
		protected $_zipFileContent      = array()                       ;
		protected $_zip                                                 ;
		protected $_FileCompressedSize  = array()                       ;
		protected $_FilesCompressedSize                                 ;
		
		const NOPUTDIR                                           = false;
		const PUTDIR                                             = true ;
		const NODIR   = 'le répertoire n\'existe pas'                   ;
		
		/**
		 * Crée l'instance de la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function __construct($filepath){
			$filepath = strval($filepath);
			if(is_file($filepath) && file_exists($filepath) && is_readable($filepath) and zip_open($filepath)){
				$this->setFile($filepath);
			}
			else{
				$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
			}
		}
		
		public function getContentZip(){
			if($this->_isExist == true){
				$this->_zipContent = array();
				$this->_setZip($this->_filePath);
				
				while ($zip_entry = zip_read($this->_zip)){
					array_push($this->_zipContent, zip_entry_name($zip_entry));
				}
				
				$this->_closeZip($this->_filePath);
				return $this->_zipContent;
			}
			else{
				$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
				return false;
			}
		}
		
		public function getContentFileZip(){
			if($this->_isExist == true){
				$this->_zipContentFile = array();
				$this->_setZip($this->_filePath);
				
				while ($zip_entry = zip_read($this->_zip)){
					$this->_zipContentFile[zip_entry_name($zip_entry)] = zip_entry_read($zip_entry, 900000);
				}
				
				$this->_closeZip($this->_filePath);
				return $this->_zipContentFile;
			}
			else{
				$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
				return false;
			}			
		}
		
		public function putFileToFtp($dir, $option = zipGc::NOPUTDIR, $filter = array()){
			if($this->_isExist == true){
				if(is_dir($dir)){
					$this->_zipContentFile = array();
					$this->_setZip($this->_filePath);
					
					while ($zip_entry = zip_read($this->_zip)){
						if($option == zipGc::NOPUTDIR){
							if(in_array((substr(zip_entry_name($zip_entry),-3)), $filter) || count($filter)==0){
								if(!preg_match('#\/$#i', zip_entry_name($zip_entry))){
									file_put_contents($dir.basename(zip_entry_name($zip_entry)), zip_entry_read($zip_entry, 900000));
									echo $dir.basename(zip_entry_name($zip_entry)).'<br />';
								}							
							}
							else{
							}
						}
						elseif(zipGc::PUTDIR){
							if(preg_match('#\/$#i', zip_entry_name($zip_entry))){
								mkdir($dir.zip_entry_name($zip_entry));
							}
							else{
								if(in_array((substr(zip_entry_name($zip_entry),-3)), $filter) || count($filter)==0){
									file_put_contents($dir.zip_entry_name($zip_entry), zip_entry_read($zip_entry, 900000));
								}
							}
						}
					}
					
					$this->_closeZip($this->_filePath);
					return true;
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
				return false;
			}
		}
		
		public function getFilesCompressedSize(){
			return $this->_FilesCompressedSize;
		}
		
		public function getFileCompressedSize(){
			return $this->_FileCompressedSize;
		}
		
		public function setFile($filepath){
			$filepath = strval($filepath);
			if(is_file($filepath) && file_exists($filepath) && is_readable($filepath)){
				$this->_setFilePath($filepath);
				$this->_setFileName($filepath);
				$this->_setFileExt($filepath);
				$this->_setFileInfo($filepath);
				$this->_setFileContent($filepath);
				$this->_setFileChmod($filepath);
				$this->_setFileCompressedSize($filepath);
				$this->_setFilesCompressedSize($filepath);
				$this->_isExist = true;
			}
			else{
				$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
			}
		}
		
		protected function _setFilesCompressedSize($filepath){
			$this->_setZip($filepath);
			while ($zip_entry = zip_read($this->_zip)){
				$this->_FilesCompressedSize += zip_entry_compressedsize($zip_entry);
			}
			$this->_closeZip($filepath);
		}
		
		protected function _setFileCompressedSize($filepath){
			$this->_setZip($filepath);
			while ($zip_entry = zip_read($this->_zip)){
				$this->_FileCompressedSize[zip_entry_name($zip_entry)] = zip_entry_compressedsize($zip_entry);
			}
			$this->_closeZip($filepath);
		}
		
		protected function _setZip($filepath){
			$this->_zip = zip_open($filepath);	
		}
		
		protected function _closeZip($filepath){
			$this->_zip = zip_close($filepath);
		}

		public function getIsExist(){
			return $this->_isExist;
		}
		
		/**
		 * Desctructeur
		 * @access	public
		 * @since 2.0
		*/
		
		public  function __destruct(){
		}
	}