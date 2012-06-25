<?php
	/*\
	 | ------------------------------------------------------
	 | @file : objectGc.class.php
	 | @author : fab@c++
	 | @description : class gérant les fichiers compressés
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class objectGc{
		private $object                      ; //contient une référence vers l'objet créé
		private $error              = array(); //array contenant toutes les erreurs enregistrées
		
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
	
	class ObjectCalendarGC{
	
	}
	
	class ObjectDialogBoxGC{
	
	}
	
	class ObjectSwfGc{
	
	}
	
	class ObjectVideoGc{
	
	}
?>