<?php
	/*\
	 | ------------------------------------------------------
	 | @file : modoGc.class.php
	 | @author : fab@c++
	 | @description : class gérant le filtrage du contenu du site
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class modoGc{
		use errorGc;                            //trait fonctions génériques
		
		public $contenu                       ; //contenu à filtrer
		protected $insulte =array(
			'salaud', 'merde', 'salope', 'pute', 'putain', 'fils de pute', 'enculé', 'connasse'); //array contenant toutes les erreurs enregistrées
		
		public  function __construct(){
		}
		
		protected function _showError(){
			foreach($this->error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		protected function _addError($error){
			array_push($this->error, $error);
		}
		
		public  function __desctuct(){
		
		}
	}
?>