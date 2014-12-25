<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Template.class.php
	 | @author : fab@c++
	 | @description : template engine
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Template;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\General\url;
	use system\General\resolve;
	use system\Profiler\Profiler;
	use system\Exception\MissingTemplateException;

	class Template{
		use error, langs, facades, url, resolve;

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
		const TPL_COMPILE_TO_INCLUDE    =       0;
		const TPL_COMPILE_TO_STRING     =       1;

		/**
		 * constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $file string : file path or content
		 * @param $name string : template name
		 * @param $cache int : cache time
		 * @param $stream int : use a file or a string
		 * @throws \system\Exception\MissingTemplateException if the tpl file can't be read
		 * @since 3.0
		 * @package system\Template
		*/

		public function __construct (&$profiler, &$config, &$request, &$response, $lang, $file, $name = 'template', $cache = 0, $stream = self::TPL_FILE){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_createlang();

			$this->_file = $this->resolve(RESOLVE_TEMPLATE, $file).EXT_TEMPLATE;
			$this->_name      = $name;
			$this->_timeCache = $cache;
			$this->_stream    = $stream;

			if(CACHE_ENABLED == false)
				$this->_timeCache = 0;

			if(!preg_match('#(tplInclude)#isU', $name)){
				$stack = debug_backtrace(0);
				$trace = $this->getStackTraceToString($stack);
				$this->_name .= $trace;
			}
			else{
				$trace = '';
				$this->_name .= $trace;
			}

			if($this->_stream == self::TPL_FILE){
				if(file_exists($this->_file)){
					if (!file_exists(APP_CACHE_PATH_TEMPLATE))
						mkdir(APP_CACHE_PATH_TEMPLATE, 0755, true);

					$hash = sha1(preg_replace('#/#isU', '', $file));
				}
				else{
					throw new MissingTemplateException('can\'t open template file "'.$this->_file.'"');
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
		 * @package system\Template
		*/

		private function getStackTraceToString($stack){
			$max = 0;
			$trace = '';

			for($i = 3; $i < count($stack) && $max < 4; $i++){
				if(isset($stack[$i]['file']) && preg_match('#('.preg_quote('system\orm').')#isU', $stack[$i]['file'])){ //ORM
					$trace .= str_replace('\\', '-', $stack[$i]['class']).'_'.$stack[$i]['function'].'_'.$stack[$i-1]['line'].'__';
				}
			}

			return $trace;
		}

		/**
		 * initialize the parser instance reference
		 * @return void
		 * @since 3.0
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
		*/

		protected function _save($content){
			file_put_contents($this->_fileCache, $content);
		}

		/**
		 * @param int $type
		 * @param $returnType : make a include or eval the template
		 * @return mixed
		 * @since 3.0
		 * @package system\Template
		*/

		public function show($type = self::TPL_COMPILE_ALL, $returnType = self::TPL_COMPILE_TO_INCLUDE){
			$this->profiler->addTime('template '.$this->_name);
			$this->profiler->addTemplate($this->_name, Profiler::TEMPLATE_START, $this->_file);

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

			if($returnType == self::TPL_COMPILE_TO_INCLUDE){
				if($type != self::TPL_COMPILE_INCLUDE)
					require_once($this->_fileCache);
			}

			$this->profiler->addTemplate($this->_name, Profiler::TEMPLATE_END, $this->_file);
			$this->profiler->addTime('template '.$this->_name, Profiler::USER_END);

			if($returnType == self::TPL_COMPILE_TO_STRING){

				ob_start();
					require_once($this->_fileCache);
				$output = ob_get_contents();
				ob_get_clean();

				return $output;
			}
		}

		/**
		 * get file path
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		public function getFile(){
			return $this->_file;
		}

		/**
		 * get file path cache
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		public function getFileCache(){
			return $this->_fileCache;
		}

		/**
		 * get tpl name
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		public function getName(){
			return $this->_name;
		}

		/**
		 * destructor
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		public function __destruct(){
		}
	}