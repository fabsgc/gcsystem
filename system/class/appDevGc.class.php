<?php
	/*\
	 | ------------------------------------------------------
	 | @file : appDevGc.class.php
	 | @author : fab@c++
	 | @description : class à utiliser lors du développement de l'application
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class appDevGc{
		private $_lang                ; // gestion des langues via des fichiers XML
		private $_langInstance        ;
		private $_timeExec            ; //calcul du temps d'exécution
		private $_timeExecStart       ; //time de départ
		private $_timeExecEnd         ; //time de fin
		private $_rubrique   = array(); //liste des rubrique
		private $_template   = array(); //liste des templates
		private $_sql        = array(); //liste des requêtes sql
		private $_arbo                ; //liste des fichiers inclus
		private $_show      = 0      ; //liste des fichiers inclus
		private $_setShow   = true   ; //liste des fichiers inclus
		
		public  function __construct($lang=""){
			if(!$lang){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
			$this->_timeExecStart=microtime(true);
		}
		
		public function show(){
			if($this->_setShow == true){
				if($this->_show==0){
					$rubrique="";
					$template="";
					$sql="";
					$this->_rubrique = get_included_files();
					self::setTimeExec();
					foreach($this->_rubrique as $val){
						$rubrique .= $val."\n";
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
						$this->_arbo .="".$cle."::".$val."\n";
					}
					$this->_arbo .="----------file--------------\n";
					foreach($_FILES as $cle => $val){
						$this->_arbo .="".$cle."\n";
						foreach($val as $cle2 => $val2){
							$this->_arbo .="-".$cle2."::".$val2."\n";
						}
					}
					
					$tpl = new templateGC('GCsystemDev', 'GCsystemDev', 0, $lang="");
					$tpl->assign(array(
						'text'=>$this->useLang('appDevGc_temp'),
						'IMG_PATH'=>IMG_PATH.'GCsystem/',
						'timeexec' => round($this->_timeExecEnd,2),
						'http' => $rubrique,
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
		
		public function setShow($show){
			$this->_setShow = $show;
		}
		
		private function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}
		
		public function getLangClient(){
			if(!array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) || !$_SERVER['HTTP_ACCEPT_LANGUAGE'] ) { return DEFAULTLANG; }
			else{
				$langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
				$langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
				$langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
				$langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
				return $langcode['0'];
			}
		}
		
		public function setTimeExec(){
			$this->_timeExecEnd=microtime(true);
			$this->_timeExecEnd=($this->_timeExecEnd-$this->_timeExecStart)*1000;
		}
		
		public function addRubrique($val){
			array_push($this->_rubrique, $val);
		}
		
		public function addTemplate($val){
			array_push($this->_template, $val);
		}
		
		public function addSql($val){
			array_push($this->_sql, $val);
		}
		
		public  function __desctuct(){
		
		}
	}
?>