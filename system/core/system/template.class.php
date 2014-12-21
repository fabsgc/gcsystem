<?php
	/*\
	 | ------------------------------------------------------
	 | @file : template.class.php
	 | @author : fab@c++
	 | @description : template engine
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class template{
			use error, langInstance, facades, url;

			protected $_file                     ; //path to the .tpl file
			protected $_fileCache                ; //path to the .compil.tpl file
			protected $_name                     ; //template cache file name
			protected $_content                  ; //content of the .tpl
			protected $_contentCompiled          ; //compiled content of the .tpl
			public    $vars             = array(); //list of variables
			protected $_parser          =    null; //reference to the parser instance
			protected $_timeCache	    =       0; //time cache
			protected $_timeFile	    =       0; //last update of the .tpl
			protected $_stream		    =       1; //type of tpl stream : file or string

			const TPL_FILE              =       0; //we can load a .tpl as template
			const TPL_STRING            =       1; //we can load a string as template
			const TPL_COMPILE_ALL       =       0;
			const TPL_COMPILE_INCLUDE   =       1;
			const TPL_COMPILE_LANG      =       2;

			/**
			 * constructor
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @param $file string : file path or content
			 * @param $name string : template name
			 * @param $cache int : cache time
			 * @param $stream int : use a file or a string
			 * @param $lang string
			 * @throws exception if the tpl file can't be read
 			 * @since 3.0
			*/

			public function __construct (&$profiler, &$config, &$request, &$response, $lang, $file, $name = 'template', $cache = 0, $stream = self::TPL_FILE){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
				$this->_createLangInstance();

				$this->_file = $this->resolve(RESOLVE_TEMPLATE, $file).EXT_TEMPLATE;
				$this->_name      = $name;
				$this->_timeCache = $cache;
				$this->_stream    = $stream;

				if(CACHE_ENABLED == false)
					$this->_timeCache = 0;

				if(!preg_match('#(tplInclude)#isU', $name)){
					$stack = debug_backtrace(0);
					$trace = $this->getStackTraceToString($stack);
					$this->name .= $trace;
				}
				else{
					$trace = '';
					$this->name .= $trace;
				}

				if($this->_stream == self::TPL_FILE){
					if(file_exists($this->_file)){
						if (!file_exists(APP_CACHE_PATH_TEMPLATE))
							mkdir(APP_CACHE_PATH_TEMPLATE, 0755, true);

						$hash = sha1(preg_replace('#/#isU', '', $file));
					}
					else{
						throw new exception('can\'t open template file "'.$this->_file.'"', 1);
					}
				}
				else{
					$this->_content = $file;
					$hash = '';
				}

				if(CACHE_SHA1 == 'true')
					$this->_fileCache = APP_CACHE_PATH_TEMPLATE.sha1(substr($hash, 0, 10).'_template_'.$this->_name.EXT_COMPILED_TEMPLATE);
				else
					$this->_fileCache = APP_CACHE_PATH_TEMPLATE.substr($hash, 0, 10).'_template_'.$this->_name.EXT_COMPILED_TEMPLATE;

				$this->_setParser();
			}

			/**
			 * get the trace of execution. it's used to give an explicit name to the caching file
			 * @access protected
			 * @param $stack string
			 * @return string
			 * @since 3.0
			 */

			private function getStackTraceToString($stack){
				$max = 0;
				$trace = '';

				for($i = 3; $i < count($stack) && $max < 4; $i++){
					if(preg_match('#('.preg_quote('system\orm').')#isU', $stack[$i]['file'])){ //ORM
						$trace .= str_replace('\\', '-', $stack[$i]['class']).'_'.$stack[$i]['function'].'_'.$stack[$i-1]['line'].'__';
					}
				}

				return $trace;
			}

			/**
			 * initialize the parser instance reference
			*/

			protected function _setParser(){
				$this->_parser = $this->templateParser($this);
			}

			/**
			 * insert variable
			 * @param $name
			 * @param $vars
			 * @return void
			 * @since 3.0
			*/

			public function assign($name, $vars = ''){
				if(is_array($name))
					$this->vars = array_merge($this->vars, $name);
				else
					$this->vars[$name] = $vars;
			}

			/**
			 * compile the template instance
			 * @param $content string
			 * @param $type int
			 * @return mixed
			 * @since 3.0
			*/

			protected function _compile($content, $type = self::TPL_COMPILE_ALL){
				switch ($type) {
					case self::TPL_COMPILE_ALL:
						return $this->_parser->parse($content);
					break;

					case self::TPL_COMPILE_INCLUDE:
						return $this->_parser->parseNoTemplate($content);
					break;

					case self::TPL_COMPILE_LANG:
						return $this->_parser->parseLang($content);
					break;
				}
			}

			/**
			 * save content in cache file
			 * @param $content
			 * @return void
			 * @since 3.0
			*/

			protected function _save($content){
				file_put_contents($this->_fileCache, $content);
			}

			public function show($type = self::TPL_COMPILE_ALL){
				$this->profiler->addTime('template '.$this->_name);
				$this->profiler->addTemplate($this->_name, profiler::TEMPLATE_START, $this->_file);

				foreach ($this->vars as $cle => $valeur){
					${$cle} = $valeur;
				}

				if($this->_timeCache > 0 && file_exists($this->_fileCache)){
					$this->_timeFile = filemtime($this->_fileCache);

					if(($this->_timeFile + $this->_timeCache) <= time()){
						if($this->_stream == self::TPL_FILE){
							$this->_content = file_get_contents($this->_file);
						}

						$this->_contentCompiled = $this->_compile($this->_content, $type);
						$this->_save($this->_contentCompiled);
					}
				}
				else{
					if($this->_stream == self::TPL_FILE){
						$this->_content = file_get_contents($this->_file);
					}

					$this->_contentCompiled = $this->_compile($this->_content, $type);
					$this->_save($this->_contentCompiled);
				}

				if($type != self::TPL_COMPILE_INCLUDE)
					require_once($this->_fileCache);

				$this->profiler->addTemplate($this->_name, profiler::TEMPLATE_END, $this->_file);
				$this->profiler->addTime('template '.$this->_name, profiler::USER_END);
			}

			/**
			 * get file path
			 * @return string
			*/

			public function getFile(){
				return $this->_file;
			}

			/**
			 * get file path cache
			 * @return string
			*/

			public function getFileCache(){
				return $this->_fileCache;
			}

			/**
			 * get tpl name
			 * @return string
			*/

			public function getName(){
				return $this->_name;
			}

			/**
			 * destructor
			 * @access public
			 * @return string
			 * @since 3.0
			*/

			public function __destruct(){
			}
		}

		/**
		 * Class templateParser
		*/

		class templateParser{
			use error, langInstance, url, facades;

			protected $_template         ;
			protected $_content          ;
			protected $_space     = '\s*';
			protected $_spaceR    = '\s+';
			protected $_name      = 'gc:';
			protected $_includeI  =     0;

			/**
			 * list of template language markup elements
			 * @var array
			*/

			protected $markup = array(
				'vars'         => array('{', '}', '}}', '}_}', '{{gravatar:', '{{php:', '{{url', '{{lang', '{_{url', '{_{lang'),  // vars
				'include'      => array('include', 'file', 'cache', 'compile', 'false'), // include
				'condition'    => array('if', 'elseif', 'else', 'condition'),            // condition
				'foreach'      => array('foreach', 'var', 'as'),                         // foreach
				'function'     => array('function', 'call'),                             // function
				'for'          => array('for', 'condition'),                             // for
				'block'        => array('block', 'name'),                                // block (function)
				'template'     => array('template', 'name', 'vars'),                     // template (class)
				'call'         => array('call', 'block', 'template'),                    // call block or template
				'assetManager' => array('asset', 'type', 'files', 'cache'),              // css/js manger
				'minify'   	  => array('minify')                                         // minify part of code
			);

			/**
			 * constructor
			 * @access public
			 * * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @param $tpl \system\template
			 * @since 3.0
			*/

			public function __construct(&$profiler, &$config, &$request, &$response, $lang, template $tpl){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
				$this->_createLangInstance();
				$this->_template = $tpl;
			}

			/**
			 * classic parsing
			 * @access public
			 * @param $content string
			 * @return string
			 * @since 3.0
			*/

			public function parse($content){
				$this->_content = $content;
				$this->_parseDebugStart();
				$this->_parseInclude();
				$this->_parseGravatar();
				$this->_parseUrl();
				$this->_parsePhp();
				$this->_parseLang();
				$this->_parseForeach();
				$this->_parseFor();
				$this->_parseVar();
				$this->_parseVarFunc();
				$this->_parseCondition();
				$this->_parseFunction();
				$this->_parseException();
				$this->_parseBlock();
				$this->_parseTemplate();
				$this->_parseCall();
				$this->_parseAssetManager();
				$this->_parseMinify();
				$this->_parseDebugEnd();
				return $this->_content;
			}

			/**
			 * parsing without block and template
			 * @access public
			 * @param $content string
			 * @return string
			 * @since 3.0
			*/

			public function parseNoTemplate($content){
				$this->_content = $content;
				$this->_parseDebugStart();
				$this->_parseInclude();
				$this->_parseGravatar();
				$this->_parseUrl();
				$this->_parsePhp();
				$this->_parseLang();
				$this->_parseForeach();
				$this->_parseFor();
				$this->_parseVar();
				$this->_parseVarFunc();
				$this->_parseCondition();
				$this->_parseFunction();
				$this->_parseException();
				$this->_parseAssetManager();
				$this->_parseMinify();
				$this->_parseDebugEnd();
				return $this->_content;
			}

			/**
			 * parsing for lang
			 * @access public
			 * @param $content string
			 * @return string
			 * @since 3.0
			*/

			public function parseLang($content){
				$this->_content = $content;
				$this->_parseDebugStart();
				$this->_parseGravatar();
				$this->_parseUrl();
				$this->_parsePhp();
				$this->_parseLang();
				$this->_parseForeach();
				$this->_parseFor();
				$this->_parseVar();
				$this->_parseVarFunc();
				$this->_parseCondition();
				$this->_parseFunction();
				$this->_parseException();
				$this->_parseDebugEnd();
				return $this->_content;
			}

			/**
			 * parse include :
			 * 		<gc:include file="" cache="" />
			 * 		<gc:include file="" compile="false" cache="" />
			 * @access protected
			 * @return void
			 * @since 3.0
			 */

			protected function _parseInclude(){
				$this->_content = preg_replace_callback(
					'`<'.$this->_name.preg_quote($this->markup['include'][0]).$this->_spaceR.preg_quote($this->markup['include'][1]).$this->_space.'='.$this->_space.'"([A-Za-z0-9_\-\$/]+)"'.$this->_space.'(('.preg_quote($this->markup['include'][2]).$this->_space.'='.$this->_space.'"([0-9]*)"'.$this->_space.')*)'.$this->_space.'/>`isU',
					array('system\templateParser','_parseIncludeCallback'), $this->_content);
				$this->_content = preg_replace_callback(
					'`<'.$this->_name.preg_quote($this->markup['include'][0]).$this->_spaceR.preg_quote($this->markup['include'][1]).$this->_space.'='.$this->_space.'"([A-Za-z0-9_\-\$/]+)"'.$this->_space.preg_quote($this->markup['include'][3]).$this->_space.'='.$this->_space.'"'.preg_quote($this->markup['include'][4]).'"'.$this->_space.'(('.preg_quote($this->markup['include'][2]).$this->_space.'='.$this->_space.'"([0-9]*)"'.$this->_space.')*)'.$this->_space.'/>`isU',
					array('system\templateParser','_parseIncludeCompileCallback'), $this->_content);
			}

			/**
			 * parse include callback
			 * @access protected
			 * @param $m
			 * @return string
			 * @since 3.0
			*/

			protected function _parseIncludeCallback($m){
				$file = $this->resolve(RESOLVE_TEMPLATE, $m[1]).EXT_TEMPLATE;

				$content = "";
				if($this->_template->getFile() != $file){
					if(file_exists($file)){
						if(isset($m[4])) //precised time cache
							$t = $this->template($m[1], 'tplInclude_'.$this->_template->getName().'_'.$m[4].'_'.$this->lang.'_'.$this->_includeI.'_', $m[4]);
						else
							$t = $this->template($m[1], 'tplInclude_'.$this->_template->getName().'_'.$this->lang.'_'.$this->_includeI.'_', '0');

						$t->assign($this->_template->vars);
						$t->show(template::TPL_COMPILE_INCLUDE);

						if(file_get_contents($t->getFileCache()))
							$content = file_get_contents($t->getFileCache());

						$this->_includeI++;
					}
					else{
						$this->addError('Template '.$file.' can\'t be included', __FILE__, __LINE__, ERROR_FATAL);
					}
				}

				return $content;
			}

			/**
			 * parse include no compiled callback
			 * @access protected
			 * @param $m
			 * @return string
			 * @since 3.0
			*/

			protected function _parseIncludeCompileCallback($m){
				$data = '<?php ';

				if(!preg_match('#^\$#', $m[1]))
					$m[1] = '"'.$m[1].'"';


				if(isset($m[4])) //precised time cache
					$data .= '$t = $this->template('.$m[1].', "tplInclude_'.$m[4].'_'.$this->_includeI.'_", "'.$m[4].'"); '."\n";
				else
					$data .= '$t = $this->template('.$m[1].', "tplInclude_'.$this->_includeI.'_", "0"); '."\n";

				foreach($this->_template->vars as $key => $value){
					$data .= '$t->assign("'.$key.'", $'.$key.'); '."\n";
				}

				$data .= '$t->show(); '." ?>";

				return $data;
			}

			/**
			 * parse gravatar {{gravatar:email:size}}
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseGravatar(){
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][4]).'(.+):(.+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\templateParser', '_parseGravatarCallback'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][4]).'(.+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\templateParser', '_parseGravatarCallback'), $this->_content);
			}

			/**
			 * parse gravatar callback
			 * @access protected
			 * @param $m
			 * @return string
			 * @since 3.0
			 */

			protected function _parseGravatarCallback($m){
				if(preg_match('#^\$#', $m[1])){
					foreach ($this->_template->vars as $key => $val){
						if(substr($m[1], 1, strlen($m[1])) == $key){
							$m[1] = preg_replace('`'.$key.'`', $val, $m[1]);
							$m[1] =  substr($m[1], 1, strlen($m[1]));
						}
					}
				}
				if(isset($m[2]) && preg_match('#^\$#', $m[2])){
					foreach ($this->_template->vars as $key => $val){
						if(substr($m[2], 1, strlen($m[2])) == $key){
							$m[2] = preg_replace('`'.$key.'`', $val, $m[2]);
							$m[2] =  substr($m[2], 1, strlen($m[2]));
						}
					}
				}
				else{
					$m[2] = 100;
				}

				if(preg_match('#\'#', $m[1])){
					$m[1] = preg_replace('#\'#', '"', $m[1]);
					return '<?php echo \'http://secure.gravatar.com/avatar/\'.md5('.$m[1].').\'?s='.$m[2].'&d=identicon\'; ?>';
				}
				else{
					return '<?php echo \'http://secure.gravatar.com/avatar/\'.md5("'.$m[1].'").\'?s='.$m[2].'&d=identicon\'; ?>';
				}
			}

			/**
			 * parse url :
			 * 		{{url:id:vars}}
			 * 		{{url[absolute]:id:vars}}
			 *      {_{url:id:vars}_}
			 * 		{_{url[absolute]:id:vars}_}
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseUrl(){
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\templateParser', '_parseUrlCallback'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\:)([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU',array('system\templateParser', '_parseUrlCallback'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\:)([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);

				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\[absolute\]\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\templateParser', '_parseUrlCallback'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\[absolute\]\:)([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU',array('system\templateParser', '_parseUrlCallback'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\[absolute\]\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\[absolute\]\:)([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);
			}

			/**
			 * parse url for both url functions
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _parseUrlCallbackNormal($m){
				if(isset($m[3]))
					$vars = explode(',', $m[3]);
				else
					$vars = array();

				$array = 'array(';

				foreach($vars as $val){
					$array.=''.$val.',';
				}

				$array .= ')';
				$array = preg_replace('#,\)#isU', ')', $array);

				return array($m[2], $array);
			}

			/**
			 * parse url classic
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _parseUrlCallback($m){
				if($m[1] == ':')
					$type = '';
				else
					$type= '"http://'.$_SERVER['HTTP_HOST'].'".';

				$data = $this->_parseUrlCallbackNormal($m);
				return '<?php echo '.$type.'$this->getUrl(\''.$data[0].'\', '.$data[1].'); ?>';
			}

			/**
			 * parse url no echo
			 * @param $m array
			 * @return string
			 * @since 3.0
			 */

			protected function _parseUrlCallbackNoEcho($m){
				if($m[1] == ':')
					$type = '';
				else
					$type= '"http://'.$_SERVER['HTTP_HOST'].'".';

				$data = $this->_parseUrlCallbackNormal($m);
				return  $type.'$this->getUrl(\''.$data[0].'\', '.$data[1].')';
			}

			/**
			 * parse php {{php:code}}
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parsePhp(){
				$this->_content = preg_replace('`'.preg_quote($this->markup['vars'][5]).'(.*)'.preg_quote($this->markup['vars'][2]).'`isU', '<?php $1 ?>', $this->_content);
			}

			/**
			 * parse lang :
			 * 		{{lang:id:vars}}
			 * 		{{lang[absolute]:id:vars}}
			 *      {_{lang:id:vars}_}
			 * 		{_{lang[template]:id:vars}_}
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseLang(){
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][7]).'(\:)(.*)'.preg_quote($this->markup['vars'][2]).'`isU', array('system\templateParser', '_parseLangCallBack'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][7]).'(\[template\]\:)(.+)'.preg_quote($this->markup['vars'][2]).'`isU', array('system\templateParser', '_parseLangCallBack'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][9]).'(\:)(.*)'.preg_quote($this->markup['vars'][3]).'`isU', array('system\templateParser', '_parseLangCallBackNoEcho'), $this->_content);
				$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][9]).'(\[template\]\:)(.+)'.preg_quote($this->markup['vars'][3]).'`isU', array('system\templateParser', '_parseLangCallBackNoEcho'), $this->_content);
			}

			/**
			 * parse lang classic
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _parseLangCallBack($m){
				$a = explode(':', $m[2]); //we separate the two sections

				if($m[1] == ':')
					$type = '';
				else
					$type= ', \system\lang::USE_TPL';

				if(isset($a[1])){
					if(!preg_match('#\$#', $a[0]))
						return '<?php echo $this->useLang(\''.trim($a[0]).'\',array('.trim($a[1]).')'.$type.'); ?>';
					else
						return '<?php echo $this->useLang('.trim($a[0]).',array('.trim($a[1]).')'.$type.'); ?>';
				}
				else{
					if(!preg_match('#\$#', $a[0]))
						return '<?php echo $this->useLang(\''.trim($a[0]).'\',array()'.$type.'); ?>';
					else
						return '<?php echo $this->useLang('.trim($a[0]).',array()'.$type.'); ?>';
				}
			}

			/**
			 * parse lang classic no echo
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _parseLangCallBackNoEcho($m){
				$a = explode(':', $m[2]); //we separate the two sections

				if($m[1] == ':')
					$type = '';
				else
					$type= ', \system\lang::USE_TPL';

				if(isset($a[1])){
					if(!preg_match('#\$#', $a[0]))
						return '$this->useLang(\''.trim($a[0]).'\',array('.trim($a[1]).')'.$type.')';
					else
						return '$this->useLang('.trim($a[0]).',array('.trim($a[1]).')'.$type.')';
				}
				else{
					if(!preg_match('#\$#', $a[0]))
						return '$this->useLang(\''.trim($a[0]).'\',array()'.$type.')';
					else
						return '$this->useLang('.trim($a[0]).',array()'.$type.')';
				}
			}

			/**
			 * parse foreach <gc:foreach var="" as=""></gc:foreach>
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseForeach(){
				$this->_content = preg_replace(array(
					'`<'.$this->_name.preg_quote($this->markup['foreach'][0]).$this->_spaceR.preg_quote($this->markup['foreach'][1]).$this->_space.'="'.$this->_space.'(.+)'.$this->_space.'"'.$this->_spaceR.preg_quote($this->markup['foreach'][2]).$this->_space.'='.$this->_space.'"(.+)'.$this->_space.'"'.$this->_space.'>`sU',
					'`</'.$this->_name.preg_quote($this->markup['foreach'][0]).$this->_space.'>`sU'
				),array(
					'<?php if(!empty($1)) { foreach(\1 as \2) { ?>',
					'<?php }} ?>'
				),
				$this->_content);
			}

			/**
			 * parse for <gc:for cond=""></gc:for>
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseFor(){
				$this->_content = preg_replace(array(
					'`<'.$this->_name.preg_quote($this->markup['for'][0]).$this->_spaceR.preg_quote($this->markup['for'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.$this->_space.'>`sU',
					'`</'.$this->_name.preg_quote($this->markup['for'][0]).$this->_space.'>`sU'
				),array(
					'<?php for($1) { ?>',
					'<?php } ?>'
				),
				$this->_content);
			}

			/**
			 * parse vars
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseVar(){
				$this->_content = preg_replace('`'.preg_quote($this->markup['vars'][0]).$this->_space.'([\[\]\(\)A-Za-z0-9\$\'._>\+-]+)'.$this->_space.preg_quote($this->markup['vars'][1]).'`', '<?php echo ($1); ?>', $this->_content);
			}

			/**
			 * parse echo function result
			 * 		{<gc:function call=""/>}
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseVarFunc(){
				$this->_content = preg_replace('`'.preg_quote($this->markup['vars'][0]).$this->_space.'<gc:function(.+)>'.$this->_space.preg_quote($this->markup['vars'][1]).'`isU', '<?php echo <gc:function$1>; ?>', $this->_content);
			}

			/**
			 * parse condition :
			 * 		<gc:if condition="">
			 * 		<gc:elseif condition="">
			 * 		<gc:else/>
			 * 		</gc:else>
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseCondition(){
				$this->_content = preg_replace(array(
					'`<'.$this->_name.preg_quote($this->markup['condition'][0]).$this->_spaceR.preg_quote($this->markup['condition'][3]).$this->_space.'='.$this->_space.'"(.+)"'.$this->_space.'>`sU',
					'`</'.$this->_name.preg_quote($this->markup['condition'][0]).$this->_space.'>`sU',
					'`<'.$this->_name.preg_quote($this->markup['condition'][1]).$this->_spaceR.preg_quote($this->markup['condition'][3]).'='.$this->_space.'"(.+)"'.$this->_space.'/>`sU',
					'`<'.$this->_name.preg_quote($this->markup['condition'][2]).$this->_space.'/>`sU',
				),array(
					'<?php if(\1) { ?>',
					'<?php } ?>',
					'<?php }elseif(\1){ ?>',
					'<?php }else{ ?>'
				),
				$this->_content);
			}

			/**
			 * parse function :
			 * 		<gc:function call=""/>
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseFunction(){
				$this->_content = preg_replace('`<'.$this->_name.preg_quote($this->markup['function'][0]).$this->_spaceR.preg_quote($this->markup['function'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.$this->_space.'/>`isU', '$1', $this->_content);

			}

			/**
			 * parse block : <gc:block name="">
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseBlock(){
				$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['block'][0]).$this->_spaceR.preg_quote($this->markup['block'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)'.$this->_space.'"'.$this->_space.'>(.*)</'.$this->_name.$this->markup['block'][0].$this->_space.'>`isU', array('system\templateParser', '_parseBlockCallback'), $this->_content);
			}

			/**
			 * parse block callback
			 * @access protected
			 * @param $m array
			 * @return string
			 * @since 3.0
			 */

			protected function _parseBlockCallback($m){
				if(!function_exists($m[1])){
					$blockFunction  = '<?php class template'.$m[1].' extends \system\template { public static function '.$m[1].'(){ ?> ';
					$blockFunction .= $m[2];
					$blockFunction .= ' <?php } } ?>';
					return $blockFunction;
				}
				else{
					$this->addError('function '.$m[1].' already exists', __FILE__, __LINE__, ERROR_FATAL);
					return '';
				}
			}

			/**
			 * parse template : <gc:template name="name(*)">
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseTemplate(){
				$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['template'][0]).$this->_spaceR.preg_quote($this->markup['template'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)'.$this->_space.'"'.$this->_space.'>(.*)</'.$this->_name.$this->markup['template'][0].$this->_space.'>`isU', array('system\templateParser', '_parseTemplateCallback'), $this->_content);
			}

			/**
			 * parse template callback
			 * @access protected
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _parseTemplateCallback($m){

			}

			/**
			 * parse calling template or block : <gc:call template="name()">
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseCall(){

			}

			/**
			 * parse asset manager :
			 * 		<gc:asset type="css" files="
						.app/css/other.css
			 * 		</gc:asset>
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseAssetManager(){
				$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['assetManager'][0]).
					$this->_spaceR.preg_quote($this->markup['assetManager'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.
					$this->_spaceR.preg_quote($this->markup['assetManager'][2]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.
					$this->_spaceR.preg_quote($this->markup['assetManager'][3]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.
					$this->_space.'/>`isU', array('system\templateParser', '_parseAssetManagerCallback'), $this->_content);
			}

			/**
			 * parse assetManager callback
			 * @access protected
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _parseAssetManagerCallback($m){
				if(ASSET_MANAGER == true){
					$data = array(
						'type' => $m[1],
						'cache' => $m[3],
						'files' => explode(',', $m[2]));

					$asset = $this->assetManager($data);

					if($m[1] == 'css'){
						return '<link href="{{url:.gcs.gcs.assetManager:\''.$asset->getId().'\',\''.$asset->getType().'\'}}" rel="stylesheet" media="screen" type="text/css" />';
					}
					else if($m[1] == 'js'){
						return '<script type="text/javascript" defer src="{{url:.gcs.gcs.assetManager:\''.$asset->getId().'\',\''.$asset->getType().'\'}}" ></script>';
					}
				}
				else{
					$content = '';
					$files = explode(',', $m[2]);

					foreach ($files as $value) {
						$value = preg_replace('#\\n#isU', '', $value);
						$value = preg_replace('#\\r#isU', '', $value);
						$value = preg_replace('#\\t#isU', '', $value);

						if($m[1] == 'css'){
							$content .= '<link href="/'.$this->resolve(trim($value), RESOLVE_CSS).'" rel="stylesheet" media="screen" type="text/css" />'."\n";
						}
						else{
							$content .= '<script type="text/javascript" defer src="/'.$this->resolve(trim($value), RESOLVE_JS).'"></script>'."\n";
						}
					}

					return $content;
				}
			}

			/**
			 * parse minify : <gc:minify>
			 * @access protected
			 * @return void
			 * @since 3.0
			*/

			protected function _parseMinify(){
				$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['minify'][0]).$this->_space.'>(.*)</'.$this->_name.preg_quote($this->markup['minify'][0]).$this->_space.'>`isU', array('system\templateParser', '_parseMinifyCallback'), $this->_content);
			}

			/**
			 * parse minify callback
			 * @access protected
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _parseMinifyCallback($m){
				if(MINIFY_OUTPUT_HTML == true){
					$m[1] = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $m[1]);
					$m[1] = str_replace(array("\t", '  ', '    ', '    '), '', $m[1]);
				}

				return $m[1];
			}

			/**
			 * "::" isn't well managed by the parser, we temporally disable it
			*/

			protected function _parseDebugStart(){
				$this->_content = preg_replace('`::`isU', '[debug||]', $this->_content);
			}

			/**
			 * "::" isn't well managed by the parser, we put it back
			*/

			protected function _parseDebugEnd(){
				$this->_content = preg_replace('`\[debug\|\|\]`isU', '::', $this->_content);
			}

			/**
			 * parse exception
			 * @access protected
			 * @return void
			*/

			protected function _parseException(){
				$this->_content = preg_replace('#'.preg_quote('; ?>; ?>').'#isU', '; ?>', $this->_content);
				$this->_content = preg_replace('#'.preg_quote('<?php echo <?php').'#isU', '<?php echo', $this->_content);
			}

			/**
			 * destructor
			 * @access public
			 * @return string
			 * @since 3.0
			*/

			public function __destruct(){
			}
		}
	}