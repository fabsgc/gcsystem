<?php
	/**
	 * @file : applicationGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les contrôleurs. abstraite
	 * @version : 2.0 bêta
	*/
	
	abstract class applicationGc{
		use errorGc, langInstance, generalGc, urlRegex,domGc                ; //trait
		/* --- infos d'en tete -- */
		
		protected $_doctype            = "<!DOCTYPE html>\n<html lang=\"fr\">"    ;
		protected $_title              = 'page web'                               ;
		protected $_metaContentType    = 'text/html; charset= UTF-8'              ;
		protected $_metaKeyword        = ''                                       ;
		protected $_metaDescription    = ''                                       ;
		protected $_metaRobot          = 'index,follow'                           ;
		protected $_metaGoogleSite     = ''                                       ;
		protected $_metaAuthor         = ''                                       ;
		protected $_metaCopyright      = ''                                       ;
		protected $_openSearch         = ''                                       ;
		protected $_js                 = array('script.js')                       ;
		protected $_css                = array('default.css')                     ;
		protected $_jsInFile           = array('inpage.js')                       ;
		protected $_rss                = array()                                  ;
		protected $_contentMarkupBody  = ''                                       ;
		protected $_localisation       = ''                                       ;
		protected $_otherHeader        =  array()                                 ;
		protected $_fbTitle            = ''                                       ;
		protected $_fbDescription      = ''                                       ;
		protected $_fbImage            = ''                                       ;
		protected $_html5              = true                                     ;
		
		protected $_devTool           = true                                     ;
		
		protected $_var               = array()                                  ; //contient les variables que l'on passe depuis l'extérieur : obsolète
		protected $bdd                                                           ; //contient la connexion sql
		
		protected $_header;
		protected $_footer;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public function __construct($lang=""){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
			if(CONNECTBDD == true) {$this->bdd=$this->_connectDatabase($GLOBALS['db']); }
			$this->_addError('Contrôleur '.$_GET['rubrique'].' initialisé', __FILE__, __LINE__);
		}
		
		protected function init(){
			
		}
		
		protected function loadModel(){
			$class = 'manager'.ucfirst($_GET['rubrique']);
			if(class_exists($class)){	
				$this->_addError('Model '.$_GET['rubrique'].' initialisé', __FILE__, __LINE__);
				$instance = new $class($this->_lang, $this->bdd);
				$instance->init();
				return $instance;
			}
		}
		
		protected function _connectDatabase($db){
			foreach ($db as $d){
				switch ($d['extension']){
					case 'pdo':
						try{
							$sql_connect[''.$d['database'].''] = new PDO($d['sgbd'].':host='.$d['hostname'].';dbname='.$d['database'], $d['username'], $d['password']);
						}
						catch (PDOException $e){
							$this->setErrorLog('errors_sql', 'Une exception a été lancée. Message d\'erreur lors de la connexion à une base de données : '.$e.'');
						}	
					break;
					
					case 'mysqli':
						$sql_connect[''.$d['database'].''] = new mysqli($d['hostname'], $d['username'], $d['password'], $d['database']);
					break;
					
					case 'mysql':
						$sql_connect[''.$d['database'].''] = mysql_connect($d['hostname'], $d['username'], $d['password']);
						$sql_connect[''.$d['database'].''] = mysql_select_db($d['database']);
					break;
					
					default :
						$this->setErrorLog('errors_sql', 'L\'extension de cette connexion n\'est pas gérée');
					break;
				}
			}
			return $sql_connect;
		}
		
		protected function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode))){
                    $this->$methode($valeur);
                }
            }
        }
			
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		protected function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		protected function getLang(){
			return $this->_lang;
		}
		
		protected function setVar($nom, $val){
			$this->_var[$nom] = $val;
		}
		
		protected function setVarArray($var){
			foreach($var as $cle => $val){
				$this->_var[$cle] = $val;
			}
		}
		
		protected function getVar($nom){
			if(isset($this->_var[$nom]))
				return $this->_var[$nom];
			else
				return false;
		}
		
		protected function unSetVar($nom){
			if(isset($this->_var[$nom]))
				unset($this->_var[$nom]);
			else
				return false;
		}
		
		protected function setDevTool($set){
			$this->_devTool = $set;
		}
		
		protected function getDevTool($set){
			return $this->_devTool;
		}
		
		protected function setLang($lang){
			$this->_lang=$lang;
			$this->_langInstance->setLang($this->_lang);
		}
		
		protected function setInfo($info=array()){
			foreach($info as $cle=>$info){
				switch($cle){
					case'doctype':
						switch($info){
							case 'xhtml11':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'xhtml1-strict':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'xhtml1-trans':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'xhtml1-frame':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'html5':
								$this->_doctype='<!DOCTYPE html>';
								$this->_doctype.="\n";
								$this->_doctype.='<html lang="fr">';
								$this->_html5 = true;
							break;
						}
					break;
					
					case 'title': 
						$this->_title=$info;
					break;

					case 'type': 
						$this->metacontenttype=$info;
					break;

					case 'key':
						$this->metakeyword=$info;
					break;

					case 'description':
						$this->_metaDescription=$info;
					break;

					case 'metarobot':
						$this->_metaRobot=$info;
					break;

					case 'metaauthor':
						$this->_metaAuthor=$info;
					break;
					
					case 'metacopyright':
						$this->_metaCopyright=$info;
					break;
					
					case 'metagooglesite':
						$this->_metaGoogleSite=$info;
					break;

					case 'opensearch':
						$this->_openSearch=$info;
					break;

					case 'js':
						$this->_js=$info;
					break;

					case 'css':
						$this->_css=$info;
					break;

					case 'jsinfile':
						$this->_jsinfile=$info;
					break;

					case 'rss':
						$this->_rss=$info;
					break;

					case 'contentmarkupbody':
						$this->contentmarkupbody=$info;
					break;

					case 'otherheader':
						$this->otherheader=$info;
					break;
					
					case 'localisation':
						$this->_localisation=$info;
					break;
					
					case 'lang':
						$this->_lang=$info;
					break;
					
					case 'fb_title':
						$this->_fbTitle=$info;
					break;
					
					case 'fb_desccription':
						$this->fbDesccription=$info;
					break;
					
					case 'fb_image':
						$this->_fbImage=$info;
					break;
				}
			}
		}
		
		protected function affHeader(){
			$this->_header.=$this->_doctype."\n";
			$this->_header.="  <head>\n";
			$this->_header.="    <title>".($this->_title)."</title>\n";
			if($this->_html5 == false){ $this->_header.="    <meta http-equiv=\"Content-Type\" content=\"".$this->_metaContentType."\" />\n"; }
				else { $this->_header.="    <meta charset=\"utf-8\" />\n"; }
			if($this->_html5 == false){ $this->_header.="    <meta http-equiv=\"content-language\" content=\"".$this->_lang."\"/>\n"; }
			$this->_header.="    <meta name=\"keywords\" content=\"".$this->_metaKeyword."\"/>\n";
			$this->_header.="    <meta name=\"description\" content=\"".$this->_metaDescription."\" />\n";
			$this->_header.="    <meta name=\"robots\" content=\"".$this->_metaRobot."\" />\n";
			$this->_header.="    <meta name=\"geo.placename\" content=\"".$this->_localisation."\" />\n";
			
			if($this->_fbTitle!=""){
				$this->_header.="    <meta property=\"og:description\" content=\"".$this->_fbTitle."\" />\n";
			}
			if($this->_fbDescription!=""){
				$this->_header.="    <meta property=\"og:description\" content=\"".$this->_fbDescription."\" />\n";
			}
			if($this->_fbImage!=""){
				$this->_header.="    <meta property=\"og:description\" content=\"".$this->_fbImage."\" />\n";
			}
			if($this->_metaGoogleSite!=""){
				$this->_header.="    <meta name=\"google-site-verification\" content=\"".$this->_metaGoogleSite."\" />\n";
			}
			if($this->_metaAuthor!=""){
				$this->_header.="    <meta name=\"Author\" content=\"".$this->_metaAuthor."\" />\n";
			}
			if($this->_metaCopyright!=""){
				$this->_header.="    <meta name=\"Copyright\" content=\"".$this->_metaCopyright."\" />\n";
			}
			if($this->_openSearch!=""){
				$this->_header.="    <link href=\"".$this->_openSearch."\"  rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"open search\" />\n";
			}
			
			if(is_file(FAVICON_PATH)){
				$this->_header.="     <link rel=\"icon\" type=\"image/png\" href=\"".FAVICON_PATH."\" />\n";
			}
			
			if(JQUERY==true){
				$this->_header.="    <script type=\"text/javascript\" src=\"".JQUERYFILE."\" ></script> \n";
				$this->_header.="    <script type=\"text/javascript\" src=\"".JQUERYUIJS."\" ></script> \n";
				$this->_header.="    <link href=\"".JQUERYUICSS."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
			}
			
			foreach($this->_js as $element){
				if(is_file(JS_PATH.$element)){
					$this->_header.="    <script type=\"text/javascript\" src=\"".JS_PATH.$element."\" ></script> \n";
				}
			}
			foreach($this->_css as $element){
				if(!preg_match('#http:#', $element)){
					if(is_file(CSS_PATH.$element)){
						$this->_header.="    <link href=\"".CSS_PATH.$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
					}
				}
				else{
					$this->_header.="    <link href=\"".$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
				}	
			}
			
			foreach($this->_jsInFile as $element){
				$this->_header.="    <script type=\"text/javascript\">\n";
				if(is_file(JS_PATH.$element)){
					$fichier=JS_PATH.$element;
					$contenu = fread(fopen($fichier, "r"), filesize($fichier));
					$this->_header.="    ".$contenu."\n";
				}
				$this->_header.="    </script>\n";
			}
			
			foreach($this->_rss as $element){
				if(is_file($element)){
					$this->_header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
				else{
					$this->_header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
			}
			
			if($this->_otherHeader){
				foreach($this->_otherHeader as $element){
					$this->_header.="    ".$element."\n";
				}
			}
			
			$this->_header.="  </head>\n";
			
			if($this->_contentMarkupBody!=""){
				$this->_header.="  <body ".$this->_contentMarkupBody.">\n";
			}
			else{
				$this->_header.="  <body>\n";
			}
			
			return $this->_header;			
		} 
		
		protected function affFooter(){
			$this->_footer="  </body>\n</html>";
			return $this->_footer;
		}
		
		protected function genererToken(){
			$token = uniqid(rand(), true);
			return $token;
		}
		
		protected function showDefault(){
			$t= new templateGC(GCSYSTEM_PATH.'GCnewrubrique', 'GCrubrique', '0');
			$t->assign(array(
				'rubrique' => $_GET['rubrique']
			));
			$t->show();
		}
		
		protected function affTemplate($nom_template){
			if(is_file(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT)) { 
				include(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT);
			} 
			else { 
				$this->setErrorLog('errors', 'Le template '.$nom_template.' n\'a pas été trouvé');
			}
		}
		
		public function __desctuct(){
		}
	}