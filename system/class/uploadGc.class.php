<?php
	/*\
	 | ------------------------------------------------------
	 | @file : uploadGc.class.php
	 | @author : fab@c++
	 | @description : class gérant les uploads
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class uploadGc{
		use errorGc;                               //trait fonctions génériques
		
		protected $_name                         ; //contient le nom du formulaire
		protected $_type                         ; //contient le type du fichier
		protected $_tmpName                      ; //contient l\'adresse temporaire
		protected $_erreur                       ; //contient les erreurs retournées
		protected $_size                         ; //contient le poids du fichier
		protected $_extension                    ; //contient l\'extension du fichier
		protected $_url                          ; //adresse du fichier une fois enregistré sur le serveur
		protected $_validate            = false  ; //adresse du fichier une fois enregistré sur le serveur
		
		public  function __construct($name){
			$this->_setUpload(strval($name));
		}
		
		public function checkFile($contrainte = array()){
			switch($contrainte){
				case 'minsize':
				break;
				
				case 'maxsize':
				break;
				
				case 'size':
				break;
				
				case 'extension':
				break;
				
				default:
					$this->_addError('Cette contrainte n\'existe pas');
				break;
			}
		}
		
		public function move($dir){
		}
		
		public function setUpload($name){
			$this->_setUpload(strval($name));
		}
		
		public function getName(){
			return $this->_name;
		}
		
		public function getType(){
			return $this->_type;
		}
		
		public function getTmp(){
			return $this->_tmpName;
		}
		
		public function getError(){
			return $this->_erreur;
		}
		
		public function getSize(){
			return $this->_size;
		}
		
		public function getExtension(){
			return $this->_extension;
		}
		
		public function getUrl(){
			return $this->_url;
		}
		
		protected function _setUpload($name){	
		}
		
		protected function _setName($name){	
		}
		
		protected function _setType($name){	
		}
		
		protected function _setTmp($name){	
		}
		
		protected function _setError($name){	
		}
		
		protected function _setSize($name){	
		}
		
		protected function _setExtension($name){	
		}
		
		public  function __desctuct(){
		
		}
	}
?>