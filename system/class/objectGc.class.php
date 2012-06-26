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
		private $objectGc                      ; //contient une référence vers l'objet créé
		private $error              = array(); //array contenant toutes les erreurs enregistrées
		
		public  function __construct(){
		}
		
		public function addAccordion(){
		
		}
		
		public function addAutocomplete($id){
			return $this->objectGc = new ObjectUiAutocompleteGC($id);
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
	
	abstract class ObjectUiInteractions {
	}
	
	class ObjectUiAccordionGC extends ObjectUiInteractions{
		public  function __construct(){
		}
		
		public  function __desctuct(){
		}
	}
	
	class ObjectUiAutocompleteGC extends ObjectUiInteractions{
		private $list        = array('no');
		private $render               ;
		private $id                   ;
		
		public  function __construct($id="no"){
			$this->id = $id;
		}
		
		public function setList($list = array()){
			if(is_array($list)){
				$this->list = $list;
			}
			return $this;
		}
		
		public function show(){
			$this->render .= '<script>';
			$this->render .= '$(function() {';
			$this->render .= 'var availableTags = [';
			
			foreach($this->list as $list){
				$this->render .='"'.$list.'",';
			}
			
			$this->render = substr($this->render, 0, -1);
			
			$this->render .= '];';
			$this->render .= '$( "#'.$this->id.'" ).autocomplete({';
			$this->render .= 'source: availableTags';
			$this->render .= '});';
			$this->render .= '});';
			$this->render .= '</script>';
			
			echo $this->render;
		}
		
		public  function __desctuct(){
		}
	}
	
	class ObjectUiButtonGC extends ObjectUiInteractions{
		public  function __construct(){
		}
		
		public  function __desctuct(){
		}
	}
	
	class ObjectUiDatepickerGC extends ObjectUiInteractions{
		public  function __construct(){
		}
		
		public  function __desctuct(){
		}
	}
	
	class ObjectDialogGC extends ObjectUiInteractions{
		public  function __construct(){
		}
		
		public  function __desctuct(){
		}
	}
	
	class ObjectProgressbarGC extends ObjectUiInteractions{
		public  function __construct(){
		}
		
		public  function __desctuct(){
		}
	}
	
	class ObjectSwfGc{
		public  function __construct(){
		}
		
		public  function __desctuct(){
		}
	}
	
	class ObjectVideoGc{
		public  function __construct(){
		}
		
		public  function __desctuct(){
		}
	}
?>