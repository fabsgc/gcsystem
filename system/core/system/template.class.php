<?php
	/**
	 * @file : template.class.php
	 * @author : fab@c++
	 * @description : class gérant le moteur de template
	 * @version : 2.2 bêta
	*/
	
	namespace system{
		class template{
			use error, langInstance, urlRegex, general;
			
			protected $_file               = ""         ;    //chemin vers le .tpl
			protected $_fileString         = ""         ;    //contenu du template
			protected $_fileCache          = ""         ;    //chemin vers le .compil.tpl
			protected $_name               = ""         ;    //nom du fichier compilé à créer
			protected $_content            = ""         ;    //contenu du fichier de template
			protected $_contentCompiled    = ""         ;    //contenu du fichier de template
			public    $vars                = array()    ;    //ensemble des variables
			protected $_refParser		   = null       ;    //contient une réfénrece vers l'instance du parser
			protected $_variable		   = ""         ;    //contient des variables
			protected $_timeCache		   = 0          ;    //contient le temps de mise en cache
			protected $_timeFile		   = 0          ;    //contient la date de dernière modif du template
			protected $_show		       = true       ;

			const TPL_FILE                 = 1          ;    //on peut charger un tpl à partir d'un fichier
			const TPL_STRING               = 2          ;    //on peut charger un tpl à partir d'une chaîne de caractères
			const TPL_COMPILE_ALL          = 1          ;    //du fait du système d'include, on compile soit les fichiers en entier soit juste les balises include
			const TPL_COMPILE_INCLUDE      = 2          ;
			
			public  function __construct($file="", $nom="", $timecache=0, $lang="fr", $stream = self::TPL_FILE){
				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();

				switch($stream){
					case self::TPL_FILE :
						$this->_file=TEMPLATE_PATH.$file.TEMPLATE_EXT;

						if(file_exists($this->_file) or is_readable($this->_file)){
							$handle = fopen($this->_file, 'rb');
							$this->_content = fread($handle, filesize ($this->_file));
							fclose ($handle);
							
							$this->_name=$nom;
							$this->_timeCache=$timecache;

							if(CACHE_SHA1 == 'true')
								$this->_fileCache=CACHE_PATH.sha1('template_'.$this->_name.'.tpl.compil.php.cache');
							else
								$this->_fileCache=CACHE_PATH.'template_'.$this->_name.'.tpl.compil.php.cache';

							$this->_setParser();
							$this->_addError('le fichier de template "'.$this->_name.'" ("'.$this->_file.'") a bien été chargé.', __FILE__, __LINE__, INFORMATION);
						} 
						else{
							$this->_addError('le fichier de template "'.$this->_name.'" ("'.$this->_file.'") spécifié n\'a pas été trouvé.', __FILE__, __LINE__, FATAL);
							$this->_name=$nom;
							$this->_timeCache=$timecache;
							$this->_fileCache=CACHE_PATH.'template_'.$this->_name.'.tpl.compil.php.cache';
							if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
							$this->_setParser();
						}
					break;

					case self::TPL_STRING :
						$this->_name=$nom;
						$this->_timeCache=$timecache;
						$this->_content = $file;

						if(CACHE_SHA1 == 'true')
							$this->_fileCache=CACHE_PATH.sha1('template_'.$this->_name.'.tpl.compil.php.cache');
						else
							$this->_fileCache=CACHE_PATH.'template_'.$this->_name.'.tpl.compil.php.cache';

						$this->_setParser();
						$this->_addError('le fichier de template "'.$this->_name.'" (chaîne de caractères) a bien été chargé.', __FILE__, __LINE__, INFORMATION);
					break;
				}
			}
			
			public function getFile(){
				return $this->_file;
			}

			public function getFileString(){
				return $this->_fileString;
			}
			
			public function getTimeCache(){
				return $this->_timeCache;
			}

			public function getFileCache(){
				return $this->_fileCache;
			}

			public function getName(){
				return $this->_name;
			}
			
			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}

			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
			}
			
			protected function _setParser(){
				$this->_refParser = new templateParser($this, $this->_lang);
			}
			
			public function assign($nom, $vars=null){
				if(is_array($nom)){ $this->vars = array_merge($this->vars, $nom);}
				else $this->vars[$nom] = $vars;
			}

			public function assignArray($nom, $vars){
				if(isset($this->vars[$nom]) && !is_array($this->vars[$nom])){
					$this->_addError('Vous avez écrasé "'.$nom.'" une variable par un array.', __FILE__, __LINE__, WARNING);
				}
				
				if(strpos($nom, '.')){
					$e = explode('.', $n);
					$b = '$this->vars';
					$c = count($e) -1;
					for ($i=0 ; $i<$c ; $i++) {
						$b .= '[\'' . $e[$i] . '\']';
						$c_b_p = 'count(' . $b . ') - 1';
						$b .= '[' . $c_b_p . ']';
					}
					$b .= '[\'' . $e[$c] . '\'][] = $vars;';
					eval($b);
				}else{
					$this->vars[$nom][] = $vars;
				}
			}
			
			protected function _compile($contenu, $typeCompile){
				switch ($typeCompile) {
					case self::TPL_COMPILE_ALL:
						return $this->_variable.$this->_refParser->parse($contenu);
					break;
					
					case self::TPL_COMPILE_INCLUDE:
						return $this->_variable.$this->_refParser->parseNoCall($contenu);
					break;
				}
			} 
			
			protected function _saveCache($contenu){
				$file = fopen($this->_fileCache, 'w+');
				fputs($file, ($contenu));
				fclose($file);
			}
			
			public function show($typeCompile = self::TPL_COMPILE_ALL){
				if($this->_show == true){
					$GLOBALS['appDev']->addTemplate($this->_file);

					if(is_file($this->_fileCache) && $this->_timeCache>0 && file_exists($this->_fileCache) && is_readable($this->_fileCache)){
						$this->_timeFile=filemtime($this->_fileCache);

						if(($this->_timeFile+$this->_timeCache)>time()){ //cache dépassé
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}

							include($this->_fileCache);
						}
						else{
							$this->_contentCompiled=$this->_compile($this->_content, $typeCompile);
							$this->_saveCache($this->_contentCompiled);
							
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}

							include($this->_fileCache);
						}
					}
					else{
						$this->_contentCompiled=$this->_compile($this->_content, $typeCompile);
						$this->_saveCache($this->_contentCompiled);
						
						foreach ($this->vars as $cle => $valeur){
							${$cle} = $valeur;
						}

						include($this->_fileCache);
					}
				}
				elseif($this->_show == false){
					$GLOBALS['appDev']->addTemplate($this->_file);

					if(is_file($this->_fileCache) && $this->_timeCache > 0 && file_exists($this->_fileCache) && is_readable($this->_fileCache)){
						$this->_timeFile=filemtime($this->_fileCache);

						if(($this->_timeFile+$this->_timeCache) > time()){ //cache non périmé
							ob_start ();
								foreach ($this->vars as $cle => $valeur){
									$cle = $valeur;
								}

								include($this->_fileCache);
							$out = ob_get_contents();
							ob_get_clean();
							return $out;
						}
						else{
							$this->_contentCompiled=$this->_compile($this->_content, $typeCompile);
							$this->_saveCache($this->_contentCompiled);

							ob_start ();
								foreach ($this->vars as $cle => $valeur){
									${$cle} = $valeur;
								}

								include($this->_fileCache);
							$out = ob_get_contents();
							ob_get_clean();
							return $out;
						}
					}
					else{
						$this->_contentCompiled=$this->_compile($this->_content, $typeCompile);
						$this->_saveCache($this->_contentCompiled);

						ob_start ();
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}

							include($this->_fileCache);
						$out = ob_get_contents();
						ob_get_clean();

						return($out);
					}
				}
			}
			
			public function setShow($show){
				$this->_show = $show;
			}

			public  function __destruct(){
			}
		}
		
		class templateParser{
			use error, langInstance, urlRegex, errorPerso ;
			
			protected $_template                            ;
			protected $_contenu                             ;
			protected $_space             = '\s*'           ;
			protected $_spaceR            = '\s+'           ;
			protected $_name              = 'gc:'           ;
			protected $_includeI          = 0               ;
			protected $_info                                ;
			
			/// les balises à parser
			protected $bal= array(
				'vars'           => array('{', '}', '{$', '}', '{{', '}}', 'variable', '{{gravatar:', '}}', '{{url:', '}}', '{{def:', '}}', '{{php:',  '}}', '{{lang:', '}}', '{{lang[template]:'),  // vars
				'include'        => array('include', 'file', 'cache'),                   // include
				'cond'           => array('if', 'elseif', 'else', 'cond'),               // condition
				'foreach'        => array('foreach', 'var', 'as', 'foreachelse'),        // boucle tableau
				'function'       => array('function', 'name'),                           // fonction
				'com'            => array('/#', '#/'),                                   // commentaire
				'switch'         => array('switch', 'case', 'cond', 'default'),          // switch
				'while'          => array('while', 'cond'),                              // while
				'for'            => array('for', 'var', 'boucle', 'cond'),               // for
				'spaghettis'     => array('continue', 'break', 'goto', 'from', 'to'),    // spaghettis
				'block'          => array('block', 'name'),                              // block de code
				'template'       => array('template', 'name', 'vars'),                   // fonction de template
				'call'           => array('call', 'block', 'template'),                  // fonction d'appel (block et template)
				'cache'          => array('cache', 'id', 'time'));                 			 // gestion des blocs mis en cache inline
			
			public  function __construct(template &$tplGC, $lang = ""){
				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_template=$tplGC;
				$this->_createLangInstance();
			}
			
			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
			}
			
			public function parse($c){
				$this->_contenu=$c;
				$this->_parseDebugStart();
				$this->_parseCache();
				$this->_parseInclude();
				$this->_parsevarsPhp();
				$this->_parsevarAdd();
				$this->_parseGravatar();
				$this->_parseUrlRegex();
				$this->_parseDefine();
				$this->_parseDefineClass();
				$this->_parseForeach();
				$this->_parseWhile();
				$this->_parseFor();
				$this->_parsevarsExist();
				$this->_parsevars();
				$this->_parsevarFunc();
				$this->_parseCond();
				$this->_parseSwitch();
				$this->_parseCom();
				$this->_parseFunc();
				$this->_parseSpaghettis();
				$this->_parseLang();
				$this->_parseException();
				$this->_parseBlock();
				$this->_parseTemplate();
				$this->_parseCall();
				$this->_parseDebugEnd();
				return $this->_contenu;
			}

			public function parseNoCall($c){
				$this->_contenu=$c;
				$this->_parseDebugStart();
				$this->_parseCache();
				$this->_parseInclude();
				$this->_parsevarsPhp();
				$this->_parsevarAdd();
				$this->_parseGravatar();
				$this->_parseUrlRegex();
				$this->_parseDefine();
				$this->_parseDefineClass();
				$this->_parseForeach();
				$this->_parseWhile();
				$this->_parseFor();
				$this->_parsevarsExist();
				$this->_parsevars();
				$this->_parsevarFunc();
				$this->_parseCond();
				$this->_parseSwitch();
				$this->_parseCom();
				$this->_parseFunc();
				$this->_parseSpaghettis();
				$this->_parseLang();
				$this->_parseException();
				$this->_parseDebugEnd();
				return $this->_contenu;
			}

			protected function _parseDebugStart(){
				$this->_contenu = preg_replace('`::`isU', '[debug||]', $this->_contenu);
			}

			protected function _parseDebugEnd(){
				$this->_contenu = preg_replace('`\[debug\|\|\]`isU', '::', $this->_contenu);
			}
			
			protected function _parsevars(){
				foreach ($this->_template->vars as $cle => $valeur){
					$array = '';
					if(is_array($valeur)){
						$array .= 'array(';

						foreach($valeur as $val){
							$array.=''.$val.',';
						}

						$array .= ')';

						$array = preg_replace('#,\)#isU', ')', $array);
						
						$variable = '$'.$cle.'='.$array.';';
					}
					else{
						$variable = '$'.$cle.'='.$valeur.';';
					}
					
					$this->_contenu = preg_replace('`'.preg_quote($this->bal['vars'][0]).$this->_space.$cle.$this->_space.preg_quote($this->bal['vars'][1]).'`', '<?php echo ($'.$cle.'); ?>', $this->_contenu);
				}
				$this->_contenu = preg_replace('`'.preg_quote($this->bal['vars'][0]).$this->_space.'([\[\]\(\)A-Za-z0-9\$\'._-]+)'.$this->_space.preg_quote($this->bal['vars'][1]).'`', '<?php echo ($$1); ?>', $this->_contenu);
			}
			
			protected function _parsevarFunc(){
				$this->_contenu = preg_replace('`'.preg_quote($this->bal['vars'][0]).$this->_space.'<gc:function(.+)>'.$this->_space.preg_quote($this->bal['vars'][1]).'`isU', '<?php echo <gc:function$1>; ?>', $this->_contenu);
			}
			
			protected function _parsevarsExist(){
				$this->_contenu = preg_replace('`'.preg_quote($this->bal['vars'][2]).$this->_space.'([\[\]\(\)A-Za-z0-9\$\'._-]+)'.$this->_space.preg_quote($this->bal['vars'][3]).'`', '<?php echo ($1); ?>', $this->_contenu);
			}

			protected function _parsevarsPhp(){
				$this->_contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][13]).'(.*)'.preg_quote($this->bal['vars'][14]).'`isU',
					array('system\templateParser','_parsePhpCallback'), $this->_contenu);
			}

			protected function _parsePhpCallback($m){
				return '<?php '.$m[1].' ?>';
			}
			
			protected function _parseDefine(){
				$this->_contenu = preg_replace('`'.preg_quote($this->bal['vars'][11]).'([\[\]A-Za-z0-9._-]+)'.preg_quote($this->bal['vars'][12]).'`sU', '<?php echo $1; ?>', $this->_contenu);
			}

			protected function _parseDefineClass(){
				$this->_contenu = preg_replace('`'.preg_quote($this->bal['vars'][4]).'(.+)::(.+)'.preg_quote($this->bal['vars'][5]).'`sU', '<?php echo $1::$2; ?>', $this->_contenu);
			}
			
			protected function _parseInclude(){
				$this->_contenu = preg_replace_callback(
					'`<'.$this->_name.preg_quote($this->bal['include'][0]).$this->_spaceR.preg_quote($this->bal['include'][1]).$this->_space.'='.$this->_space.'"(.+)"'.$this->_space.'((cache='.$this->_space.'"([0-9]*)"'.$this->_space.')*)'.$this->_space.'/>`isU', 
					array('system\templateParser','_parseIncludeCallback'), $this->_contenu);
			}

			protected function _parseIncludeCallback($m){
				$file[1] = TEMPLATE_PATH.$m[1].TEMPLATE_EXT;
				$content = "";
				if($this->_template->getFile() != $file[1]){
					if(file_exists($file[1]) or is_readable($file[1])){

						if(isset($m[4])){ //temps de cache précisé
							$t = new template($m[1], 'tplInclude_'.$this->_template->getName().'_'.$m[4].'_'.$this->_lang.'_'.$this->_includeI.'_', $m[4]);
						}
						else{
							$t = new template($m[1], 'tplInclude_'.$this->_template->getName().'_'.$this->_lang.'_'.$this->_includeI.'_', '0');
						}

						$t->assign($this->_template->vars);
						$t->setShow(false);
						$t->show(template::TPL_COMPILE_INCLUDE);
						if(file_get_contents($t->getFileCache())){
							$content = file_get_contents($t->getFileCache());
						}

						$this->_includeI++;
					}
					else{
						$this->_addError('Le template '.$file[1].' n\'a pas pu être inclus', __FILE__, __LINE__, FATAL);
					}	
				}

				return $content;
			}
			
			protected function _parseCond(){
				$this->_contenu = preg_replace(array(
						'`<'.$this->_name.preg_quote($this->bal['cond'][0]).$this->_spaceR.preg_quote($this->bal['cond'][3]).$this->_space.'='.$this->_space.'"(.+)"'.$this->_space.'>`sU',
						'`</'.$this->_name.preg_quote($this->bal['cond'][0]).$this->_space.'>`sU',
						'`<'.$this->_name.preg_quote($this->bal['cond'][1]).$this->_spaceR.preg_quote($this->bal['cond'][3]).'='.$this->_space.'"(.+)"'.$this->_space.'/>`sU',
						'`<'.$this->_name.preg_quote($this->bal['cond'][2]).$this->_space.'/>`sU',
					),array(
						'<?php if(\1) { ?>',
						'<?php } ?>',
						'<?php }elseif(\1){ ?>',
						'<?php }else{ ?>'
					),
					$this->_contenu);
			}
			
			protected function _parseSwitch(){
				$this->_contenu = preg_replace(array(
						'`<'.$this->_name.preg_quote($this->bal['switch'][0]).$this->_spaceR.preg_quote($this->bal['switch'][2]).$this->_space.'='.$this->_space.'"(.+)'.$this->_space.'"'.$this->_space.'>`sU',
						'`<'.$this->_name.preg_quote($this->bal['switch'][1]).$this->_space.'='.$this->_space.'"(.+)'.$this->_space.'"'.$this->_space.'>`sU',
						'`</'.$this->_name.preg_quote($this->bal['switch'][1]).$this->_space.'>`sU',
						'`</'.$this->_name.preg_quote($this->bal['switch'][0]).$this->_space.'>`sU',
						'`<'.$this->_name.preg_quote($this->bal['switch'][3]).$this->_space.'>`sU',
						'`</'.$this->_name.preg_quote($this->bal['switch'][3]).$this->_space.'>`sU',
					),array(
						'<?php switch(\1) { ',
						'case "\1" : ?>',
						'<?php break;',
						'} ?>',
						'default : ?>',
						'<?php break;'					
					),
					$this->_contenu);
			}
			
			protected function _parseCom(){
				$this->_contenu = preg_replace('`'.preg_quote($this->bal['com'][0]).'.+'.preg_quote($this->bal['com'][1]).'`isU', null, $this->_contenu);
			}
			
			protected function _parseFunc(){
				$this->_contenu = preg_replace_callback('`<'.$this->_name.preg_quote($this->bal['function'][0]).$this->_spaceR.preg_quote($this->bal['function'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.$this->_space.'(.*)'.$this->_space.'/>`isU', array('system\templateParser', '_parseFuncCallback'), $this->_contenu);
			}

			protected function _parseFuncCallback($m){
				$args = '';
				$func = '';

				//on récupère les arguments
				if($nb = preg_match_all('`(string|int|var)="(.+)"`U', $m[2], $arr)){
					for($i=0; $i<$nb; $i++){
						$args .= $arr[1][$i] == 'string' ? '"'.$arr[2][$i].'", ' : $arr[2][$i].', ';
					}
				}

				$args = substr($args, 0, strlen($args)-2);

				//on récupère les fonctions
				$m[1] = explode(',', $m[1]);
				$func = '<?php ';

				foreach ($m[1] as $fonctions) {
					$func .= trim($fonctions).'(';
				}

				$func .= $args;

				for ($i=0; $i < count($m[1]); $i++) { 
					$func .=')';
				}
				
				$func .= '; ?>';

				return $func;
			}
			
			protected function _parseForeach(){
				$this->_contenu = preg_replace(array(
						'`<'.$this->_name.preg_quote($this->bal['foreach'][0]).$this->_spaceR.preg_quote($this->bal['foreach'][1]).$this->_space.'="'.$this->_space.'(.+)'.$this->_space.'"'.$this->_spaceR.preg_quote($this->bal['foreach'][2]).$this->_space.'='.$this->_space.'"(.+)'.$this->_space.'"'.$this->_space.'>`sU',
						'`</'.$this->_name.preg_quote($this->bal['foreach'][0]).$this->_space.'>`sU',
						'`<'.$this->_name.preg_quote($this->bal['foreach'][3]).$this->_space.'/>`sU'
					),array(
						'<?php if(!empty($1)) { foreach(\1 as \2) { ?>',
						'<?php }} ?>',
						'<?php }} else { ?>'
					),
				$this->_contenu);
			}
			
			protected function _parseWhile(){
				$this->_contenu = preg_replace(array(
						'`<'.$this->_name.preg_quote($this->bal['while'][0]).$this->_spaceR.preg_quote($this->bal['while'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.$this->_space.'>`sU',
						'`</'.$this->_name.preg_quote($this->bal['while'][0]).$this->_space.'>`sU'
					),array(
						'<?php while(\1) { ?>',
						'<?php } ?>'
					),
				$this->_contenu);
			}
			
			protected function _parseFor(){
				$this->_contenu = preg_replace(array(
						'`<'.$this->_name.preg_quote($this->bal['for'][0]).$this->_spaceR.preg_quote($this->bal['for'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.$this->_spaceR.preg_quote($this->bal['for'][3]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.*)'.$this->_space.'"'.$this->_spaceR.preg_quote($this->bal['for'][2]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)-(.+)-(.+)'.$this->_space.'"'.$this->_space.'>`sU',
						'`</'.$this->_name.preg_quote($this->bal['for'][0]).$this->_space.'>`sU'
					),array(
						'<?php for(\1=\3;\1\2\4;\1=\1+\5) { ?>',
						'<?php } ?>'
					),
				$this->_contenu);
			}
		
			protected function _parseVarAdd(){
				$this->_contenu = preg_replace_callback(
					'`<'.$this->_name.preg_quote($this->bal['vars'][6]).$this->_spaceR.'(.+)'.$this->_space.'='.$this->_space.'(.+)'.$this->_space.'/>`sU',
					array('system\templateParser', '_parseVarAddCallBack'),
				$this->_contenu);
				
			}

			protected function _parseVarAddCallBack($m){
				ob_start ();
					eval('echo '.$m[2].';');
				$out = ob_get_contents();
				ob_get_clean();

				return "<?php $".$m[1]."=".$m[2]."; ?>";
			}
			
			protected function _parseSpaghettis(){
				$this->_contenu = preg_replace(array(
						'`<'.$this->_name.preg_quote($this->bal['spaghettis'][0]).$this->_space.'/>`sU',
						'`<'.$this->_name.preg_quote($this->bal['spaghettis'][1]).$this->_space.'/>`sU'/*,
						'`<'.$this->_name.preg_quote($this->bal['spaghettis'][2]).$this->_spaceR.preg_quote($this->bal['spaghettis'][3]).'="(.+)"/>`sU',
						'`<'.$this->_name.preg_quote($this->bal['spaghettis'][2]).$this->_spaceR.preg_quote($this->bal['spaghettis'][4]).'="(.+)"/>`sU'*/
					),array(
						'<?php continue; ?>',
						'<?php break; ?>'/*,
						'<?php goto $1; ?>',
						'<?php $1: ?>'*/
					),
				$this->_contenu);
			}
			
			protected function _parseLang(){
				$this->_contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][15]).'(.*)'.preg_quote($this->bal['vars'][16]).'`isU', array('system\templateParser', '_parseLangCallBack'), $this->_contenu);
				$this->_contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][17]).'(.*)'.preg_quote($this->bal['vars'][16]).'`isU', array('system\templateParser', '_parseLangTemplateCallBack'), $this->_contenu);
			}

			protected function _parseLangCallBack($m){
				$a = explode(':', $m[1]); //on sépare sentence et variable

				if(isset($a[1])){
					if(!preg_match('#\$#', $a[0]))
						return '<?php echo $this->useLang(\''.trim($a[0]).'\',array('.trim($a[1]).')); ?>'; //il faut mettre des '' aux strings
					else
						return '<?php echo $this->useLang('.trim($a[0]).',array('.trim($a[1]).')); ?>'; //il faut mettre des '' aux strings
				}
				else{
					if(!preg_match('#\$#', $a[0]))
						return '<?php echo $this->useLang(\''.trim($a[0]).'\',array()); ?>'; //il faut mettre des '' aux strings
					else
						return '<?php echo $this->useLang('.trim($a[0]).',array()); ?>'; //il faut mettre des '' aux strings
				}
			}

			protected function _parseLangTemplateCallBack($m){
				$a = explode(':', $m[1]); //on sépare sentence et variable

				if(isset($a[1])){
					if(!preg_match('#\$#', $a[0]))
						return '<?php echo $this->useLang(\''.trim($a[0]).'\',array('.trim($a[1]).'), lang::USE_TPL); ?>'; //il faut mettre des '' aux strings
					else
						return '<?php echo $this->useLang('.trim($a[0]).',array('.trim($a[1]).'), lang::USE_TPL); ?>'; //il faut mettre des '' aux strings
				}
				else{
					if(!preg_match('#\$#', $a[0]))
						return '<?php echo $this->useLang(\''.trim($a[0]).'\',array(), lang::USE_TPL); ?>'; //il faut mettre des '' aux strings
					else
						return '<?php echo $this->useLang('.trim($a[0]).',array(), lang::USE_TPL); ?>'; //il faut mettre des '' aux strings
				}
			}
			
			protected function _parseGravatar(){
				$this->_contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][7]).'(.+):(.+)'.preg_quote($this->bal['vars'][8]).'`sU',
				array('system\templateParser', '_parseGravatarCallback'), $this->_contenu);

				$this->_contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][7]).'(.+)'.preg_quote($this->bal['vars'][8]).'`sU',
				array('system\templateParser', '_parseGravatarCallback'), $this->_contenu);
			}
			
			protected function _parseGravatarCallback($m){			
				if(preg_match('#\$#', $m[1])){
					foreach ($this->_template->vars as $cle => $val){
						if(substr($m[1], 1, strlen($m[1])) == $cle){
							$m[1] = preg_replace('`'.$cle.'`', $val, $m[1]);
							$m[1] =  substr($m[1], 1, strlen($m[1]));
						}
					}
				}
				if(preg_match('#\$#', $m[2])){
					foreach ($this->_template->vars as $cle => $val){
						if(substr($m[2], 1, strlen($m[2])) == $cle){
							$m[2] = preg_replace('`'.$cle.'`', $val, $m[2]);
							$m[2] =  substr($var, 1, strlen($m[2]));
						}
					}
				}
				
				if(preg_match('#\'#', $m[1])){
					$m[1] = preg_replace('#\'#', '"', $m[1]);
					return '<?php echo \'http://secure.gravatar.com/avatar/\'.md5('.$m[1].').\'?s='.$m[2].'&d=identicon\'; ?>';
				}
				else{
					return '<?php echo \'http://secure.gravatar.com/avatar/\'.md5("'.$m[1].'").\'?s='.$m[2].'&d=identicon\'; ?>';
				}
			}
			
			protected function _parseUrlRegex(){
				if(preg_match('`'.preg_quote($this->bal['vars'][9]).'([\w]+):(.*)'.preg_quote($this->bal['vars'][10]).'`sU', $this->_contenu)){
					$this->_contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][9]).'(.*):(.+)'.preg_quote($this->bal['vars'][10]).'`sU',
					array('system\templateParser', '_parseUrlRegexCallback'), $this->_contenu);
				}
				else{
					$this->_contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][9]).'(.*)'.preg_quote($this->bal['vars'][10]).'`sU',
					array('system\templateParser', '_parseUrlRegexCallback'), $this->_contenu);
				}
			}
			
			protected function _parseUrlRegexCallback($m){
				if(isset($m[2])) $vars = explode(',', $m[2]);
				else $vars = array();

				$valeur = array();
				$array = '';
				foreach($vars as $var){
					if(preg_match('#\$#', $var)){
						foreach ($this->_template->vars as $cle => $val){
							if(substr($var, 1, strlen($var)) == $cle && !in_array($var, $valeur)){
								array_push($valeur, '$'.$cle);
							}
							
							if(!in_array($var, $valeur)){
								array_push($valeur, $var);
							}
						}
					}
					else{
						array_push($valeur, $var);
					}
				}

				$array .= 'array(';

				foreach($valeur as $val){
					$array.=''.$val.',';
				}

				$array .= ')';

				$array = preg_replace('#,\)#isU', ')', $array);

				return '<?php echo $this->getUrl(\''.$m[1].'\', '.$array.'); ?>';
			}

			protected function _parseBlock(){
				$this->_contenu = preg_replace_callback('`<'.$this->_name.preg_quote($this->bal['block'][0]).$this->_spaceR.preg_quote($this->bal['block'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)'.$this->_space.'"'.$this->_space.'>(.*)</'.$this->_name.$this->bal['block'][0].$this->_space.'>`isU', array('system\templateParser', '_parseBlockCallback'), $this->_contenu);
			}

			protected function _parseBlockCallback($m){
				if(!function_exists($m[1])){
					$blockFunction  = '<?php class template'.$m[1].' extends template { langInstance, urlRegex; public static function '.$m[1].'(){ ?> ';
					$blockFunction .= $m[2];
					$blockFunction .= ' <?php } } ?>';

					return $blockFunction;
				}
				else{
					$this->_addError('Le template '.$file[1].' n\'a pas pu être inclus', __FILE__, __LINE__, FATAL);
					return '';
				}
			}

			protected function _parseTemplate(){
				$this->_contenu = preg_replace_callback('`<'.$this->_name.preg_quote($this->bal['template'][0]).$this->_spaceR.preg_quote($this->bal['template'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)'.$this->_space.'"'.$this->_spaceR.$this->bal['template'][2].$this->_space.'='.$this->_space.'"'.$this->_space.'(.*)'.$this->_space.'"'.$this->_space.'>(.*)</'.$this->_name.$this->bal['template'][0].$this->_space.'>`isU', array('system\templateParser', '_parseTemplateCallback'), $this->_contenu);
				$this->_contenu = preg_replace_callback('`<'.$this->_name.preg_quote($this->bal['template'][0]).$this->_spaceR.preg_quote($this->bal['template'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)'.$this->_space.'"'.$this->_space.'>(.*)</'.$this->_name.$this->bal['template'][0].$this->_space.'>`isU', array('system\templateParser', '_parseTemplateCallbackNoVars'), $this->_contenu);
			}

			protected function _parseTemplateCallback($m){
				if(!function_exists($m[1])){
					$blockFunction  = '<?php class template'.$m[1].' extends system\template{ use \system\langInstance, \system\urlRegex; public function '.$m[1].'('.$m[2].'){ ?> ';
					$blockFunction .= $m[3];
					$blockFunction .= ' <?php } } ?>';

					return $blockFunction;
				}
				else{
					return '';
				}
			}

			protected function _parseTemplateCallbackNoVars($m){
				if(!function_exists($m[1])){
					$blockFunction  = '<?php class template'.$m[1].' extends system\template{ use \system\langInstance, \system\urlRegex; public function '.$m[1].'(){ ?> ';
					$blockFunction .= $m[2];
					$blockFunction .= ' <?php } } ?>';

					return $blockFunction;
				}
				else{
					return '';
				}
			}

			protected function _parseCall(){
				$this->_contenu = preg_replace_callback('`<'.$this->_name.preg_quote($this->bal['call'][0]).$this->_spaceR.preg_quote($this->bal['call'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)'.$this->_space.'"'.$this->_space.'/>`isU', array('system\templateParser', '_parseCallBlockCallback'), $this->_contenu);
				$this->_contenu = preg_replace_callback('`<'.$this->_name.preg_quote($this->bal['call'][0]).$this->_spaceR.preg_quote($this->bal['call'][2]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)'.$this->_space.'"'.$this->_space.'(.*)'.$this->_space.'/>`isU', array('system\templateParser', '_parseCallTemplateCallback'), $this->_contenu);
			}

			protected function _parseCallBlockCallback($m){
				return '<?php '.$m[1].'(); ?>';
			}

			protected function _parseCallTemplateCallback($m){
				$args = '';
				if($nb = preg_match_all('`(string|int|var)="(.+)"`U', $m[2], $arr)){
					for($i=0; $i<$nb; $i++){
						$args .= $arr[1][$i] == 'string' ? '"'.$arr[2][$i].'", ' : $arr[2][$i].', ';
					}
				}
				$args = substr($args, 0, strlen($args)-2);
				
				return '<?php (template'.$m[1].'::'.$m[1].'('.$args.')); ?>';
			}

			protected function _parseCache(){
				$html = new htmlparser();
				$html->load($this->_contenu, false, false);
				
				foreach($html->find('gc:cache') as $element){
					$element->innertext = preg_replace_callback('`^(.+)<id=(.+)><time=(.+)>$`isU', array('system\templateParser', '_parseCacheCallback'), $element->innertext.'<id='.$element->getAttribute('id').'><time='.$element->getAttribute('time').'>');
				}

				$this->_contenu = $html->outertext;
			}

			protected function _parseCacheCallback($m){
				$tpl = new template($m[1], $this->_template->getName().'_cache_'.$m[2], $m[3], $this->_lang, template::TPL_STRING);
				$tpl->setShow(false);
				return $tpl->show();
			}

			protected function _parseException(){
				$this->_contenu = preg_replace('#'.preg_quote('; ?>; ?>').'#isU', '; ?>', $this->_contenu);
				$this->_contenu = preg_replace('#'.preg_quote('<?php echo <?php').'#isU', '<?php echo', $this->_contenu);
			}

			public  function __destruct(){
			}
		}
	}