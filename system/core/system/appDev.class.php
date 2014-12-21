<?php
	/*\
	 | ------------------------------------------------------
	 | @file : appDev.class.php
	 | @author : fab@c++
	 | @description : class à utiliser lors du développement de l'application
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class appDev{
			use langInstance;
			
			protected $_timeExec                  ; //calcul du temps d'exécution
			protected $_timeExecUser     = array(); //les temps d'exécution demandés par l'utilisateur
			protected $_timeExecStart             ; //time de départ
			protected $_timeExecEnd               ; //time de fin
			protected $_controller       = array(); //liste des controller
			protected $_template         = array(); //liste des templates
			protected $_sql              = array(); //liste des requêtes sql
			protected $_arbo                      ; //liste des fichiers inclus
			protected $_show             = 0      ; //liste des fichiers inclus
			protected $_setShow          = true   ; //liste des fichiers inclus
			protected $_profiler         = true   ; //profiler activé ?

			public  function __construct($lang=NULL){
				if($lang==NULL){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();
				$this->_timeExecStart=microtime(true);
				$this->_setShow = true;
			}
			
			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
			}
			
			public function show(){
				if($this->_setShow == true){
					if($this->_show==0){
						$controller="";
						$template="";
						$sql="";
						$this->_controller = get_included_files();
						$this->setTimeExec();

						foreach($this->_controller as $val){
							$controller .= $val."\n";
						}

						foreach($this->_template as $val){
							$template .= $val."\n";
						}

						foreach($this->_sql as $val){
							$sql .= $val['query-executed']."\n";

							$sql .= "######################\n";
						}
						
						$this->_arbo .="-----------get------------\n";
						foreach($_GET as $cle => $val){
							$this->_arbo .="".$cle."::".$val."\n";
						}

						$this->_arbo .="-----------post-----------\n";
						foreach($_POST as $cle => $val){
							$this->_arbo .="".$cle."::".$val."\n";
						}

						$this->_arbo .="----------session--------\n";
						foreach($_SESSION as $cle => $val){
							if(!is_array($val)){
								$this->_arbo .="".$cle."::".$val."\n";
							}
							else{
								$this->_arbo .="".$cle."::Array\n";
							}
						}

						$this->_arbo .="----------file--------------\n";
						foreach($_FILES as $cle => $val){
							$this->_arbo .="".$cle."\n";
							foreach($val as $cle2 => $val2){
								$this->_arbo .="-".$cle2."::".$val2."\n";
							}
						}

						$this->_arbo .="----------cookie--------------\n";
						foreach($_COOKIE as $cle => $val){
							$this->_arbo .="".$cle."::".$val."\n";
						}

						if(DEVTOOLBAR == true){
							$tpl = new template(GCSYSTEM_PATH.'devtool', 'GCdevtool', '10000', $this->_lang);
							$tpl->assign(array(
								'text'=> $this->useLang('gc_appDev_temp'),
								'timeexec' => round($this->_timeExecEnd,2),
								'http' => $controller,
								'tpl' => $template,
								'sql' => $sql,
								'arbo' => $this->_arbo,
								'memory' => (memory_get_usage(true)/1024)
							));

							$this->_show =1;

							echo $tpl->show();
						}
					}
				}
			}

			public function profiler(){
				if($this->_setShow == true){
					$dataProfiler = array();

					$this->setTimeExec();

					$dataProfiler['timeExec'] = round($this->_timeExecEnd,2);
					$dataProfiler['timeExecUser'] = $this->_timeExecUser;
					$dataProfiler['controller'] = get_included_files();
					$dataProfiler['template'] = $this->_template;
					$dataProfiler['sql'] = $this->_sql;
					$dataProfiler['get'] = $_GET;
					$dataProfiler['post'] = $_POST;
					$dataProfiler['session'] = $_SESSION;
					$dataProfiler['cookie'] = $_COOKIE;
					$dataProfiler['files'] = $_FILES;
					$dataProfiler['server'] = $_SERVER;
					$dataProfiler['url'] = $_SERVER['REQUEST_URI'];

					$cache = new cache('gcs_profiler', $dataProfiler, 0);
					$cache->setCache();

					$cacheId = new cache('gcs_profiler_'.$_GET['pageid'], $dataProfiler, 0);
					$cacheId->setCache();
				}
			}
			
			public function getShow(){
				return $this->_setShow;
			}
			
			public function setShow($show){
				$this->_setShow = $show;
			}

			public function setProfiler($profiler){
				$this->_profiler = $profiler;
			}
			
			public function setTimeExec(){
				$this->_timeExecEnd = microtime(true);
				$this->_timeExecEnd = ($this->_timeExecEnd-$this->_timeExecStart)*1000;
			}

			public function setTimeExecUser($name){
				if(count($this->_timeExecUser[''.$name.'']) == 0){
					$this->_timeExecUser[''.$name.''][0] = microtime(true);
					$this->_timeExecUser[''.$name.''][0] = round(($this->_timeExecUser[''.$name.''][0] - $this->_timeExecStart)*1000, 2);
				}
				else{
					$this->_timeExecUser[''.$name.''][1] = microtime(true);
					$this->_timeExecUser[''.$name.''][1] = round(($this->_timeExecUser[''.$name.''][1] - $this->_timeExecStart)*1000, 2);
				}
			}
			
			public function addRubrique($val){
				array_push($this->_controller, $val);
			}
			
			public function addTemplate($val){
				array_push($this->_template, $val);
			}
			
			public function addSql($name, $type, $val){
				if(count($this->_sql[''.$name.'']) == 0){
					$this->_sql[''.$name.''] = array();
				}

				if($type == 'query' || $type == 'query-executed'){
					$this->_sql[''.$name.''][''.$type.''] = $val;
				}
				else{
					$this->_sql[''.$name.'']['vars'][''.$type.''] = $val;
				}
			}

			public function addTimeExecUser($describe, $val){
				$array = array($describe, $val);
				array_push($this->_timeExecUser, $array);
			}
			
			public  function __destruct(){
			}
		}
	}