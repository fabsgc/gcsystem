<?php
	/*\
	 | ------------------------------------------------------
	 | @file : modo.class.php
	 | @author : fab@c++
	 | @description : class gérant le filtrage du contenu du site
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class modoGc{
		public $contenu                       ; //contenu à filtrer
		private $error              = array() ; //array contenant toutes les erreurs enregistrées
		private $insulte =array(
			'salaud', 'merde', 'salope', 'pute', 'putain', 'fils de pute', 'enculé', 'connasse'); //array contenant toutes les erreurs enregistrées
		
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