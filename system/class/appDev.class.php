<?php
	/*\
	 | ------------------------------------------------------
	 | @file : appDev.class.php
	 | @author : fab@c++
	 | @description : class à utiliser lors du développement de l'application
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class appDev{
		private $lang               ; // gestion des langues via des fichiers XML
		private $langInstance       ;
		private $timeExec           ;
		private $timeExecStart      ;
		private $timeExecEnd        ;
		private $rubrique   =array();
		private $template   =array();
		private $sql        =array();
		private $arbo               ;
		
		public  function __construct($lang=""){
			if(!$lang){ $this->lang=$this->getLangClient(); } else { $this->lang=$lang; }
			$this->createLangInstance();
			$this->timeExecStart=microtime(true);
		}
		
		public function show(){
			$this->rubrique = get_included_files();
			self::setTimeExec();
			foreach($this->rubrique as $val){
				$rubrique .= $val.'<br />';
			}
			foreach($this->template as $val){
				$template .= $val.'<br />';
			}
			foreach($this->sql as $val){
				$sql .= $val.'<br />';
			}
			
			
			foreach($_GET as $cle => $val){
				$this->arbo .="".$cle."::".$val."#";
			}
			foreach($_POST as $cle => $val){
				$this->arbo .="".$cle."::".$val."#";
			}
			
			$tpl = new templateGC('GCsystemDev', 'GCsystemDev', 0, $lang="");
			$tpl->assign(array(
				'text'=>$this->useLang('appdev_temp'),
				'IMG_PATH'=>IMG_PATH,
				'timeexec' => round($this->timeExecEnd,2),
				'http' => $rubrique,
				'tpl' => $template,
				'sql' => $sql,
				'arbo' => $this->arbo,
				'memory' => (memory_get_usage(true)/1024)
			));
				
			$tpl->show();
		}
		
		private function createLangInstance(){
			$this->langInstance = new lang($this->lang);
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
		
		public function setLogXml($type, $contenu){
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