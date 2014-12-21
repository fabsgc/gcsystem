<?php
	/*\
	 | ------------------------------------------------------
	 | @file : route.class.php
	 | @author : fab@c++
	 | @description : url rewriting
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class router{
			use error;
			
			/** 
			 * contain all the routes
			 * @var array
			*/

			protected $routes = array();

			/**
			 * add route to the instance
			 * @access public
			 * @param $route route : route instance
			 * @return void
			 * @since 3.0
 			 * @package system
			*/
			
			public function addRoute(route $route){
				if (!in_array($route, $this->routes)){
					$this->routes[] = $route;
				}
			}
			
			/**
			 * after url rewriting, return the right route
			 * @access public
			 * @param $url string
			 * @return \system\route
			 * @since 3.0
 			 * @package system
			*/

			public function getRoute($url){
				foreach ($this->routes as $route){
					$url2 = substr($url, strlen(FOLDER), strlen($url));
					if (($varsValues = $route->match($url2)) != false){
						// if she has vars
						if ($route->hasVars()){
							$varsNames = $route->varsNames();
							$listVars = array();
							
							//key : name of the var, value = value
							// (clé = nom de la variable, valeur = sa valeur)
							foreach ($varsValues as $key => $match){
								// the first key contains all the captured string (preg_match)
								if ($key > 0){
									if(array_key_exists($key - 1, $varsNames)){
										$listVars[$varsNames[$key - 1]] = $match;
									}
								}
							}
							
							$route->setVars($listVars);
						}
						
						return $route;
					}
				}
			}
		}
		
		class route{
			use error;
			
			protected $action;
			protected $controller;
			protected $name;
			protected $cache;
			protected $url;
			protected $varsNames;
			protected $vars = array();
			protected $src;
			protected $logged;
			protected $access;

			/**
			 * each route from route.xml become an instance of this class
			 * @access public
			 * @param $url string
			 * @param $controller string
			 * @param $action string
			 * @param $name string
			 * @param $cache int
			 * @param $varsNames array : list of variable from vars=""
			 * @param $src string : location of the file
			 * @since 3.0
 			 * @package system
			*/
			
			public function __construct($url, $controller, $action, $name, $cache, $varsNames = array(), $src, $logged, $access){
				$this->setUrl($url);
				$this->setController($controller);
				$this->setAction($action);
				$this->setName($name);
				$this->setCache($cache);
				$this->setVarsNames($varsNames);
				$this->setSrc($src);
				$this->setLogged($logged);
				$this->setAccess($access);
			}
			
			public function hasVars(){
				return !empty($this->varsNames);
			}
			
			public function match($url){
				if (preg_match('`^'.$this->url.'$`', $url, $matches)){
					return $matches;
				}
				else{
					return false;
				}
			}
			
			public function setAction($action){
				if (is_string($action)){
					$this->action = $action;
				}
			}
			
			public function setName($name){
				if (is_string($name)){
					$this->name = $name;
				}
			}

			public function setCache($cache){
				$this->cache = $cache;
			}
			
			public function setController($controller){
				if (is_string($controller)){
					$this->controller = $controller;
				}
			}
			
			public function setUrl($url){
				if (is_string($url)){
					$this->url = $url;
				}
			}
			
			public function setVarsNames(array $varsNames){
				$this->varsNames = $varsNames;
			}
			
			public function setVars(array $vars){
				$this->vars = $vars;
			}

			public function setSrc($src){
				$this->src = $src;
			}

			public function setLogged($logged){
				$this->logged = $logged;
			}

			public function setAccess($access){
				$this->access = $access;
			}
			
			public function action(){
				return $this->action;
			}
			
			public function name(){
				return $this->name;
			}

			public function cache(){
				return $this->cache;
			}
			
			public function url(){
				return $this->url;
			}
			
			public function controller(){
				return $this->controller;
			}
			
			public function vars(){
				return $this->vars;
			}
			
			public function varsNames(){
				return $this->varsNames;
			}

			public function src(){
				return $this->src;
			}

			public function logged(){
				return $this->logged;
			}

			public function access(){
				return $this->access;
			}

			public function __destruct(){
			}
		}
	}