<?php
	/*\
	 | ------------------------------------------------------
	 | @file : assetManager.class.php
	 | @author : fab@c++
	 | @description : css and js manager system (minify, compress, put in cache file)
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		/**
		 * Class assetManager
		 * @package system
		 */
		class assetManager{
			use error, langInstance, facades;

			protected $_name                    ;  //concatenated name files
			protected $_files          = array();  //files list
			protected $_cache                   ;  //cache file
			protected $_time                    ;  //time cache
			protected $_type                    ;  //js or css
			protected $_currentPath             ;  //chemin du fichier courant
			protected $_concatenedContent       ;  //concatened content, corrected and compressed

			/**
			 * Constructor
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @param $data array :
			 * 		files array
			 * 		cache int
			 * 		type string
			 * @since 3.0
			*/

			public function __construct(&$profiler, &$config, &$request, &$response, $lang, $data = array()){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
			}

			/**
			 * get the ID of the generated file
			 * @access	public
			 * @return string
			 * @since 2.0
			 */
			public function getId(){
				return sha1($this->_name);
			}

			/**
			 * get the type
			 * @access	public
			 * @return string
			 * @since 2.0
			 */
			public function getType(){
				return $this->_type;
			}

			/**
			 * destructor
			 * @access public
			 * @since 3.0
			*/

			public function __destruct(){
			}
		}
	}