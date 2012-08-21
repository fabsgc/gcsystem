<?php
	/**
	 * @file : textGc.class.php
	 * @author : fab@c++
	 * @description : class gÃ©rant les text
	 * @version : 2.0 bÃªta
	*/
	
	class textGc{
		use errorGc;                                  //trait
		
		protected $_texte                                   ;
		protected $_accentSearch                = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i');
		protected $_accentReplace               = array ('e','a','i','u','o','c');
		protected $_urlSearch                   = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_]@');
		protected $_urlReplace                  = array ('e','a','i','u','o','c', ' ');
		
		/**
		 * Crée l'instance de la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public  function __construct($texte){
			$this->_texte = strval($texte);
		}
		
		public  function setText($texte){
			$this->_texte = strval($texte);
		}
		
		public function delAccent($texte=''){
			if($texte !='') $string = $texte; else $string = $this->_texte;
			
			return preg_replace($this->_accentSearch, $this->_accentReplace, $string); 
		}
		
		public function delUrl($texte=''){
			if($texte !='') $string = $texte; else $string = $this->_texte;
			
			return preg_replace($this->_urlSearch, $this->_urlReplace, $string); 
		}
		
		public function cleanCut($length,$cutString = '...', $texte=''){
			if($texte !='') $string = $texte; else $string = $this->_texte;
			
			$taille=strlen(html_entity_decode($string));
			if($taille<=$length){
				return $texte;
			}
			if($taille > $length){
				$str = substr($string,0,$length-strlen($cutString)+1);
				return substr($str,0,strrpos($str,' ')).$cutString;
			}
		}
		
		public function censure($censored = array(), $texte=''){
			if($texte !='') $string = $texte; else $string = $this->_texte;
			
			foreach($censored as $cle => $valeur){
				$string = preg_replace('`'.preg_quote($cle).'`isU', $valeur, $string); 
			}
			return $string; 
		}
		
		/**
		 * Desctructeur
		 * @access	public
		 * @return	boolean
		 * @since 2.0
		*/
		
		public  function __destruct(){
		
		}
	}