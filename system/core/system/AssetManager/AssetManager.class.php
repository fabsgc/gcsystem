<?php
	/*\
	 | ------------------------------------------------------
	 | @file : assetManager.class.php
	 | @author : fab@c++
	 | @description : css and js manager system (minify, compress, put in cache file)
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system\AssetManager;

	use \system\General;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;

	class AssetManager{
		use error, langs, facades;

		protected $_name                    ;  //concatenated name files
		protected $_files          = array();  //files list
		protected $_data           = array();  //content files
		protected $_cache                   ;  //cache file
		protected $_time                    ;  //time cache
		protected $_type                    ;  //js or css
		protected $_currentPath             ;  //path to the current file
		protected $_concatenedContent       ;  //concatened content, corrected and compressed

		/**
		 * Constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $data array :
		 * 		files array
		 * 		cache int
		 * 		type string
		 * @since 3.0
		 * @package system\AssetManager
		*/

		public function __construct(&$profiler, &$config, &$request, &$response, $lang, $data = array()){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;

			foreach ($data as $key => $value) {
				switch ($key) {
					case 'files':
						$this->_setFiles($value);
					break;

					case 'cache':
						$this->_time = abs(intval($value));
					break;

					case 'type':
						$this->_type = $value;
					break;
				}
			}
		}

		/**
		 * get the ID of the generated file
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\AssetManager
		*/

		public function getId(){
			return sha1($this->_name);
		}

		/**
		 * get the type
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\AssetManager
		*/

		public function getType(){
			return $this->_type;
		}

		/**
		 * configuration
		 * @access protected
		 * @param $data array
		 * @return void
		 * @since 3.0
		 * @package system\AssetManager
		*/

		protected function _setFiles($data = array()){
			foreach ($data as $value) {
				$value = preg_replace('#\\n#isU', '', $value);
				$value = preg_replace('#\\r#isU', '', $value);
				$value = preg_replace('#\\t#isU', '', $value);

				if(is_file(trim($value))){
					if(empty($this->_data[''.$value.''])){
						$this->_setFile($value);
					}
				}
				else if(is_dir(trim($value))){
					$this->_setDir($value);
				}
			}

			$this->_cache = $this->cache(sha1($this->_name).'.'.$this->_type, "", $this->_time);

			if($this->_cache->isDie()){
				$this->_compress();
				$this->_save();
			}
		}

		/**
		 * configure one file
		 * @access public
		 * @param $path string
		 * @return void
		 * @since 3.0
		 * @package system\AssetManager
		*/

		protected function _setFile($path){
			$this->_name .= $path;
			$this->_data[''.$path.''] = file_get_contents($path);

			if($this->_type == 'css'){
				$this->_currentPath = dirname($path).'/';
				$this->_data[''.$path.''] = preg_replace_callback('`url\((.*)\)`isU', array('system\AssetManager\AssetManager', '_parseRelativePathCssUrl'), $this->_data[''.$path.'']);
				$this->_data[''.$path.''] = preg_replace_callback('`src=\'(.*)\'`isU', array('system\AssetManager\AssetManager', '_parseRelativePathCssSrc'), $this->_data[''.$path.'']);
			}
		}

		/**
		 * configure a directory
		 * @access public
		 * @param $path string
		 * @return void
		 * @since 3.0
		 * @package system\AssetManager
		 */

		protected function _setDir($path){
			if ($handle = opendir($path)) {
				while (false !== ($entry = readdir($handle))) {
					$extension = explode('.', basename($entry));
					$ext = $extension[count($extension)-1];

					if($ext == $this->_type){
						$this->_setFile($path.$entry);
					}
				}

				closedir($handle);
			}
		}

		/**
		 * parse url()
		 * @access public
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\AssetManager
		*/

		protected function _parseRelativePathCssUrl($m){
			return 'url('.$this->_parseRelativePathCss($m).')';
		}

		/**
		 * parse src=""
		 * @access public
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\AssetManager
		*/

		protected function _parseRelativePathCssSrc($m){
			return 'src=\''.$this->_parseRelativePathCss($m).'\'';
		}

		/**
		 * correct wrong links
		 * @access public
		 * @param $m array
		 * @return string
		 * @since 3.0
		 * @package system\AssetManager
		*/

		protected function _parseRelativePathCss($m){

		}

		/**
		 * concatenate parser content
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\AssetManager
		*/

		protected function _compress(){
			foreach ($this->_data as $value) {
				$this->_concatenedContent .= $value;
			}

			if($this->_type == 'css'){
				$this->_concatenedContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $this->_concatenedContent);
				$this->_concatenedContent = str_replace(': ', ':', $this->_concatenedContent);
				$this->_concatenedContent = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $this->_concatenedContent);
			}
		}

		/**
		 * save content in cache
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\AssetManager
		*/

		protected function _save(){
			$this->_cache->setContent($this->_concatenedContent);
			$this->_cache->setCache();
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system\AssetManager
		*/

		public function __destruct(){
		}
	}