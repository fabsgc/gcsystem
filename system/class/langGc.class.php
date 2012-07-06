<?php
	/*\
	 | ------------------------------------------------------
	 | @file : langGc.class.php
	 | @author : fab@c++
	 | @description : class permettant la gestion de plusieurs langues
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    class langGc{
		use errorGc;                            //trait fonctions génériques
		
		protected $_lang = 'fr';
		protected $_langFile = true;
		protected $_domXml;
		protected $_sentence;
		protected $_content;
		
		public function __construct($lang){
			$this->_lang = $lang;
			$this->loadFile();
		}
		
		public function setLang($lang){
			$this->_lang = $lang;
			$this->_addError('fichier à ouvrir : '.$lang);
			$this->loadFile();
		}
		
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
		
		public function __destruct(){
		}
	}