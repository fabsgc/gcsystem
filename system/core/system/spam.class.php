<?php
	/*\
	 | ------------------------------------------------------
	 | @file : spam.class.php
	 | @author : fab@c++
	 | @description : allow you to protect your url(s) against spam
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
	    class spam{
			use error, facades, langInstance, resolve;

			protected $_ip         = array()    ;
			protected $_xmlValid   = true       ;
			protected $_xmlContent = ''         ;
			protected $_exception  = false      ;
			protected $_ipClient   = '127.0.0.1';

			const USE_NOT_TPL    = 0;
			const USE_TPL        = 1;
			
			/**
			 * init lang class
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @since 3.0
			*/
			
			public function __construct(&$profiler, &$config, &$request, &$response, $lang){
				$this->profiler = $profiler;
				$this->config = $config;
				$this->request = $request;
				$this->response = $response;
				$this->lang = $lang;

				$this->_createLangInstance();

				$this->_ipClient = $this->request->env('REMOTE_ADDR');

				if($fp = @fopen(APP_CONFIG_SPAM, 'r+')) {
					if($this->_xmlContent = simplexml_load_file(APP_CONFIG_SPAM)){
						if($this->_exception() == false){
							flock($fp, LOCK_EX);
							$this->_setIp();
							flock($fp, LOCK_UN);
						}
					}
					else{
						$this->_xmlValid = true;
						$this->addError('Can\'t open file "'.APP_CONFIG_SPAM.'"', __FILE__, __LINE__, ERROR_ERROR);
					}
				}
			}

			/**
			 * check authorization to allow to a visitor to load a page
			 * @access public
			 * @return array
			 * @since 3.0
			*/
			
			public function check(){
				if($this->_exception == false && $this->_xmlValid == true){
					if(isset($this->_ip['ip']) && $this->_ip['ip'] == $this->_ipClient){
						if($this->_ip['time'] + $this->config->config['spam']['app']['query']['duration'] < time()){
							$this->_updateIp(time(), 1);
							return true;
						}
						elseif($this->_ip['number'] < $this->config->config['spam']['app']['query']['number']){
							$this->_updateIp($this->_ip['time'], $this->_ip['number']+1);
							return true;
						}
						else{
							$t = $this->template($this->config->config['spam']['app']['error']['template'], 'GCspam', 0);	
							
							foreach($this->config->config['spam']['app']['error']['variable'] as $key => $value){
								if($value['type'] == 'var'){
									$t->assign(array($value['name'] => $value['value']));
								}
								else{
									$t->assign(array($value['name'] => $this->useLang($value['value'])));
								}
							}
							
							$t->show();

							$this->addError($this->_ipClient.' : exceeded the number of queries allowed for the page '.$this->request->src.'/'.$this->request->controller.'/'.$this->request->action, __FILE__, __LINE__, ERROR_ERROR);
							return false;
						}
					}
				}

				return true;
			}

			/**
			 * check if the url is a spam exception
			 * @access public
			 * @return array
			 * @since 3.0
			*/

			protected function _exception(){
				$url = '.'.$this->request->src.'.'.$this->request->name;

				if(in_array($url, $this->config->config['spam']['app']['exception'])){
					$this->_exception = true;
					return true;
				}
				else{
					$this->_exception = false;
					return false;
				}
			}

			/**
			 * get the the list of IPs
			 * @access public
			 * @return array
			 * @since 3.0
			*/

			protected function _setIp(){
				$values =  $this->_xmlContent->xpath('//ip');

				foreach ($values as $value) {
					$this->_ip['ip'] = $value['ip']->__toString();
					$this->_ip['number'] = $value['number']->__toString();
					$this->_ip['time'] = $value['time']->__toString();
				}
			}

			/**
			 * update time and number attribute from IP
			 * @access public
			 * @param $time string
			 * @param $number int
			 * @return array
			 * @since 3.0
			*/
			protected function _updateIp($time = 0, $number = 1){
				$values =  $this->_xmlContent->xpath('//ip[@ip=\''.$this->_ip['ip'].'\']');

				foreach ($values as $value) {
					$value['time'] = $time;
					$value['number'] = $number;
					$dom = new \DOMDocument("1.0");
					$dom->preserveWhiteSpace = false;
					$dom->formatOutput = true;
					$dom->loadXML($this->_xmlContent->asXML());
					$dom->save(APP_CONFIG_SPAM);
				}
			}
			
			/**
			 * Desctructor
			 * @access public
			 * @since 3.0
			*/
			
			public function __destruct(){
			}
		}
	}