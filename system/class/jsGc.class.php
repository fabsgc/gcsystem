<?php
	/*\
	 | ------------------------------------------------------
	 | @file : jsGc.class.php
	 | @author : fab@c++
	 | @description : class de surcouche javascript
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class jsGc{
		private $error              = array() ; //array contenant toutes les erreurs enregistrées
		
		public  function __construct(){
		}
		
		private function showError(){
			foreach($this->error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		private function addError($error){
			array_push($this->error, $error);
		}
		
		public  function __desctuct(){
		
		}
	}
?>