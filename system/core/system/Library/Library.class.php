<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Library.class.php
	 | @author : fab@c++
	 | @description : library
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Library;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\Exception\MissingLibraryException;

	class Library{
		use error, langs, facades;

		/**
		 * constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $src string
		 * @since 3.0
		 * @throws \system\Exception\MissingLibraryException
		 * @package system\Library
		*/

		public function __construct (&$profiler, &$config, &$request, &$response, $lang, $src){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;

			foreach($this->config->config['library'][''.$src.''] as $key => $value){
				if($value['enabled'] == 'true'){
					if($this->_checkInclude($value['include']) == true){
						if($src == 'app'){
							$file = APP_RESOURCE_LIBRARY_PATH.$value['access'];
						}
						else{
							$file = SRC_PATH.$src.'/'.SRC_RESOURCE_LIBRARY_PATH.$value['access'];
						}

						if(file_exists($file)){
							require_once($file);
							$this->addError('The library '.$file.' was successfully included', __FILE__, __LINE__, ERROR_INFORMATION);
						}
						else{
							throw new MissingLibraryException('The library '.$file.' could not be included');
						}
					}
				}
			}
		}

		/**
		 * check if the library can be included
		 * @access protected
		 * @param $include string
		 * @return boolean
		 * @since 3.0
		 * @package system\Library
		*/

		protected function _checkInclude($include){
			if($include == '*'){
				return true;
			}
			else if(preg_match('#no\[(.*)\]#isU', $include, $matches)){
				$match = array_map('trim', explode(',', $matches[1]));

				if(
					in_array('.'.$this->request->src, $match) || 
					in_array('.'.$this->request->src.'.'.$this->request->controller, $match) || 
					in_array('.'.$this->request->src.'.'.$this->request->controller.'.'.$this->request->action, $match) ||
					in_array($this->request->controller, $match) || 
					in_array($this->request->controller.'.'.$this->request->action, $match)
				){
					return false;
				}
				else{
					return true;
				}
			}
			else if(preg_match('#yes\[(.*)\]#isU', $include, $matches)){
				$match = explode(',', $matches[1]);

				if(
					in_array('.'.$this->request->src, $match) || 
					in_array('.'.$this->request->src.'.'.$this->request->controller, $match) || 
					in_array('.'.$this->request->src.'.'.$this->request->controller.'.'.$this->request->action, $match) ||
					in_array($this->request->controller, $match) || 
					in_array($this->request->controller.'.'.$this->request->action, $match)
				){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system\Library
		*/

		public function __destruct(){
		}
	}