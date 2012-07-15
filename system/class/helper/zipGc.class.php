<?php
	/**
	 * @file : zipGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers compressés
	 * @version : 2.0 bêta
	*/
	
	class zipGc extends fileGc{
		protected $_zipContent                                          ;
		protected $_zipFileContent                                      ;
		
		const NOPUTDIR                                           = false;
		const PUTDIR                                             = true ;
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 *
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function __construct($filepath){
			$filepath = strval($filepath);
			if(is_file($filepath) and zip_open($filepath)){
				$this->setFile($filepath);
			}
			else{
				$this->_addError(self::NOACCESS);
			}
		}
		
		public function getContentZip(){
		
		}
		
		public function getContentFileZip(){
		
		}
		
		public function putFileToFtp($dir = zipGc::NOPUTDIR){
		
		}
		
		public function getFileCompressedSize(){
			return $this->_FileCompressedSize;
		}
		
		public function setFile($filepath){
			$filepath = strval($filepath);
			if(is_file($filepath)){
				$this->_setFilePath($filepath);
				$this->_setFileName($filepath);
				$this->_setFileExt($filepath);
				$this->_setFileInfo($filepath);
				$this->_setFileContent($filepath);
				$this->_setFileChmod($filepath);
				$this->_setFileCompressedSize($filepath);
				$this->_isExist = true;
			}
			else{
				$this->_addError(self::NOACCESS);
			}
		}
		
		protected function _setFileCompressedSize($filepath){
			$this->_FileCompressedSize = zip_entry_compressedsize($filepath);
		}
		
		/**
		 * Desctructeur
		 *
		 * @access	public
		 * @return	boolean
		 * @since 2.0
		*/
		
		public  function __desctuct(){
		}
	}
?>