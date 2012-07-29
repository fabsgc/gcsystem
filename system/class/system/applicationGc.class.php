<?php
	/**
	 * @file : rubrique.class.php
	 * @author : fab@c++
	 * @description : class mère de l'application
	 * @version : 2.0 bêta
	*/
	
	abstract class applicationGc{
		use errorGc, langInstance, generalGc, urlRegex;                            //trait
		/* --- infos d'en tete -- */
		
		protected $doctype            = "<!DOCTYPE html>\n<html lang=\"fr\">"    ;
		protected $title              = 'page web'                               ;
		protected $metaContentType    = 'text/html; charset= UTF-8'              ;
		protected $metaKeyword        = ''                                       ;
		protected $metaDescription    = ''                                       ;
		protected $metaRobot          = 'index,follow'                           ;
		protected $metaGoogleSite     = ''                                       ;
		protected $openSearch         = ''                                       ;
		protected $js                 = array('script.js')                       ;
		protected $css                = array('default.css')                     ;
		protected $jsInFile           = array('inpage.js')                       ;
		protected $rss                = array()                                  ;
		protected $contentMarkupBody  = ''                                       ;
		protected $localisation       = ''                                       ;
		protected $otherHeader        =  array()                                 ;
		protected $fbTitle            = ''                                       ;
		protected $fbDescription      = ''                                       ;
		protected $fbImage            = ''                                       ;
		protected $html5              = true                                     ;
		protected $_domXml                                                       ;
		protected $_nodeXml                                                      ;
		protected $_markupXml                                                    ;
		
		protected $_devTool           = true                                     ;
		
		protected $_var               = array()                                  ;
		
		/* --- permet d'affiche le doctype et l'entete (avant la balise body) et </body></html> -- */
		
		protected $header;
		protected $footer;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct($lang=""){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
		}
		
		public function hydrate(array $donnees){
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
		
		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}
		
		public function setVar($nom, $val){
			$this->_var[$nom] = $val;
		}
		
		public function setVarArray($var){
			foreach($var as $cle => $val){
				$this->_var[$cle] = $val;
			}
		}
		
		public function getVar($nom){
			if(isset($this->_var[$nom]))
				return $this->_var[$nom];
			else
				return false;
		}
		
		public function unSetVar($nom){
			if(isset($this->_var[$nom]))
				unset($this->_var[$nom]);
			else
				return false;
		}
		
		/* ---------- SETTER --------- */
		
		public function setInfo($info=array()){
			foreach($info as $cle=>$info){
				switch($cle){
					case'doctype':
						switch($info){
							case 'xhtml11':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->html5 = false;
							break;
							
							case 'xhtml1-strict':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->html5 = false;
							break;
							
							case 'xhtml1-trans':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->html5 = false;
							break;
							
							case 'xhtml1-frame':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->html5 = false;
							break;
							
							case 'html5':
								$this->doctype='<!DOCTYPE html>';
								$this->doctype.="\n";
								$this->doctype.='<html lang="fr">';
								$this->html5 = true;
							break;
						}
					break;
					
					case 'title': 
						$this->title=$info;
					break;

					case 'type': 
						$this->metacontenttype=$info;
					break;

					case 'key':
						$this->metakeyword=$info;
					break;

					case 'description':
						$this->metadescription=$info;
					break;

					case 'metarobot':
						$this->metarobot=$info;
					break;

					case 'metagooglesite':
						$this->metagooglesite=$info;
					break;

					case 'opensearch':
						$this->opensearch=$info;
					break;

					case 'js':
						$this->js=$info;
					break;

					case 'css':
						$this->css=$info;
					break;

					case 'jsinfile':
						$this->jsinfile=$info;
					break;

					case 'rss':
						$this->rss=$info;
					break;

					case 'contentmarkupbody':
						$this->contentmarkupbody=$info;
					break;

					case 'otherheader':
						$this->otherheader=$info;
					break;
					
					case 'localisation':
						$this->localisation=$info;
					break;
					
					case 'lang':
						$this->_lang=$info;
					break;
					
					case 'fb_title':
						$this->fbTitle=$info;
					break;
					
					case 'fb_desccription':
						$this->fbDesccription=$info;
					break;
					
					case 'fb_image':
						$this->fbImage=$info;
					break;
				}
			}
		}
		
		public function setLang($lang){
			$this->_lang=$lang;
			$this->_langInstance->setLang($this->_lang);
		} 
		
		/* ---------- GETTER --------- */
		
		public  function __desctuct(){
		
		}
		
		/* ---------- FONCTIONS ------------- */
		
		public function affHeader(){
			$this->header.=$this->doctype."\n";
			$this->header.="  <head>\n";
			$this->header.="    <title>".($this->title)."</title>\n";
			if($this->html5 == false){ $this->header.="    <meta http-equiv=\"Content-Type\" content=\"".$this->metaContentType."\" />\n"; }
				else { $this->header.="    <meta charset=\"utf-8\" />\n"; }
			$this->header.="    <meta http-equiv=\"content-language\" content=\"fr\"/>\n";
			$this->header.="    <meta name=\"keywords\" content=\"".$this->metaKeyword."\"/>\n";
			$this->header.="    <meta name=\"description\" content=\"".$this->metaDescription."\" />\n";
			$this->header.="    <meta name=\"robots\" content=\"".$this->metaRobot."\" />\n";
			$this->header.="    <meta name=\"geo.placename\" content=\"".$this->localisation."\" />\n";
			
			if($this->fbTitle!=""){
				$this->header.="    <meta property=\"og:description\" content=\"".$this->fbTitle."\" />\n";
			}
			if($this->fbDescription!=""){
				$this->header.="    <meta property=\"og:description\" content=\"".$this->fbDescription."\" />\n";
			}
			if($this->fbImage!=""){
				$this->header.="    <meta property=\"og:description\" content=\"".$this->fbImage."\" />\n";
			}
			if($this->metaGoogleSite!=""){
				$this->header.="    <meta name=\"google-site-verification\" content=\"".$this->metaGoogleSite."\" />\n";
			}
			if($this->openSearch!=""){
				$this->header.="    <link href=\"".$this->openSearch."\"  rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"open search\" />\n";
			}
			
			if(is_file(FAVICON_PATH)){
				$this->header.="     <link rel=\"icon\" type=\"image/png\" href=\"".FAVICON_PATH."\" />\n";
			}
			
			if(JQUERY==true){
				$this->header.="    <script type=\"text/javascript\" src=\"".JQUERYFILE."\" ></script> \n";
				$this->header.="    <script type=\"text/javascript\" src=\"".JQUERYUIJS."\" ></script> \n";
				$this->header.="    <link href=\"".JQUERYUICSS."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
			}
			
			foreach($this->js as $element){
				if(is_file(JS_PATH.$element)){
					$this->header.="    <script type=\"text/javascript\" src=\"".JS_PATH.$element."\" ></script> \n";
				}
			}
			foreach($this->css as $element){
				if(!preg_match('#http:#', $element)){
					if(is_file(CSS_PATH.$element)){
						$this->header.="    <link href=\"".CSS_PATH.$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
					}
				}
				else{
					$this->header.="    <link href=\"".$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
				}	
			}
			
			foreach($this->jsInFile as $element){
				$this->header.="    <script type=\"text/javascript\">\n";
				if(is_file(JS_PATH.$element)){
					$fichier=JS_PATH.$element;
					$contenu = fread(fopen($fichier, "r"), filesize($fichier));
					$this->header.="    ".$contenu."\n";
				}
				$this->header.="    </script>\n";
			}
			
			foreach($this->rss as $element){
				if(is_file($element)){
					$this->header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
				else{
					$this->header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
			}
			
			if($this->otherHeader){
				foreach($this->otherHeader as $element){
					$this->header.="    ".$element."\n";
				}
			}
			
			$this->header.="  </head>\n";
			
			if($this->contentMarkupBody!=""){
				$this->header.="  <body ".$this->contentMarkupBody.">\n";
			}
			else{
				$this->header.="  <body>\n";
			}
			
			return $this->header;			
		} 
		
		public function affFooter(){
			$this->footer="  </body>\n</html>";
			return $this->footer;
		}
		
		public function genererToken(){
			$token = uniqid(rand(), true);
			return $token;
		}
		
		public function affTemplate($nom_template){
			if(is_file(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT)) { 
				include(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT);
			} 
			else { 
				$this->setErrorLog('errors', 'Le template '.$nom_template.' n\'a pas été trouvé');
			}
		}
	}