<?php
	/*\
	 | ------------------------------------------------------
	 | @file : firewall.class.php
	 | @author : fab@c++
	 | @description : allow you to protect your url(s) against attacks
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
	    class firewall{
			use error, facades, langInstance, resolve, url;

			protected $_configFirewall;
			protected $_csrf   = array();
			protected $_logged;
			protected $_role;
			
			/**
			 * init lang class
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @since 3.0
 			 * @package system
			*/
			
			public function __construct(&$profiler, &$config, &$request, &$response, $lang){
				$this->profiler = $profiler;
				$this->config = $config;
				$this->request = $request;
				$this->response = $response;
				$this->lang = $lang;
				$this->_createLangInstance();

				$this->_configFirewall = &$this->config->config['firewall'][''.$this->request->src.''];
				$this->_setFirewall();
			}

			/**
			 * set firewall configuration
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			protected function _setFirewall(){
				$csrf = explode('.', $this->_configFirewall['csrf']['name']);
				$logged = explode('.', $this->_configFirewall['logged']['name']);
				$role = explode('.', $this->_configFirewall['roles']['name']);

				$this->_csrf['POST'] = $this->_setFirewallConfigArray($_POST, $csrf);
				$this->_csrf['GET'] = $this->_setFirewallConfigArray($_GET, $csrf);
				$this->_csrf['SESSION'] = $this->_setFirewallConfigArray($_SESSION, $csrf);
				$this->_logged = $this->_setFirewallConfigArray($_SESSION, $logged);
				$this->_role = $this->_setFirewallConfigArray($_SESSION, $role);
			}

			/**
			 * get token, logged and role value from environment
			 * @access public
			 * @param $in array : array which contain the value
			 * @param $array array : "path" to the value in $in
			 * @return mixed
			 * @since 3.0
 			 * @package system
			*/

			protected function _setFirewallConfigArray($in, $array){
				$to = '';

				if(isset($in[''.$array[0].''])){
					$to = $in[''.$array[0].''];
					array_splice($array, 0, 1);

					foreach ($array as $key => $value) {
						if(isset($to[''.$value.''])){
							$to = $to[''.$value.''];
						}
						else{
							return false;
						}
					}
				}
				else{
					return false;
				}

				return $to;
			}

			/**
			 * check authorization to allow to a visitor to load a page
			 * @access public
			 * @return array
			 * @since 3.0
 			 * @package system
			*/
			
			public function check(){
				if($this->_checkCsrf() == true){
					switch ($this->request->logged) {
						case '*' :
							return true;
						break;
						
						case 'true' :
							if($this->_checkLogged()){
								if($this->_checkRole()){
									return true;
								}
								else{
									$t = $this->template($this->_configFirewall['forbidden']['template'], 'GCfirewallForbidden', 0);
									foreach($this->_configFirewall['forbidden']['variable'] as $val){
										if($val['type'] == 'var'){
											$t->assign(array($val['name']=>$val['value']));
										}
										else{
											$t->assign(array($val['name']=>$this->useLang($val['value'])));
										}
									}
									echo $t->show();

									$this->addError('The access to the page '.$this->request->src.'/'.$this->request->controller.'/'.$this->request->action.' is forbidden', __FILE__, __LINE__, ERROR_FATAL);
											
									return false;
								}
							}
							else{
								$this->addError('The access to the page '.$this->request->src.'/'.$this->request->controller.'/'.$this->request->action.' is forbidden because the user must be logged', __FILE__, __LINE__, ERROR_FATAL);
								$url = $this->getUrl($this->_configFirewall['login']['name'], $this->_configFirewall['login']['vars']);

								if($url != ""){
									$this->response->header('Location: '.$url);
									return false;
								}
								else{
									$this->addError('The firewall failed to redirect the user to the url '.$url, __FILE__, __LINE__, ERROR_FATAL);
									return false;
								}

								return false;
							}
						break;

						case 'false' :
							if($this->_checkLogged() == false){
								return true;
							}
							else{
								$this->addError('The access to the page '.$this->request->src.'/'.$this->request->controller.'/'.$this->request->action.' is forbidden because the user mustn\'t be logged', __FILE__, __LINE__, ERROR_FATAL);	
								$url = $this->getUrl($this->_configFirewall['default']['name'], $this->_configFirewall['default']['vars']);

								if($url != ""){
									$this->response->header('Location:'.$url);
								}
								else{
									$this->addError('The firewall failed to redirect the user to the url '.$url, __FILE__, __LINE__, ERROR_FATAL);
									return false;
								}

								return false;
							}
						break;
					}
				}
				else{
					$t = $this->template($this->_configFirewall['csrf']['template'], 'GCfirewallForbidden', 0);						
					foreach($this->_configFirewall['csrf']['variable'] as $val){
						if($val['type'] == 'var'){
							$t->assign(array($val['name']=>$val['value']));
						}
						else{
							$t->assign(array($val['name']=>$this->useLang($val['value'])));
						}
					}
					echo $t->show();
					$this->addError('The access to the page '.$this->request->src.'/'.$this->request->controller.'/'.$this->request->action.' is forbidden : CSRF error', __FILE__, __LINE__, ERROR_FATAL);
							
					return false;
				}

				return true;
			}

			/**
			 * check csrf
			 * @access protected
			 * @return boolean
			 * @since 3.0
 			 * @package system
			*/

			protected function _checkCsrf(){
				if($this->_configFirewall['csrf']['enabled'] == true && $this->request->logged == true){
					if($this->_csrf['SESSION'] != false && ($this->_csrf['GET'] != false || $this->_csrf['POST'] != false)){
						if($this->_csrf['POST'] == $this->_csrf['SESSION'] || $this->_csrf['GET'] == $this->_csrf['SESSION']){
							return true;
						}
						else{
							return false;
						}
					}
					else{
						return true;
					}
				}
				else{
					return true;
				}
			}

			/**
			 * check logged
			 * @access protected
			 * @return boolean
			 * @since 3.0
 			 * @package system
			*/

			protected function _checkLogged(){
				return $this->_logged;
			}

			/**
			 * check role
			 * @access protected
			 * @return boolean
			 * @since 3.0
 			 * @package system
			*/
			
			protected function _checkRole(){
				if(in_array($this->_role, array_map('trim', explode(',', $this->request->access))) || $this->request->access == '*'){
					return true;
				}
				else{
					return false;
				}
			}

			/**
			 * Desctructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/
			
			public function __destruct(){
			}
		}
	}