<?php
	/*\
	 | ------------------------------------------------------
	 | @file : cacheGc.class.php
	 | @author : fab@c++
	 | @description : class gérant la mise en cache de façon générale
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class CacheGc{
		use errorGc;                  //trait fonctions génériques
		
		protected $_name              ; //nom du cache
		protected $_nameFile          ; //nom du fichier de cache
		protected $_time              ; //temps de mise en cache
		protected $_val               ; //contenu à mettre en cache
		
		public function __construct($name, $val, $time=0){
			$this->_time = $time;
			$this->_name = $name;
			$this->_nameFile = CACHE_PATH.$name.'.cache';
			$this->_val = $val;
		}
		
		public function setCache(){
			if(!file_exists($this->_nameFile)){
				$fichier = fopen($this->_nameFile, 'w+');
				fwrite($fichier, $this->_compress(serialize($this->_val)));
				fclose($fichier);
			}
		 
			$time_ago = time() - filemtime($this->_nameFile);
		 
			if($time_ago > $this->_time){
				$fichier = fopen($this->_nameFile, 'w+');
				fwrite($fichier, $this->_compress(serialize($this->_val)));
				fclose($fichier);
			}
		}
		
		public function setName($name){
			$this->_name = $name;
			$this->_nameFileFile = CACHE_PATH.$name.'.cache';
		}
		
		public function setVal($val){
			$this->_val = $val;
		}
		
		public function setTime($time=0){
			$this->_time = $time;
		}
	 
		public function getCache(){
			if(file_exists($this->_nameFile)){
				return unserialize($this->_uncompress(file_get_contents($this->_nameFile)));
			}
			else{
				$this->setCache();
			}
		}
	 
		public function isDie(){
			$rep = false;
			if(!file_exists($this->_nameFile)){
				$rep = false;
			}
			else{
				$time_ago = time() - filemtime($this->_nameFile);
		 
				if($time_ago > $this->_time){
					$rep = true;
				}
			}
			return $rep;
		}
		
		public function isExist(){
			if(file_exists($this->_nameFile)){
				return true;
			}
			else{
				return false;
			}
		}
		
		protected function _compress($val){
			return gzcompress($val,9);
		}
		
		protected function _uncompress($val){
			return gzuncompress($val);
		}
	}
?>