<?php
	/**
	 * @file : lang.class.php
	 * @author : fab@c++
	 * @description : class permettant la gestion de plusieurs langues
	 * @version : 2.2 bêta
	*/
	
	namespace system{
	    class lang{
			use error, general;
			
			protected $_lang     = DEFAULTLANG; // nom de la langue a utilise
			protected $_langFile = true       ; // indique si le fichier de langue est chargé ou non
			protected $_dom                ;

			const USE_NOT_TPL    = 0          ;
			const USE_TPL        = 1          ;
			
			/**
			 * Crée l'instance de la classe langue
			 * @access	public
			 * @param string $lang : le nom de la lang qui sera chargée
			 * @return	void
			 * @since 2.0
			*/
			
			public function __construct($lang){
				$this->_lang = $lang;
				$this->loadFile();
			}
			
			/**
			 * Configure la langue qui sera utilisée
			 * @access	public
			 * @param string $lang : le nom de la lang qui sera chargée
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
				if(is_file(LANG_PATH.$this->_lang.LANG_EXT) && file_exists(LANG_PATH.$this->_lang.LANG_EXT) && is_readable(LANG_PATH.$this->_lang.LANG_EXT)){
					$this->_langFile=true;

					$this->_dom = new htmlparser();				
					$this->_dom->load(file_get_contents(LANG_PATH.$this->_lang.LANG_EXT), false, false);
					$this->_addError('Le fichier de route " '.ROUTE.'" a bien été chargé', __FILE__, __LINE__, INFORMATION);

					return true;
				}
				else{
					$this->_lang = DEFAULTLANG;
					$this->_langFile=true;
					$this->_dom = new \DomDocument('1.0', CHARSET);

					if($this->_dom->load(LANG_PATH.$this->_lang.LANG_EXT)){
						$this->_langFile=true;
						return true;
					}
					else{
						$this->_langFile=false;
						return false;
					}
				}
			}
			
			/**
			 * Charge une phrase contenue dans un des fichiers de langues du framework (./system/lang/) en fonction de la langue choisie
			 * @access	public
			 * @param string $nom : le nom de la phrase à charger. Il correspondant à l'attribut id dans le fichier XML de langue
			 * @param array $var : les variables à utiliser dans la phrase
			 * @param bool template : utilisation de la syntaxe des templates
			 * @return string
			 * @since 2.0
			*/
			
			public function loadSentence($nom, $var = array(), $template = self::USE_NOT_TPL){
				$this->_content = "";
				if($this->_langFile==true){
					foreach ($this->_dom->find('sentence[id='.$nom.']') as $sentence) {
						if($template == self::USE_NOT_TPL){
							if(count($var) < 1){
								$this->_content = $sentence->innertext;
							}
							else{
								$this->_content = $sentence->innertext;
									
								foreach($var as $cle => $val){
									$this->_content = preg_replace('#\{'.$cle.'\}#isU', $val, $this->_content);
								}
							}

							break;
						}
						else{
							$t= new template($sentence->innertext, $nom, 0, $this->_lang, template::TPL_STRING);
							$t->assign(array($var));
							$t->setShow(false);
							$this->_content = $t->show();
						}
					}
					
					if($this->_content!=""){
						return ($this->_content);
					}
					else{
						$this->_addError('Le texte "'.$nom.'" n\'a pas été trouvé dans le fichier de lang "'.$this->_lang.'" ou une variable n\'a pas été remplie correctement', __FILE__, __LINE__, ERROR);
						return 'texte non trouvé ou vide ('.$nom.','.$this->_lang.')';
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
	}