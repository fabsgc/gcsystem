<?php
	/*\
	 | ------------------------------------------------------
	 | @file : file.class.php
	 | @author : fab@c++
	 | @description : class gérant les opérations sur les fichiers
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    class file{		
		private $file;
		private $error;
		
		public function __construct($file){
			if(is_file($file)){
				$this->file = $file;
			}
			else{
				$this->addError('le fichier n\'est pas accessible');
			}
		}
		
		public function setFile($file){
			if(is_file($file)){
				$this->file = $file;
			}
			else{
				$this->addError('le fichier n\'est pas accessible');
				return false;
			}
		}
		
		public function moveTo($dir){
			if(copy($file, $dest)){
				if(delete($file)){
					$this->setFile($dir.'/'.array_pop(explode($file)));
					return true;
				}
			}
			else{
				$this->addError('le fichier n\'a pas pu être déplacé');
				return false;
			}
		}
		
		public function copyTo($dir){
			if(copy($file, $dest)){
				return true;
			}
			else{
				$this->addError('le fichier n\'a pas pu être copié');
				return false;
			}
		}
		
		public function showFile($dir){
		
		}
		
		private function addError($error){
			array_push($this->error, $error);
		}
		
		public function showError(){
			foreach($this->error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		public function returnRelative(){
		}

		public function __destruct(){
		}
	}