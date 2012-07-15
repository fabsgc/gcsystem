<?php
	/**
	 * @file : zipGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers compressés
	 * @version : 2.0 bêta
	*/
	
	class zipGc extends fileGc{
		protected $_zipContent                                      ;
		protected $_zipFileContent                                  ;
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 *
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function __construct($filepath){
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
		
		public function putFileToFtp(){
		
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