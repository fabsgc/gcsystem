<?php
	/*\
	 | ------------------------------------------------------
	 | @file : modo.class.php
	 | @author : fab@c++
	 | @description : class g�rant le filtrage du contenu du site
	 | @version : 2.0 b�ta
	 | ------------------------------------------------------
	\*/
	
	class modo{
		public $contenu                       ; //contenu � filtrer
		private $error              = array() ; //array contenant toutes les erreurs enregistr�es
		
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