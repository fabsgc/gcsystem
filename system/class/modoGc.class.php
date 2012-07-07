<?php
	/**
	 * @file : modoGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le filtrage du contenu du site
	 * @version : 2.0 bêta
	*/
	
	class modoGc{
		use errorGc;                            //trait fonctions génériques
		
		protected $_contenu                       ; //contenu à filtrer
		protected $_maxWord                  = 10  ; //contenu à filtrer
		protected $_insulte                  = array(
			'salaud', 'merde', 'salope', 'pute', 'putain', 'fils de pute', 'enculé', 'connasse'); //array contenant toutes les erreurs enregistrées
		protected $_parseInsulte             = array();
		protected $_i                        = array();
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 * @access	public
		 * @return	void
		 * @param string $contenu : contenu à modérer
		 * @param string $maxword : nombre maximum de mot toléré, une valeur de 0 entraîne un nombre illimité d'insultes toléré
		 * @since 2.0
		*/
		
		public  function __construct($contenu, $maxword=0){
			$this->_contenu = strval($contenu);
			$this->_maxWord = intval($maxword);
		}
		
		/**
		 * Fonction de parsage du message
		 * @access	public
		 * @return	array ou boolean
		 * @since 2.0
		*/
		
		public function parse(){
			$this->_i = 0;
		}
		
		/**
		 * Fonction de censure du message
		 * @access	public
		 * @return	boolean
		 * @since 2.0
		*/
		
		public function censure(){
			$this->_i = 0;
		}
		
		/**
		 * Récupération du contenu du message
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getContenu(){
			return $this->_contenu;
		}
		
		/**
		 * Récupération du nombre de mot vulgaire maximum
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getMaxWord(){
			return $this->_maxWord;
		}
		
		/**
		 * Récupération du tableau contenant les insultes détectées
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getInsulte(){
			return $this->_parseInsulte;
		}
		
		/**
		 * Récupération du tableau contenant les insultes détectées en format html
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getInsulteHtml(){
			foreach($this->_parseInsulte as $valeur){
				$val .=$valeur.'<br />';
			}
			return $val;
		}
		
		/**
		 * Configuration du contenu à modérer
		 * @access	public
		 * @return	void
		 * @param string $contenu : contenu à modérer
		 * @since 2.0
		*/
		
		public function setContenu($contenu){
			$this->_contenu = strval($contenu);
			$this->_i            = 0      ;
			$this->_parseInsulte = array();
		}
		
		/**
		 * Configuration du nombre de mot vulgaire toléré, une valeur de 0 entraîne un nombre illimité d'insultes toléré
		 * @access	public
		 * @return	void
		 * @param string $max : nombre d'insultes toléré
		 * @since 2.0
		*/
		
		public function setMaxWord($max){
			$this->_maxWord = intval($maxu);
		}
		
		/**
		 * Desctructeur
		 * @access	public
		 * @return	boolean
		 * @since 2.0
		*/
		
		public  function __desctuct(){
		
		}
	}
?>