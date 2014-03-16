<?php
	/**
	 * @file : appDev.class.php
	 * @author : fab@c++
	 * @description : class à utiliser lors du développement de l'application
	 * @version : 2.3 Bêta
	*/
	
	namespace system{
		class appDev{
			use langInstance;
			
			protected $_timeExec                  ; //calcul du temps d'exécution
			protected $_timeExecStart             ; //time de départ
			protected $_timeExecEnd               ; //time de fin
			protected $_controller       = array(); //liste des controller
			protected $_template         = array(); //liste des templates
			protected $_sql              = array(); //liste des requêtes sql
			protected $_arbo                      ; //liste des fichiers inclus
			protected $_show             = 0      ; //liste des fichiers inclus
			protected $_setShow          = true   ; //liste des fichiers inclus
			
			public  function __construct($lang=NULL){
				if($lang==NULL){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();
				$this->_timeExecStart=microtime(true);
				$this->_setShow = DEVTOOL;
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
							$sql .= $val."\n";
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
						
						$tpl = new template(GCSYSTEM_PATH.'GCsystemDev', 'GCsystemDev', '10000', $this->_lang);
						$tpl->assign(array(
							'text'=>$this->useLang('appDev_temp'),
							'timeexec' => round($this->_timeExecEnd,2),
							'http' => $controller,
							'tpl' => $template,
							'sql' => $sql,
							'arbo' => $this->_arbo,
							'memory' => (memory_get_usage(true)/1024)
						));
							
						$tpl->show();
						$this->_show =1;
					}
				}
			}
			
			public function getShow(){
				return $this->_setShow;
			}
			
			public function setShow($show){
				$this->_setShow = $show;
			}
			
			public function setTimeExec(){
				$this->_timeExecEnd=microtime(true);
				$this->_timeExecEnd=($this->_timeExecEnd-$this->_timeExecStart)*1000;
			}
			
			public function addRubrique($val){
				array_push($this->_controller, $val);
			}
			
			public function addTemplate($val){
				array_push($this->_template, $val);
			}
			
			public function addSql($val){
				array_push($this->_sql, $val);
			}
			
			public  function __destruct(){
			}
		}
	}