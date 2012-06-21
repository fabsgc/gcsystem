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
		private $lang; // gestion des langues via des fichiers XML
		private $langInstance;
		
		public  function __construct($lang=""){
			if(!$lang){ $this->lang=$this->getLangClient(); } else { $this->lang=$lang; }
			$this->createLangInstance();
			$tpl = new templateGC('GCsystemDev', 'GCsystemDev', 0, $lang="");
			$tpl->assign(array(
				'text'=>$this->useLang('appdev_temp'),
				'IMG_PATH'=>IMG_PATH
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
		
		public  function __desctuct(){
		
		}
	}
?>