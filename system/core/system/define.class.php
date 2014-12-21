<?php
	/*\
	 | ------------------------------------------------------
	 | @file : template.class.php
	 | @author : fab@c++
	 | @description : define
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class define{
			use error, langInstance, facades;

			/**
			 * constructor
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @param $src string : src
 			 * @since 3.0
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
						$this->addError('The define '.$define.' is already defined', __FILE__, __LINE__, ERROR_ERROR);
					}
				}
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