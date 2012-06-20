<?php
	class Cache{
		public function set($name, $val, $time=0){
			if(!file_exists(CACHE_PATH.$name.'.cache')){
				$fichier = fopen(CACHE_PATH.$name.'.cache', 'w+');
				fwrite($fichier, gzcompress(serialize($val), 9));
				fclose($fichier);
			}
		 
			$time_ago = time() - filemtime(CACHE_PATH.$name.'.cache');
		 
			if($time_ago > $time){
				$fichier = fopen(CACHE_PATH.$name.'.cache', 'w+');
				fwrite($fichier, gzcompress(serialize($val), 9));
				fclose($fichier);
			}
		}
	 
		public function get($name){
			if(file_exists(CACHE_PATH.$name.'.cache')){
				return unserialize(gzuncompress(file_get_contents(''.$name.'.cache')));
			}
			else{
				return "sdjfhj";
			}
		}
	 
		public function isDie($name, $time){
			$rep = false;
			if(!file_exists(CACHE_PATH.$name.'.cache')){
				$rep = false;
			}
			else{
				$time_ago = time() - filemtime(CACHE_PATH.$name.'.cache');
		 
				if($time_ago > $time){
					$rep = true;
				}
			}
			return $rep;
		}
		
		public function isExist($name){
			if(file_exists(CACHE_PATH.$name.'.cache')){
				return true;
			}
			else{
				return false;
			}
		}
	}
?>