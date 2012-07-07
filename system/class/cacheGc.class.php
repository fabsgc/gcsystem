<?php
	/**
	 * @file : cacheGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la mise en cache de façon générale
	 * @version : 2.0 bêta
	*/
	
	class cacheGc{
		use errorGc;                  //trait fonctions génériques
		
		protected $_name              ; //nom du cache
		protected $_nameFile          ; //nom du fichier de cache
		protected $_time              ; //temps de mise en cache
		protected $_val               ; //contenu à mettre en cache
		
		/**
		 * Constructeur de la classe. Configure les paramètres necéssaires à la création d'un fichier de cache
		 * @access	public
		 * @return	void
		 * @param string $name : nom du fichier de cache
		 * @param string $val : contenu du fichier de cache<br />
		 * @param int $time : temps de mise en cache du fichier. La valeur par défaut, 0 correspond à un fichier non mis en cache
		 * @since 2.0
		*/
		
		public function __construct($name, $val, $time=0){
			$this->_time = $time;
			$this->_name = $name;
			$this->_nameFile = CACHE_PATH.$name.'.cache';
			$this->_val = $val;
		}
		
		/**
		 * Création du cache
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
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
		
		/**
		 * Configuration du nom du cache
		 * @access	public
		 * @return	void
		 * @param string $name : nom du fichier de cache
		 * @since 2.0
		*/
		
		public function setName($name){
			$this->_name = $name;
			$this->_nameFileFile = CACHE_PATH.$name.'.cache';
		}
		
		/**
		 * Configuration du contenu du cache
		 * @access	public
		 * @return	void
		 * @param string $val : contenu du fichier de cache
		 * @since 2.0
		*/
		
		public function setVal($val){
			$this->_val = $val;
		}
		
		/**
		 * Configuration du temps de mise en cache
		 * @access	public
		 * @return	void
		 * @param int $time : temps de mise en cache
		 * @since 2.0
		*/
		
		public function setTime($time=0){
			$this->_time = $time;
		}
		
		/**
		 * Récupération du cache
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
	 
		public function getCache(){
			if(file_exists($this->_nameFile)){
				return unserialize($this->_uncompress(file_get_contents($this->_nameFile)));
			}
			else{
				$this->setCache();
			}
		}
	 
		/**
		 * Fonction permettant de savoir si le fichier de cache est périmé
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
	 
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
		
		/**
		 * Fonction permettant de savoir si le fichier de cache existe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function isExist(){
			if(file_exists($this->_nameFile)){
				return true;
			}
			else{
				return false;
			}
		}
		
		/**
		 * Compression du fichier de cache
		 * @access	public
		 * @return	void
		 * @param string $val : contenu à compresser
		 * @since 2.0
		*/
		
		protected function _compress($val){
			return gzcompress($val,9);
		}
		
		/**
		 * Décompression du fichier de cache
		 * @access	public
		 * @return	void
		 * @param string $val : contenu à décompresser
		 * @since 2.0
		*/
		
		protected function _uncompress($val){
			return gzuncompress($val);
		}
	}
?>