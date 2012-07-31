<?php
	/**
	 * @file : langGc.class.php
	 * @author : fab@c++
	 * @description : class permettant la gestion de plusieurs langues
	 * @version : 2.0 bêta
	*/
	
    class langGc{
		use errorGc;                            //trait
		
		protected $_lang = DEFAULTLANG; // nom de la langue a utilise
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
				$this->_domXml = new DomDocument('1.0', 'utf-8');
				if($this->_domXml->load(LANG_PATH.$this->_lang.LANG_EXT)){
					$this->_langFile=true;
				}
				else{
					$this->_langFile=false;
				}
			}
			else{
				$this->_lang = DEFAULTLANG;
				$this->_langFile=true;
				$this->_domXml = new DomDocument('1.0', CHARSET);
				if($this->_domXml->load(LANG_PATH.$this->_lang.LANG_EXT)){
					$this->_langFile=true;
				}
				else{
					$this->_langFile=false;
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
						if(CHARSET == strtolower('utf-8')) { $this->_content =  utf8_encode($sentence->firstChild->nodeValue); }
						else { $this->_content =  $sentence->firstChild->nodeValue; }
					}
				}
				
				if($this->_content!=""){
					return ($this->_content);
				}
				else{
					return 'texte non trouv&#233;';
				}
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