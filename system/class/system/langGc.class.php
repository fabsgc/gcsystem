<?php
	/**
	 * @file : langGc.class.php
	 * @author : fab@c++
	 * @description : class permettant la gestion de plusieurs langues
	 * @version : 2.0 bêta
	*/
	
    class langGc{
		use errorGc, domGc;                            //trait
		
		protected $_lang = DEFAULTLANG; // nom de la langue a utilise
		protected $_langFile = true   ; // indique si le fichier de langue est charge ou non
		
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
				$this->_domXml = new DomDocument('1.0', CHARSET);
				if($this->_domXml->load(LANG_PATH.$this->_lang.LANG_EXT)){
					$this->_langFile=true;
					$this->_addError('Le fichier de langue "'.LANG_PATH.$this->_lang.LANG_EXT.'" semble être endommagé, ou innacessible', __FILE__, __LINE__, ERROR);
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
		
		public function loadSentence($nom, $var = array()){
			if($this->_langFile==true){
				$blog = $this->_domXml->getElementsByTagName('lang')->item(0);
				$sentences = $blog->getElementsByTagName('sentence');
				
				foreach($sentences as $sentence){
					if ($sentence->getAttribute("id") == $nom){
						if(count($var) < 1){
							$this->_content = $sentence->firstChild->nodeValue;
						}
						else{
							$this->_content = $sentence->firstChild->nodeValue;
							
							foreach($var as $cle => $val){
								$this->_content = preg_replace('#\{'.$cle.'\}#isU', $val, $this->_content);
							}
						}
					}
				}
				
				if($this->_content!=""){
					return ($this->_content);
				}
				else{
					$this->_addError('Le texte "'.$nom.'" n\'a pas été trouvé dans le fichier de lang "'.$this->$_lang.'"', __FILE__, __LINE__, ERROR);
					return 'texte non trouvé';
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