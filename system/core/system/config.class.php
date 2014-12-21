<?php
	/*\
	 | ------------------------------------------------------
	 | @file : config.class.php
	 | @author : fab@c++
	 | @description : contain data and path used by the application. If the CONFIG_CACHE is, the config is put in cache.
	 | 				It contains data for lang and route files, and paths for css/image/file/js/template
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class config{
			use error;

			/** 
			 * contain all the config, lang and path data (path, or content file)
			 * @var array
			*/

			public $config = array();

			/** 
			 * cache instance
			 * @var \system\cache
			*/

			protected $_cache;

			/** 
			 * permit to parse easily parents node for route file.
			 * - the name of the attribute
			 * - the separator used if it must concatenate values
			 * - concatenate or no
			 * @var array
			*/

			protected $_routeAttribute = array(
				array('name' => 'name', 'separator' => '.', 'concatenate' => true),
				array('name' => 'url', 'separator' => '', 'concatenate' => true),
				array('name' => 'action', 'separator' => '.', 'concatenate' => true),
				array('name' => 'vars', 'separator' => ',', 'concatenate' => true),
				array('name' => 'cache', 'separator' => '', 'concatenate' => false),
				array('name' => 'logged', 'separator' => '', 'concatenate' => false),
				array('name' => 'access', 'separator' => '', 'concatenate' => false)
			);

			/** 
			 * permit to parse easily parents node for lang file.
			 * - the name of the attribute
			 * - the separator used if it must concatenate values
			 * - concatenate or no
			 * @var array
			*/

			protected $_langAttribute = array(
				array('name' => 'name', 'separator' => '.', 'concatenate' => true)
			);

			/**
			 * constructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/

			public function __construct (){
				if(CACHE_CONFIG == false){
					$this->_init();
				}
				else{

					$this->_cache = new \system\cache($this, $this, $this, $this, LANG, 'config');

					if($this->_cache->isExist()){
						$this->config = $this->_cache->getCache();
					}
					else{
						$this->_init();
					}
				}
			}

			/**
			 * put config in array
			 * @access protected
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			protected function _init(){
				/* ############## APP ############## */

				/* ## LANG ## */
				if ($handle = opendir(APP_RESOURCE_LANG_PATH)) {
					while (false !== ($entry = readdir($handle))) {
						if(preg_match('#('.preg_quote(EXT_LANG).')$#isU', $entry))
							$this->_parseLang(null, str_replace(EXT_LANG, '', $entry));
					}

					closedir($handle);
				}

				/* ## TEMPLATE ## */
				$this->config['template']['app'] = APP_RESOURCE_TEMPLATE_PATH;
				/* ## CSS ## */
				$this->config['css']['app'] = WEB_PATH.'app/'.WEB_CSS_PATH;
				/* ## IMAGE ## */
				$this->config['image']['app'] = WEB_PATH.'app/'.WEB_IMAGE_PATH;
				/* ## FILE ## */
				$this->config['file']['app'] = WEB_PATH.'app/'.WEB_FILE_PATH;
				/* ## JS ## */
				$this->config['js']['app'] = WEB_PATH.'app/'.WEB_JS_PATH;
				/* ## SPAM ## */
				$this->_parseSpam();
				/* ## DEFINE ## */
				$this->_parseDefine();
				/* ## LIBRARY ## */
				$this->_parseLibrary();

				/* ############## SRC ############## */
				$xml = simplexml_load_file(APP_CONFIG_SRC);
				$datas =  $xml->xpath('//src');

				foreach ($datas as $data) {
					/* ## ROUTE ## */
					$this->_parseRoute($data['name']);

					/* ## LANG ## */
					if ($handle = opendir(SRC_PATH.$data['name'].'/'.SRC_RESOURCE_LANG_PATH)) {
						while (false !== ($entry = readdir($handle))) {
							if(preg_match('#('.preg_quote(EXT_LANG).')$#isU', $entry)){
								$lang = str_replace(EXT_LANG, '', $entry);
								$this->_parseLang($data['name'], $lang);
							}
						}

						closedir($handle);
					}

					/* ## TEMPLATE ## */
					$this->config['template'][''.$data['name'].''] = SRC_PATH.$data['name'].'/'.SRC_RESOURCE_TEMPLATE_PATH;
					/* ## CSS ## */
					$this->config['css'][''.$data['name'].''] = WEB_PATH.$data['name'].'/'.WEB_CSS_PATH;
					/* ## IMAGE ## */
					$this->config['image'][''.$data['name'].''] = WEB_PATH.$data['name'].'/'.WEB_IMAGE_PATH;
					/* ## FILE ## */
					$this->config['file'][''.$data['name'].''] = WEB_PATH.$data['name'].'/'.WEB_FILE_PATH;
					/* ## JS ## */
					$this->config['js'][''.$data['name'].''] = WEB_PATH.$data['name'].'/'.WEB_JS_PATH;
					/* ## FIREWALL ## */
					$this->_parseFirewall($data['name']);
					/* ## DEFINE ## */
					$this->_parseDefine($data['name']);
					/* ## LIBRARY ## */
					$this->_parseLibrary($data['name']);
					
					//copy app lang in each other module lang
					foreach ($this->config['lang'] as $key => $value) {
						if($key != 'app'){
							foreach($value as $key2 => $value2){
								$this->config['lang'][''.$key.''][''.$key2.''] =
									array_merge(
										$this->config['lang']['app'][''.$key2.''],
										$value2
									);
							}
						}
					}
				}

				if(CACHE_CONFIG == true){
					$this->_cache->setContent($this->config);
					$this->_cache->setCache();
				}
			}

			/**
			 * parse route file and put data in an array
			 * @access protected
			 * @param $src string
			 * @return array
			 * @since 3.0
 			 * @package system
			 * @throws exception if src config file doesn't exist
			*/

			protected function _parseRoute($src){
				$file = SRC_PATH.$src.'/'.SRC_CONFIG_ROUTE;

				if($xml = simplexml_load_file($file)){
					$values =  $xml->xpath('//route');

					foreach ($values as $value) {
						foreach ($this->_routeAttribute as $attribute) {
							$attributeType = $attribute['name'];

							if(is_object($value[$attributeType]))
								$data[$attributeType] = $value[$attributeType]->__toString();
							else
								$data[$attributeType] = '';
						}

						$data = $this->_parseParent($value, $data, $this->_routeAttribute);

						if( $data['logged'] == '')
							 $data['logged'] = '*';

						if( $data['access'] == '')
							 $data['access'] = '*';

						$this->config['route'][''.$src.''][''.$data['name'].''] = $data;
					}
				}
				else{
					throw new exception('can\'t open file "'.$file.'"', 1);
				}
			}

			/**
			 * parse lang file and put data in an array
			 * @access protected
			 * @param $src string
			 * @param $lang lang
			 * @return array
			 * @since 3.0
 			 * @package system
			 * @throws exception if src config file doesn't exist
			*/

			protected function _parseLang($src = null, $lang){
				if($src == null){
					$file = APP_RESOURCE_LANG_PATH.$lang.EXT_LANG;
					$src = 'app';
				}
				else{
					$file = SRC_PATH.$src.'/'.SRC_RESOURCE_LANG_PATH.$lang.EXT_LANG;
				}

				$this->config['lang'][''.$src.''][''.$lang.''] = array();

				if(file_exists($file)){
					if($xml = simplexml_load_file($file)){
						$values =  $xml->xpath('//lang');

						foreach ($values as $value) {
							foreach ($this->_langAttribute as $attribute) {
								$attributeType = $attribute['name'];

								if(is_object($value[$attributeType]))
									$data[$attributeType] = $value[$attributeType]->__toString();
								else
									$data[$attributeType] = '';

								$data['content'] = $value->__toString();
							}

							$data = $this->_parseParent($value, $data, $this->_langAttribute);

							$this->config['lang'][''.$src.''][''.$lang.''][''.$data['name'].''] = $data;
							$this->config['lang'][''.$src.''][''.$lang.''][''.$data['name'].''] = $this->config['lang'][''.$src.''][''.$lang.''][''.$data['name'].'']['content'];
						}
					}
					else{
						throw new exception('can\'t open file "'.$file.'"', 1);
					}
				}
				else{
					throw new exception('can\'t open file "'.$file.'"', 1);
				}
			}

			/**
			 * parse spam file
			 * @access protected
			 * @return array
			 * @throws exception if spam file can't be opened
			 * @since 3.0
 			 * @package system
			*/

			protected function _parseSpam(){
				if($xml = simplexml_load_file(APP_CONFIG_SPAM)){
					$query =  $xml->xpath('//query');
					$error =  $xml->xpath('//error');
					$exception =  $xml->xpath('//exception');
					$errorVariable =  $xml->xpath('//variable');

					foreach ($query as $value) {
						$this->config['spam']['app']['query']['number'] = $value['number']->__toString();
						$this->config['spam']['app']['query']['duration'] = $value['duration']->__toString();
					}

					foreach ($error as $value) {
						$this->config['spam']['app']['error']['template'] = $value['template']->__toString();
					}

					$this->config['spam']['app']['exception'] = array();

					foreach ($exception as $value) {
						array_push($this->config['spam']['app']['exception'], $value['name']->__toString());
					}

					foreach ($errorVariable as $value) {
						$data = array();
						$this->config['spam']['app']['error']['variable'] = array();

						$data['type'] = $value['type']->__toString();
						$data['name'] = $value['name']->__toString();
						$data['value'] = $value['value']->__toString();

						array_push($this->config['spam']['app']['error']['variable'], $data);
					}
				}
				else{
					throw new exception('can\'t open file "'.APP_CONFIG_SPAM.'"', 1);
				}
			}

			/**
			 * parse firewall file
			 * @access protected
			 * @param $src string
			 * @throws exception if spam file can't be opened
			 * @return array
			 * @since 3.0
 			 * @package system
			*/

			protected function _parseFirewall($src = null){
				$file = SRC_PATH.$src.'/'.SRC_CONFIG_FIREWALL;

				if(file_exists($file)){
					if($xml = simplexml_load_file($file)){
						$values =  $xml->xpath('//role');

						$roles =  $xml->xpath('//roles');
						$role =  $xml->xpath('//role');
						$login =  $xml->xpath('//login/source');
						$default =  $xml->xpath('//default/source');
						$forbidden =  $xml->xpath('//forbidden');
						$csrf =  $xml->xpath('//csrf');
						$forbiddenVariable =  $xml->xpath('//forbidden/variable');
						$csrfVariable =  $xml->xpath('//csrf/variable');
						$logged =  $xml->xpath('//logged');

						$this->config['firewall'][''.$src.'']['roles'] = array();
						$this->config['firewall'][''.$src.'']['forbidden']['variable'] = array();
						$this->config['firewall'][''.$src.'']['csrf']['variable'] = array();

						foreach ($roles as $value) {
							$this->config['firewall'][''.$src.'']['roles']['name'] = $value['name']->__toString();
						}

						foreach ($role as $value) {
							$this->config['firewall'][''.$src.'']['roles']['role'][''.$value['name']->__toString().''] = $value['name']->__toString();
						}

						foreach ($login as $value) {
							$this->config['firewall'][''.$src.'']['login']['name'] = $value['name']->__toString();
							$this->config['firewall'][''.$src.'']['login']['vars'] = explode(',', $value['vars']->__toString());
						}

						foreach ($default as $value) {
							$this->config['firewall'][''.$src.'']['default']['name'] = $value['name']->__toString();
							$this->config['firewall'][''.$src.'']['default']['vars'] = explode(',', $value['vars']->__toString());
						}

						foreach ($forbidden as $value) {
							$this->config['firewall'][''.$src.'']['forbidden']['template'] = $value['template']->__toString();
						}

						foreach ($csrf as $value) {
							$this->config['firewall'][''.$src.'']['csrf']['name'] = $value['name']->__toString();
							$this->config['firewall'][''.$src.'']['csrf']['template'] = $value['template']->__toString();
							$this->config['firewall'][''.$src.'']['csrf']['enabled'] = $value['enabled']->__toString();
						}

						foreach ($forbiddenVariable as $value) {
							$data = array();

							$data['type'] = $value['type']->__toString();
							$data['name'] = $value['name']->__toString();
							$data['value'] = $value['value']->__toString();

							array_push($this->config['firewall'][''.$src.'']['forbidden']['variable'], $data);
						}

						foreach ($csrfVariable as $value) {
							$data = array();

							$data['type'] = $value['type']->__toString();
							$data['name'] = $value['name']->__toString();
							$data['value'] = $value['value']->__toString();

							array_push($this->config['firewall'][''.$src.'']['csrf']['variable'], $data);
						}

						foreach ($logged as $value) {
							$this->config['firewall'][''.$src.'']['logged']['name'] = $value['name']->__toString();
						}
					}
					else{
						throw new exception('can\'t open file "'.$file.'"', 1);
					}
				}
				else{
					throw new exception('can\'t open file "'.$file.'"', 1);
				}
			}

			/**
			 * parse parent node
			 * @access protected
			 * @param $child \SimpleXMLElement
			 * @param $data string
			 * @param $attributes array
			 * @return array
			 * @since 3.0
 			 * @package system
			*/

			protected function _parseParent($child, $data, $attributes){
				$parent = $child->xpath("parent::*");

				if(is_object($parent[0]['name'])){
					foreach ($attributes as $attribute) {
						$name = $attribute['name'];

						if(is_object($parent[0][$name])){
							if($attribute['concatenate'] == true){
								if($data[$name] != ''){
									$data[$name] = $parent[0][$name]->__toString().$attribute['separator'].$data[$name];
								}
								else{
									$data[$name] = $parent[0][$name]->__toString();
								}
							}
							else{
								if($data[$name] == '')
									$data[$name] = $parent[0][$name]->__toString();
							}
						}
					}

					$data = $this->_parseParent($parent[0], $data, $attributes);
				}
				
				return $data;
			}

			/**
			 * parse define file and put data in an array
			 * @access protected
			 * @param $src string
			 * @throws exception if spam file can't be opened
			 * @return array
			 * @since 3.0
 			 * @package system
			*/

			protected function _parseDefine($src = null){
				if($src == null){
					$file = APP_CONFIG_DEFINE;
					$src = 'app';
				}
				else{
					$file = SRC_PATH.$src.'/'.SRC_CONFIG_DEFINE;
				}

				$this->config['define'][''.$src.''] = array();

				if(file_exists($file)){
					if($xml = simplexml_load_file($file)){
						$values =  $xml->xpath('//define');

						foreach ($values as $value) {
							$this->config['define'][''.$src.''][''.$value['name'].''] = dom_import_simplexml($value)->textContent;
						}
					}
					else{
						throw new exception('can\'t open file "'.$file.'"', 1);
					}
				}
				else{
					throw new exception('can\'t open file "'.$file.'"', 1);
				}
			}

			/**
			 * parse library file and put data in an array
			 * @access protected
			 * @param $src string
			 * * @throws exception if spam file can't be opened
			 * @return array
			 * @since 3.0
 			 * @package system
			*/

			protected function _parseLibrary($src = null){
				if($src == null){
					$file = APP_CONFIG_LIBRARY;
					$src = 'app';
				}
				else{
					$file = SRC_PATH.$src.'/'.SRC_CONFIG_LIBRARY;
				}

				$this->config['library'][''.$src.''] = array();

				if(file_exists($file)){
					if($xml = simplexml_load_file($file)){
						$values =  $xml->xpath('//library');

						foreach ($values as $value) {
							$library = array();
							$library['name'] = $value['name']->__toString();
							$library['access'] = $value['access']->__toString();
							$library['enabled'] = $value['enabled']->__toString();
							$library['include'] = $value['include']->__toString();

							array_push($this->config['library'][''.$src.''], $library);
						}
					}
					else{
						throw new exception('can\'t open file "'.$file.'"', 1);
					}
				}
				else{
					throw new exception('can\'t open file "'.$file.'"', 1);
				}
			}


			/**
			 * destructor
			 * @access protected
			 * @return string
			 * @since 3.0
 			 * @package system
			*/

			public function __destruct(){

			}
		}
	}