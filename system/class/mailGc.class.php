<?php
	/*\
	 | ------------------------------------------------------
	 | @file : mailGc.class.php
	 | @author : fab@c++
	 | @description : class générant des mails
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class mailGc{
		public $destinataire                          ; //email du destinataire
		public $message                               ; //message
		public $piece                       = array() ; //liste des pièces jointes
		public $error                       = array() ; //liste des erreurs
		
		public  function __construct(){
		}
		
		private function _showError(){
			foreach($this->error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		private function _addError($error){
			array_push($this->error, $error);
		}
		
		public  function __desctuct(){
		
		}
	}
?>