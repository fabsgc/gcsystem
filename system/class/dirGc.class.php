<?php
	/*\
	 | ------------------------------------------------------
	 | @file : dirGc.class.php
	 | @author : fab@c++
	 | @description : class gèrant les opérations sur les fichiers
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    class dirGc{
		use errorGc;                            //trait fonctions génériques
		
		protected $_dir;
		
		public function __construct($dir){
			$this->_dir = $dir;
		}
		
		public function setDir($dir){
			$this->_dir = $dir;
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