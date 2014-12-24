<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Define.class.php
	 | @author : fab@c++
	 | @description : define
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Define;

	use system\General\error;
	use system\General\facades;
	use system\General\langs;

	class Define{
		use error, langs, facades;

		/**
		 * constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $src string : src
		 * @since 3.0
		 * @package system\Define
		*/

		public function __construct (&$profiler, &$config, &$request, &$response, $lang, $src){
			$this->profiler = $profiler;
			$this->config = $config;
			$this->request = $request;
			$this->response = $response;
			$this->lang = $lang;

			foreach($this->config->config['define'][''.$src.''] as $key => $value){
				$define = strtoupper($src.'_'.DEFINE_PREFIX.strval($key));

				if (!defined($define)){
					define($define, htmlspecialchars_decode($value));
				}
				else{
					$this->addError('The define '.$define.' is already defined', __FILE__, __LINE__, ERROR_WARNING);
				}
			}
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system\Define
		*/

		public function __destruct(){
		}
	}