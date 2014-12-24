<?php
	/*\
	 | ------------------------------------------------------
	 | @file : TemplateParser.class.php
	 | @author : fab@c++
	 | @description : Template engine parser
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
	use system\Exception\Exception;
	
	class templateParser{
		use error, langs, url, resolve, facades;

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
			'vars'         => array('{', '}', '}}', '}_}', '{{gravatar:', '{{php:', '{{url', '{{lang', '{_{url', '{_{lang', '{{path:'),  // vars
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
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $tpl \system\Template\template
		 * @since 3.0
		 * @package system\Template
		 */

		public function __construct(&$profiler, &$config, &$request, &$response, $lang, template $tpl){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_createlang();
			$this->_template = $tpl;
		}

		/**
		 * classic parsing
		 * @access public
		 * @param $content string
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		public function parse($content){
			$this->_content = $content;
			$this->_parseDebugStart();
			$this->_parseInclude();
			$this->_parsePath();
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
		 * @package system\Template
		*/

		public function parseNoTemplate($content){
			$this->_content = $content;
			$this->_parseDebugStart();
			$this->_parseInclude();
			$this->_parsePath();
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
		 * parsing for langs
		 * @access public
		 * @param $content string
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		public function parseLang($content){
			$this->_content = $content;
			$this->_parseDebugStart();
			$this->_parsePath();
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
		 * @package system\Template
		*/

		protected function _parseInclude(){
			$this->_content = preg_replace_callback(
				'`<'.$this->_name.preg_quote($this->markup['include'][0]).$this->_spaceR.preg_quote($this->markup['include'][1]).$this->_space.'='.$this->_space.'"([A-Za-z0-9_\-\$/]+)"'.$this->_space.'(('.preg_quote($this->markup['include'][2]).$this->_space.'='.$this->_space.'"([0-9]*)"'.$this->_space.')*)'.$this->_space.'/>`isU',
				array('system\Template\templateParser','_parseIncludeCallback'), $this->_content);
			$this->_content = preg_replace_callback(
				'`<'.$this->_name.preg_quote($this->markup['include'][0]).$this->_spaceR.preg_quote($this->markup['include'][1]).$this->_space.'='.$this->_space.'"([A-Za-z0-9_\-\$/]+)"'.$this->_space.preg_quote($this->markup['include'][3]).$this->_space.'='.$this->_space.'"'.preg_quote($this->markup['include'][4]).'"'.$this->_space.'(('.preg_quote($this->markup['include'][2]).$this->_space.'='.$this->_space.'"([0-9]*)"'.$this->_space.')*)'.$this->_space.'/>`isU',
				array('system\Template\templateParser','_parseIncludeCompileCallback'), $this->_content);
		}

		/**
		 * parse include callback
		 * @access protected
		 * @param $m
		 * @return string
		 * @since 3.0
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
		*/

		protected function _parsePath(){
			$this->_content = preg_replace('`'.preg_quote($this->markup['vars'][10]).'(.+)'.preg_quote($this->markup['vars'][2]).'`sU', '<?php echo $this->path(RESOLVE_$1) ?>', $this->_content);
			$this->_content = preg_replace('`'.preg_quote($this->markup['vars'][10]).'(.+):(.+)'.preg_quote($this->markup['vars'][2]).'`sU', '<?php echo $this->path(RESOLVE_$1, ".$2") ?>', $this->_content);
		}

		/**
		 * parse gravatar {{gravatar:email:size}}
		 * @access protected
		 * @return void
		 * @since 3.0
	 * @package system\Template
		*/

		protected function _parseGravatar(){
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][4]).'(.+):(.+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\Template\templateParser', '_parseGravatarCallback'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][4]).'(.+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\Template\templateParser', '_parseGravatarCallback'), $this->_content);
		}

		/**
		 * parse gravatar callback
		 * @access protected
		 * @param $m
		 * @return string
		 * @since 3.0
		 * @package system\Template
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
		 * @package system\Template
		*/

		protected function _parseUrl(){
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\Template\templateParser', '_parseUrlCallback'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\:)([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU',array('system\Template\templateParser', '_parseUrlCallback'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\Template\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\:)([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\Template\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);

			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\[absolute\]\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU', array('system\Template\templateParser', '_parseUrlCallback'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][6]).'(\[absolute\]\:)([^\{\}]+)'.preg_quote($this->markup['vars'][2]).'`sU',array('system\Template\templateParser', '_parseUrlCallback'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\[absolute\]\:)([^\{\}]+):([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\Template\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][8]).'(\[absolute\]\:)([^\{\}]+)'.preg_quote($this->markup['vars'][3]).'`sU',array('system\Template\templateParser', '_parseUrlCallbackNoEcho'), $this->_content);
		}

		/**
		 * parse url for both url functions
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
		*/

		protected function _parseLang(){
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][7]).'(\:)(.*)'.preg_quote($this->markup['vars'][2]).'`isU', array('system\Template\templateParser', '_parseLangCallBack'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][7]).'(\[template\]\:)(.+)'.preg_quote($this->markup['vars'][2]).'`isU', array('system\Template\templateParser', '_parseLangCallBack'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][9]).'(\:)(.*)'.preg_quote($this->markup['vars'][3]).'`isU', array('system\Template\templateParser', '_parseLangCallBackNoEcho'), $this->_content);
			$this->_content = preg_replace_callback('`'.preg_quote($this->markup['vars'][9]).'(\[template\]\:)(.+)'.preg_quote($this->markup['vars'][3]).'`isU', array('system\Template\templateParser', '_parseLangCallBackNoEcho'), $this->_content);
		}

		/**
		 * parse lang classic
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseLangCallBack($m){
			$a = explode(':', $m[2]); //we separate the two sections

			if($m[1] == ':')
				$type = '';
			else
				$type= ', \system\Lang\Lang::USE_TPL';

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
		 * @package system\Template
		*/

		protected function _parseLangCallBackNoEcho($m){
			$a = explode(':', $m[2]); //we separate the two sections

			if($m[1] == ':')
				$type = '';
			else
				$type= ', \system\Lang\Lang::USE_TPL';

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
		 * @package system\Template
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
		 * parse for <gc:for condition=""></gc:for>
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
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
		 * @package system\Template
		*/

		protected function _parseFunction(){
			$this->_content = preg_replace('`<'.$this->_name.preg_quote($this->markup['function'][0]).$this->_spaceR.preg_quote($this->markup['function'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.$this->_space.'/>`isU', '$1', $this->_content);

		}

		/**
		 * parse block : <gc:block name="">
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseBlock(){
			$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['block'][0]).$this->_spaceR.preg_quote($this->markup['block'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)\(\)'.$this->_space.'"'.$this->_space.'>(.*)</'.$this->_name.$this->markup['block'][0].$this->_space.'>`isU', array('system\Template\templateParser', '_parseBlockCallback'), $this->_content);
		}

		/**
		 * parse block callback
		 * @access protected
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
		 */

		protected function _parseBlockCallback($m){
			if(!class_exists('block'.$m[1])){
				$blockFunction  = '<?php class block'.$m[1].' extends \system\Template\Template { public static function '.$m[1].'(){ ?> ';
				$blockFunction .= $m[2];
				$blockFunction .= ' <?php } } ?>';
				return $blockFunction;
			}
			else{
				$this->addError('the class "block'.$m[1].'" already exists', __FILE__, __LINE__, ERROR_FATAL);
				return '';
			}
		}

		/**
		 * parse template : <gc:template name="name(*)">
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseTemplate(){
			$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['template'][0]).$this->_spaceR.preg_quote($this->markup['template'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)\((.*)\)'.$this->_space.'"'.$this->_space.'>(.*)</'.$this->_name.$this->markup['template'][0].$this->_space.'>`isU', array('system\Template\templateParser', '_parseTemplateCallback'), $this->_content);
		}

		/**
		 * parse template callback
		 * @access protected
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseTemplateCallback($m){
			if(!class_exists('block'.$m[1])){
				$vars = explode(',', $m[2]);
				$varList = '';

				foreach($vars as $value){
					if($value == '*'){
						foreach($this->_template->vars as $key => $value2){
							if(!in_array('$'.$key,$vars )){
								$varList .= '$'.$key.',';
							}
							else{
								$this->addError('the template function "template'.$m[1].'" has already this parameter ('.$value.')', __FILE__, __LINE__, ERROR_FATAL);
								$varList .= '$'.$key.',';
							}
						}
					}
					else{
						$varList .= $value.',';
					}
				}

				$varList = preg_replace('#,$#isU', '', $varList);

				$blockFunction  = '<?php class template'.$m[1].' extends system\Template\Template{  '."\n";
				$blockFunction .= '		public $profiler; public $config; public $request; public $response; public $lang;'."\n";
				$blockFunction .= '		public function __construct(&$profiler, &$config, &$request, &$response, $lang){'."\n";
				$blockFunction .= '			$this->profiler = $profiler; $this->config = $config; $this->request = $request; $this->response = $response; $this->lang = $lang;'."\n";
				$blockFunction .= '		}'."\n";
				$blockFunction .= '		public function '.$m[1].'('.$varList.'){ ?> '."\n";
				$blockFunction .= '			'.$m[3]."\n";
				$blockFunction .= ' <?php } } ?>';

				return $blockFunction;
			}
			else{
				$this->addError('the class "template'.$m[1].'" already exists', __FILE__, __LINE__, ERROR_FATAL);
				return '';
			}
		}

		/**
		 * parse calling template or block :
		 * 		<gc:call template="name()">
		 * 		<gc:call block="name()">
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseCall(){
			$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['call'][0]).$this->_spaceR.preg_quote($this->markup['call'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)\(\)'.$this->_space.'"'.$this->_space.'/>`isU', array('system\Template\templateParser', '_parseCallBlockCallback'), $this->_content);
			$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['call'][0]).$this->_spaceR.preg_quote($this->markup['call'][2]).$this->_space.'='.$this->_space.'"'.$this->_space.'(\w+)\((.*)\)'.$this->_space.'"'.$this->_space.'/>`isU', array('system\Template\templateParser', '_parseCallTemplateCallback'), $this->_content);
		}

		/**
		 * parse call block callback
		 * @access protected
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseCallBlockCallback($m){
			return '<?php block'.$m[1].'::'.$m[1].'(); ?>';
		}

		/**
		 * parse call block callback
		 * @access protected
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseCallTemplateCallback($m){
			$vars = explode(',', $m[2]);
			$varList = '';
			foreach($vars as $value){
				if($value == '*'){
					foreach($this->_template->vars as $key => $value2){
						if(!in_array('$'.$key,$vars )){
							$varList .= '$'.$key.',';
						}
						else{
							$this->addError('the template function "template'.$m[1].'" has already this parameter ('.$value.')', __FILE__, __LINE__, ERROR_FATAL);
							$varList .= '$'.$key.',';
						}
					}
				}
				elseif($value != ''){
					$varList .= $value.',';
				}
			}

			$varList = preg_replace('#,$#isU', '', $varList);

			return '<?php $template'.$m[1].' = new template'.$m[1].'($this->profiler, $this->config, $this->request, $this->response, $this->lang); $template'.$m[1].'->'.$m[1].'('.$varList.'); ?>';
		}

		/**
		 * parse asset manager :
		 * 		<gc:asset type="css" files="
					.app/css/other.css
		 * 		</gc:asset>
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseAssetManager(){
			$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['assetManager'][0]).
				$this->_spaceR.preg_quote($this->markup['assetManager'][1]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.
				$this->_spaceR.preg_quote($this->markup['assetManager'][2]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.
				$this->_spaceR.preg_quote($this->markup['assetManager'][3]).$this->_space.'='.$this->_space.'"'.$this->_space.'(.+)'.$this->_space.'"'.
				$this->_space.'/>`isU', array('system\Template\templateParser', '_parseAssetManagerCallback'), $this->_content);
		}

		/**
		 * parse assetManager callback
		 * @access protected
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
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
		 * @package system\Template
		*/

		protected function _parseMinify(){
			$this->_content = preg_replace_callback('`<'.$this->_name.preg_quote($this->markup['minify'][0]).$this->_space.'>(.*)</'.$this->_name.preg_quote($this->markup['minify'][0]).$this->_space.'>`isU', array('system\Template\templateParser', '_parseMinifyCallback'), $this->_content);
		}

		/**
		 * parse minify callback
		 * @access protected
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\Template
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
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseDebugStart(){
			$this->_content = preg_replace('`::`isU', '[debug||]', $this->_content);
		}

		/**
		 * "::" isn't well managed by the parser, we put it back
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
		*/

		protected function _parseDebugEnd(){
			$this->_content = preg_replace('`\[debug\|\|\]`isU', '::', $this->_content);
		}

		/**
		 * parse exception
		 * @access protected
		 * @return void
		 * @since 3.0
		 * @package system\Template
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
		 * @package system\Template
		*/

		public function __destruct(){
		}
	}