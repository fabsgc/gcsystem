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
		private $lang                ; // gestion des langues via des fichiers XML
		private $langInstance        ;
		private $timeExec            ; //calcul du temps d'exécution
		private $timeExecStart       ; //time de départ
		private $timeExecEnd         ; //time de fin
		private $rubrique   = array(); //liste des rubrique
		private $template   = array(); //liste des templates
		private $sql        = array(); //liste des requêtes sql
		private $arbo                ; //liste des fichiers inclus
		private $_show      = 0      ; //liste des fichiers inclus
		private $_setShow   = true   ; //liste des fichiers inclus
		
		public  function __construct($lang=""){
			if(!$lang){ $this->lang=$this->getLangClient(); } else { $this->lang=$lang; }
			$this->_createLangInstance();
			$this->timeExecStart=microtime(true);
		}
		
		public function show(){
			if($this->_setShow == true){
				if($this->_show==0){
					$rubrique="";
					$template="";
					$sql="";
					$this->rubrique = get_included_files();
					self::setTimeExec();
					foreach($this->rubrique as $val){
						$rubrique .= $val."\n";
					}
					foreach($this->template as $val){
						$template .= $val."\n";
					}
					foreach($this->sql as $val){
						$sql .= $val."\n";
					}
					
					$this->arbo .="-----------get------------\n";
					foreach($_GET as $cle => $val){
						$this->arbo .="".$cle."::".$val."\n";
					}
					$this->arbo .="-----------post-----------\n";
					foreach($_POST as $cle => $val){
						$this->arbo .="".$cle."::".$val."\n";
					}
					$this->arbo .="----------session--------\n";
					foreach($_SESSION as $cle => $val){
						$this->arbo .="".$cle."::".$val."\n";
					}
					
					$tpl = new templateGC('GCsystemDev', 'GCsystemDev', 0, $lang="");
					$tpl->assign(array(
						'text'=>$this->useLang('appDevGc_temp'),
						'IMG_PATH'=>IMG_PATH,
						'timeexec' => round($this->timeExecEnd,2),
						'http' => $rubrique,
						'tpl' => $template,
						'sql' => $sql,
						'arbo' => $this->arbo,
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
			$this->langInstance = new langGc($this->lang);
		}
		
		public function useLang($sentence){
			return $this->langInstance->loadSentence($sentence);
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
			$this->timeExecEnd=microtime(true);
			$this->timeExecEnd=($this->timeExecEnd-$this->timeExecStart)*1000;
		}
		
		public function addRubrique($val){
			array_push($this->rubrique, $val);
		}
		
		public function addTemplate($val){
			array_push($this->template, $val);
		}
		
		public function addSql($val){
			array_push($this->sql, $val);
		}
		
		public  function __desctuct(){
		
		}
	}
?>