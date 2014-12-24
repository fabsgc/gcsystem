<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Firewall.class.php
	 | @author : fab@c++
	 | @description : allow you to protect your url(s) against attacks
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Security;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\General\url;
	use system\General\resolve;

    class Firewall{
		use error, facades, langs, url, resolve;

		protected $_configFirewall;
		protected $_csrf = array();
		protected $_logged;
		protected $_role;
		
		/**
		 * init lang class
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @since 3.0
		 * @package system\Security
		*/
		
		public function __construct(&$profiler, &$config, &$request, &$response, $lang){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_createlang();

			$this->_configFirewall = &$this->config->config['firewall'][''.$this->request->src.''];
			$this->_setFirewall();
		}

		/**
		 * set firewall configuration
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Security
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
		 * @package system\Security
		*/

		protected function _setFirewallConfigArray($in, $array){
			if(isset($in[''.$array[0].''])){
				$to = $in[''.$array[0].''];
				array_splice($array, 0, 1);

				foreach ($array as $value) {
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
		 * @return mixed
		 * @since 3.0
		 * @package system\Security
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
								$t = $this->template($this->_configFirewall['forbidden']['template'], 'gcsfirewall', 0);
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
				$t = $this->template($this->_configFirewall['csrf']['template'], 'gcsfirewall', 0);
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
		 * @package system\Security
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
		 * @package system\Security
		*/

		protected function _checkLogged(){
			return $this->_logged;
		}

		/**
		 * check role
		 * @access protected
		 * @return boolean
		 * @since 3.0
		 * @package system\Security
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
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system\Security
		*/
		
		public function __destruct(){
		}
	}