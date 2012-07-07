<?php
	/**
	 * @file : langGc.class.php
	 * @author : fab@c++
	 * @description : class permettant la gestion de plusieurs langues
	 * @version : 2.0 bêta
	*/
	
    class langGc{
		use errorGc;                            //trait
		
		protected $_lang = 'fr'       ; // nom de la langue a utilise
		protected $_langFile = true   ; // indique si le fichier de langue est charge ou non
		protected $_domXml            ; // contient l'object DomDocument natif de PHP, permet la lecture des fichiers de langues
		protected $_sentence          ; // contient la phrase du fichier de langue à charger
		protected $_content           ; // variable interm&eacute;diaire utilis&eacute;e dans loadSentence
		
		/**
		 * Cr&eacute;e l'instance de la classe langue
		 * @access	public
		 * @param string $lang : le nom de la lang qui sera charg&eacute;e
		 * @return	void
		 * @since 2.0
		*/
		
		public function __construct($lang){
			$this->_lang = $lang;
			$this->loadFile();
		}
		
		/**
		 * Configure la langue qui sera utilis&eacute;e
		 * @access	public
		 * @param string $lang : le nom de la lang qui sera charg&eacute;e
		 * @return	void
		 * @since 2.0
		*/
		
		public function setLang($lang){
			$this->_lang = $lang;
			$this->_addError('fichier à ouvrir : '.$lang);
			$this->loadFile();
		}
		
		/**
		 * Charge le fichier de lang configure via setLang
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function loadFile(){
			if(is_file(LANG_PATH.$this->_lang.LANG_EXT)){
				$this->_langFile=true;
				$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
				if($this->_domXml->load(LANG_PATH.$this->_lang.LANG_EXT)){
					$this->_langFile=true;
					$this->_addError('fichier ouvert : '.$this->_lang);
				}
				else{
					$this->_langFile=false;
					$this->_addError('Le fichier de langue n\'a pas pu être ouvert.');
				}
			}
			else{
				$this->_addError('Le fichier de langue n\'a pas été trouvé, passage par la langue par défaut.');
				$this->_lang = DEFAULTLANG;
				$this->_langFile=true;
				$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
				if($this->_domXml->load(LANG_PATH.$this->_lang.LANG_EXT)){
					$this->_langFile=true;
					$this->_addError('fichier ouvert : '.$this->_lang);
				}
				else{
					$this->_langFile=false;
					$this->_addError('Le fichier de langue n\'a pas pu être ouvert.');
				}
			}
		}
		
		/**
		 * Charge une phrase contenue dans un des fichiers de langues du framework (./system/lang/) en fonction de la langue choisie
		 * @access	public
		 * @param string $nom : le nom de la phrase &agrave; charger. Il correspondant &agrave; l'attribut id dans le fichier XML de langue
		 * @return	boolean
		 * @since 2.0
		*/
		
		public function loadSentence($nom){
			if($this->_langFile==true){
				$blog = $this->_domXml->getElementsByTagName('lang')->item(0);
				$sentences = $blog->getElementsByTagName('sentence');
				
				foreach($sentences as $sentence){
					if ($sentence->getAttribute("id") == $nom){
						$this->_content =  $sentence->firstChild->nodeValue;
					}
				}
				
				if($this->_content!=""){
					return utf8_decode($this->_content);
				}
				else{
					return 'texte non trouvé';
				}
			}
			else{
				$this->_addError('Le fichier de langue ne peut pas être lu.');
			}
		}
		
		/**
		 * Desctructeur
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function __destruct(){
		}
	}