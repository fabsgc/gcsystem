<?php
	/*\
	 | ------------------------------------------------------
	 | @file : dir.class.php
	 | @author : fab@c++
	 | @description : class gèrant les opérations sur les fichiers
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    class dirGc{		
		private $dir;
		
		public function __construct($dir){
			$this->dir = $dir;
		}
		
		public function setDir($dir){
			$this->dir = $dir;
		}
		
		public function setChmod($chmod){
		}
		
		public function setChmodFiles($chmod){
		}
		
		public function moveTo($dir){
		
		}
		
		public function copyTo($dir){
		
		}
		
		public function showdir($dir){
		
		}

		public function __destruct(){
		}
	}