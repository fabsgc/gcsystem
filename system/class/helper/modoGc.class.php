<?php
	/**
	 * @file : modoGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le filtrage du contenu du site
	 * @version : 2.0 bêta
	*/
	
	class modoGc{
		use errorGc;                            //trait
		
		protected $_contenu                           ; //contenu à filtrer
		protected $_maxWord                  = 10     ; //contenu à filtrer
		protected $_insulte 				 = array(); //avec la participation de t1307
		protected $_parseInsulte             = array();
		protected $_i                        = array();
		
		protected $_domXml                            ; //pour la modification du fichier route
		protected $_nodeXml                           ;
		protected $_markupXml                         ;
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 * @access	public
		 * @return	void
		 * @param string $contenu : contenu à modérer
		 * @param string $maxword : nombre maximum de mot toléré, une valeur de 0 entraîne un nombre illimité d'insultes toléré
		 * @since 2.0
		*/
		
		public  function __construct($contenu, $maxword=0){
			$this->_contenu = $this->_setAccent(strval($contenu));
			$this->_maxWord = intval($maxword);
			$this->_insulte = $this->_setInsulte();
			
			echo $this->_contenu;
		}
		
		/**
		 * Fonction de parsage du message. Retourne soit true soit un array contenant la liste des insultes
		 * @access	public
		 * @return	array ou boolean
		 * @since 2.0
		*/
		
		public function parse(){
			$this->_parseInsulte = array();
			foreach($this->_insulte as $insulte){
				if(preg_match('`'.preg_quote($insulte).' `i', $this->_contenu)){
					array_push($this->_parseInsulte, $insulte);
				}
			}
			
			if(count($this->_parseInsulte) != 0){
				return $this->_parseInsulte;
			}
			else{
				return true;
			}
		}
		
		/**
		 * Fonction de censure du message, renvoie le texte censure ou non
		 * @access	public
		 * @return	string
		 * @since 2.0
		*/
		
		public function censure(){
			$this->_i            = 0;
			$this->_parseInsulte = array();
			foreach($this->_insulte as $insulte){
				if(preg_match('`'.preg_quote($insulte).' `i', $this->_contenu)){
					$this->_i++;
				}
			}
			
			if($this->_i++ > $this->_maxWord){
				$content = $this->_contenu;
				foreach($this->_insulte as $insulte){
					$content = preg_replace('`'.preg_quote($insulte).'`i', '***censuré***', $content);
				}
				return $content;
			}
			else{
				return $this->_contenu;
			}
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
		 * Configuration du tableau de mot vulgaure
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		protected function  _setInsulte(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->load(MODOGCCONFIG)){
				$this->_addError('fichier ouvert : '.MODOGCCONFIG);
				
				$this->_nodeXml = $this->_domXml->getElementsByTagName('insultes')->item(0);
				$sentences = $this->_nodeXml->getElementsByTagName('insulte');
				
				foreach($sentences as $sentence){
					if ($sentence->getAttribute("rubrique") == $this->_commandExplode[2]){
						if(CHARSET == strtolower('utf-8')) { $content =  utf8_encode($sentence->firstChild->nodeValue); }
						else { $content =  utf8_decode($sentence->firstChild->nodeValue); }
						array_push($this->_insulte, strtolower($content));
					}
				}
				
				print_r($this->_insulte);
			}
			else{
				$this->_addError('Le fichier '.MODOGCCONFIG.' n\'a pas pu être ouvert');
			}
		}
		
		protected function  _setAccent($contenu){
			$search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i');
			$replace = array ('e','a','i','u','o','c');
			$contenu = preg_replace($search, $replace, $contenu);
			return strtolower($contenu);
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