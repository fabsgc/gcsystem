<?php
	/*\
	 | ------------------------------------------------------
	 | @file : socialGc.class.php
	 | @author : fab@c++
	 | @description : class gérant différents réseaux sociaux
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class objectGc{
		private $error              = array(); //array contenant toutes les erreurs enregistrées
		
		public  function __construct(){
			if
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