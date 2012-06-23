<?php
	/*\
	 | ------------------------------------------------------
	 | @file : lang.class.php
	 | @author : fab@c++
	 | @description : class permettant la gestion de plusieurs langues
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    class langGc{
		private $lang = 'fr';
		private $langFile = true;
		private $domXml;
		private $sentence;
		private $error = array();
		private $erreur ;
		private $content;
		
		public function __construct($lang){
			$this->lang = $lang;
			$this->loadFile();
		}
		
		public function setLang($lang){
			$this->lang = $lang;
			$this->addError('fichier à ouvrir : '.$lang);
			$this->loadFile();
		}
		
		public function loadFile(){
			if(is_file(LANG_PATH.$this->lang.LANG_EXT)){
				$this->langFile=true;
				$this->domXml = new DomDocument('1.0', 'iso-8859-15');
				if($this->domXml->load(LANG_PATH.$this->lang.LANG_EXT)){
					$this->langFile=true;
					$this->addError('fichier ouvert : '.$this->lang);
				}
				else{
					$this->langFile=false;
					$this->addError('Le fichier de langue n\'a pas pu être ouvert.');
				}
			}
			else{
				$this->addError('Le fichier de langue n\'a pas été trouvé, passage par la langue par défaut.');
				$this->lang = DEFAULTLANG;
				$this->langFile=true;
				$this->domXml = new DomDocument('1.0', 'iso-8859-15');
				if($this->domXml->load(LANG_PATH.$this->lang.LANG_EXT)){
					$this->langFile=true;
					$this->addError('fichier ouvert : '.$this->lang);
				}
				else{
					$this->langFile=false;
					$this->addError('Le fichier de langue n\'a pas pu être ouvert.');
				}
			}
		}
		
		public function loadSentence($nom){
			if($this->langFile==true){
				$blog = $this->domXml->getElementsByTagName('lang')->item(0);
				$sentences = $blog->getElementsByTagName('sentence');
				
				foreach($sentences as $sentence){
					if ($sentence->getAttribute("id") == $nom){
						$this->content =  $sentence->firstChild->nodeValue;
					}
				}
				
				if($this->content!=""){
					return utf8_decode($this->content);
				}
				else{
					return 'texte non trouvé';
				}
			}
			else{
				$this->addError('Le fichier de langue ne peut pas être lu.');
			}
		}
		
		public function showError(){
			foreach($this->error as $error){
				$this->erreur .=$error."<br />";
			}
			return $this->erreur;
		}
		
		private function checkEncoding($val){
			$val =  preg_replace('#Ã©#isU', 'é', $val);
			return $val;
		}
		
		private function addError($error){
			array_push($this->error, $error);
		}
		
		public function __destruct(){
		}
	}