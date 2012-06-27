<?php
	/*\
	 | ------------------------------------------------------
	 | @file : dateGc.class.php
	 | @author : fab@c++
	 | @description : class gérant les dates
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class dateGc{
		private $error                   = array() ; //array contenant toutes les erreurs enregistrées
		private $lang                              ; //gestion des langues via des fichiers XML
		private $langInstance                      ; //instance de la class langGc
		private $timestamp                         ; //timestamp
		private $date                              ; //contient la date en date
		private $i                                 ; //compteur
		private $ago                     = array() ; //tableau contenant la liste sous forme de année-mois-jour-heure-minute-seconde
		private $age                     = array() ; //tableau contenant la liste sous forme de année-mois-jour-heure-minute-seconde
		private $dayMonth                = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) ; //tableau contenant le nombre de jour pour chaque mois
		
		const NDAY                       = 6       ; //nombre de jour 0-6
		private $dayFr                   = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
		private $dayEn                   = array('Monday', 'Thuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		private $dayNl                   = array('maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag');
		private $dayEs                   = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
		private $dayDe                   = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');
		private $dayPhp                  = array('Mon', 'Thu', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		
		const NMONTH                     = 11      ; //nombre de mois 0-11
		private $monthFr                 = array('janvier' ,'février' ,'mars' ,'avril' ,'mai' ,'juin' ,'juillet' ,'août' ,'septembre' ,'octobre' ,'novembre' ,'decembre');
		private $monthEn                 = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		private $monthNl                 = array('januari' ,'februari' ,'maart' ,'april' ,'mei' ,'juni' ,'juli' ,'augustus' ,'september' ,'oktober' ,'november' ,'december');
		private $monthEs                 = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio??', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		private $monthDe                 = array('Januar', 'Februar', 'März', 'April', 'könnte', 'June', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
		private $monthPhp                = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		
		private $zones                   = array('UM12' => -12, 'UM11' => -11, 'UM10' => -10, 'UM95' => -9.5, 'UM9' => -9, 'UM8' => -8, 'UM7' => -7, 'UM6' => -6,
												 'UM5' => -5, 'UM45' => -4.5, 'UM4' => -4, 'UM35' => -3.5, 'UM3' => -3, 'UM2' => -2, 'UM1' => -1, 'UTC' => 0,
												 'UP1' => +1, 'UP2' => +2, 'UP3' => +3, 'UP35' => +3.5, 'UP4' => +4, 'UP45' => +4.5, 'UP5' => +5, 'UP55' => +5.5,
												 'UP575' => +5.75, 'UP6' => +6, 'UP65' => +6.5, 'UP7' => +7, 'UP8' => +8, 'UP875' => +8.75, 'UP9' => +9, 'UP95' => +9.5,
												 'UP10' => +10, 'UP105' => +10.5, 'UP11' => +11, 'UP115' => +11.5, 'UP12' => +12, 'UP1275' => +12.75, 'UP13' => +13, 'UP14' => +14);
		
		const WEEK                       = 604800;
		const DAY                        = 86400;
		const HOUR                       = 3600;
		const MINUTE                     = 60;
		const DATE_DEFAULT               = 'M d Y H:i';
		
		const DATE_J                             = 'd';
		const DATE_JM_FR                         = 'd/m/';
		const DATE_JM_EN                         = 'd/m/';
		const DATE_JM_US                         = 'm/d/';
		const DATE_JM_DE                         = 'd/m/';
		const DATE_JM_IT                         = 'd-m-';
		const DATE_JMA_FR                        = 'd/m/Y';
		const DATE_JMA_EN                        = 'd/m/Y';
		const DATE_JMA_US                        = 'm/d/Y';
		const DATE_JMA_DE                        = 'd/m/y';
		const DATE_JMA_IT                        = 'd-m-y';
		const DATE_JMA_H_FR                      = 'd/m/Y H';
		const DATE_JMA_H_EN                      = 'd/m/Y H';
		const DATE_JMA_H_US                      = 'm/d/Y H';
		const DATE_JMA_H_DE                      = 'd/m/y H';
		const DATE_JMA_H_IT                      = 'd-m-y H';
		const DATE_JMA_HM_FR                     = 'd/m/Y H:i';
		const DATE_JMA_HM_EN                     = 'd/m/Y H:i';
		const DATE_JMA_HM_US                     = 'm/d/Y H:i';
		const DATE_JMA_HM_DE                     = 'd/m/y H:i';
		const DATE_JMA_HM_IT                     = 'd-m-y H:i';
		const DATE_JMA_HMS_FR                    = 'd/m/Y H:i:s';
		const DATE_JMA_HMS_EN                    = 'd/m/Y H:i:s';
		const DATE_JMA_HMS_US                    = 'm/d/Y H:is';
		const DATE_JMA_HMS_DE                    = 'd/m/y H:i:s';
		const DATE_JMA_HMS_IT                    = 'd-m-y H:i:s';
		const DATE_JMA_HMSU_FR                   = 'd/m/Y H:i:s:u';
		const DATE_JMA_HMSU_EN                   = 'd/m/Y H:i:s:u';
		const DATE_JMA_HMSU_US                   = 'm/d/Y H:i:s:u';
		const DATE_JMA_HMSU_DE                   = 'd/m/y H:i:s:u';
		const DATE_JMA_HMSU_IT                   = 'd-m-y H:i:s:u';
		const DATE_H                             = 'h';
		const DATE_HM                            = 'h:i';
		const DATE_HMS                           = 'h:i:s';
		const DATE_HMSU                          = 'h:i:s:u';
		
		const DATE_COMPLETE_H_FR_1               = 'd/m/Y \à h\h';
		const DATE_COMPLETE_H_FR_2               = 'j M Y \à h \h\e\u\r\e\(\s\)';
		const DATE_COMPLETE_HM_FR_1              = 'd/m/Y \à h\h i\m';
		const DATE_COMPLETE_HM_FR_2              = 'j M Y \à h \h\e\u\r\e\(\s\) i \m\i\n\u\t\e\(\s\)';
		const DATE_COMPLETE_FR_1                 = 'd/m/Y \à h\h i\m s\s';
		const DATE_COMPLETE_FR_2                 = 'j M Y \à h \h\e\u\r\e\(\s\) i \m\i\n\u\t\e\(\s\) s \s\e\c\o\n\d\e\(\s\)';
		
		const DATE_COMPLETE_H_NL_1               = 'd/m/Y \o\p h\u';
		const DATE_COMPLETE_H_NL_2               = 'j M Y \o\p h \u\u\r';
		const DATE_COMPLETE_HM_NL_1              = 'd/m/Y \o\p h\u i\m';
		const DATE_COMPLETE_HM_NL_2              = 'j M Y \o\p h \u\u\r i \m\i\n\u\(\u\)\t\e\(\n\)';
		const DATE_COMPLETE_NL_1                 = 'd/m/Y \o\p h\u i\m s\s';
		const DATE_COMPLETE_NL_2                 = 'j M Y \o\p h \u\u\r i \m\i\n\u\(\u\)\t\e\(\n\) s \s\e\c\o\n\d\e(\n)';
		
		const DATE_COMPLETE_H_EN_1               = 'd/m/Y \a\t h\h';
		const DATE_COMPLETE_H_EN_2               = 'j M Y \a\t h \h\o\u\r\(\s\)';
		const DATE_COMPLETE_HM_EN_1              = 'd/m/Y \a\t h\h i\m';
		const DATE_COMPLETE_HM_EN_2              = 'j M Y \a\t h \h\o\u\r\(\s\) i \m\i\n\u\t\(\s\)';
		const DATE_COMPLETE_EN_1                 = 'd/m/Y \a\t h\h i\m s\s';
		const DATE_COMPLETE_EN_2                 = 'j M Y \a\t h \h\o\u\r\(\s\) i \m\i\n\u\t\(\s\) s \s\e\c\o\n\d\(\s\)';
		
		public  function __construct($lang=""){
			$this->langInstance;
			$this->_createLangInstance();
			if($lang==""){ $this->lang=$this->getLangClient(); } else { $this->lang=$lang; }
		}
		
		public function getDate($time=NULL, $format=NULL){
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			return date($format, $time);
		}
		
		public function getDateFr($time=NULL, $format=NULL){
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->date = $this->getDate($time, $format);

			//day enphp to fr
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayPhp[$this->i].'`', $this->dayFr[$this->i], $this->date);
			}
			//day en to fr
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayEn[$this->i].'`', $this->dayFr[$this->i], $this->date);
			}
			//day nl to fr
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayNl[$this->i].'`', $this->dayFr[$this->i], $this->date);
			}
			
			//month enphp to fr
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthPhp[$this->i].'`', $this->monthFr[$this->i], $this->date);
			}
			//month en to fr
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthEn[$this->i].'`', $this->monthFr[$this->i], $this->date);
			}
			//month nl to fr
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthNl[$this->i].'`', $this->monthFr[$this->i], $this->date);
			}
			
			return $this->date;
		}
		
		public function getDateEn($time=NULL, $format=NULL){
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->date = $this->getDate($time, $format);

			//day enphp to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayPhp[$this->i].'`', $this->dayEn[$this->i], $this->date);
			}
			//day fr to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayFr[$this->i].'`', $this->dayEn[$this->i], $this->date);
			}
			//day nl to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayNl[$this->i].'`', $this->dayEn[$this->i], $this->date);
			}
			
			//month enphp to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthPhp[$this->i].'`', $this->monthEn[$this->i], $this->date);
			}
			//month fr to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthFr[$this->i].'`', $this->monthEn[$this->i], $this->date);
			}
			//month nl to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthNl[$this->i].'`', $this->monthEn[$this->i], $this->date);
			}
			
			return $this->date;
		}
		
		public function getDateNl($time=NULL, $format=NULL){
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->date = $this->getDate($time, $format);

			//day enphp to nl
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayPhp[$this->i].'`', $this->dayNl[$this->i], $this->date);
			}
			//day fr to nl
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayFr[$this->i].'`', $this->dayNl[$this->i], $this->date);
			}
			//day en to nl
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->dayEn[$this->i].'`', $this->dayNl[$this->i], $this->date);
			}
			
			//month enphp to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthPhp[$this->i].'`', $this->monthNl[$this->i], $this->date);
			}
			//month fr to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthFr[$this->i].'`', $this->monthNl[$this->i], $this->date);
			}
			//month nl to en
			for($this->i = 0; $this->i <= self::NDAY; $this->i++){
				$this->date = preg_replace('`'.$this->monthEn[$this->i].'`', $this->monthNl[$this->i], $this->date);
			}
			
			return $this->date;
		}
		
		public function getDecalTimeZone($time=NULL){
			$time = intval($time);
			if($time == NULL) $time = time();
			return date('Z', $time);
		}
		
		public function getAge($time=NULL){
			$time = intval($time);
			if($time == NULL) $time = time();
		}
		
		public function getAgo($time=NULL){
			$time = intval($time);
			if($time == NULL) $time = time();
			return date('Z', $time);
		}
		
		public function isBissextile($time=""){
			$time = intval($time);
			if($time == NULL) $time = time();
			
			if(date('L', $time) == 1) return true;
				else return false;
		}
		
		public function isSummer($time=""){
			$time = intval($time);
			if($time == NULL) $time = time();
			
			if(date('I', $time) == 1) return true;
				else return false;
		}
		
		public function getDayInMonth($month="", $time=""){
			if ($month < 1 OR $month > 12){
				return 0;
			}

			if (!is_numeric($year) OR strlen($year)!= 4){
				$year = date('Y');
			}

			if ($month == 2){
				if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0)){
					return 29;
				}
			}

			return $this->dayMonth[$month - 1];
		}
		
		private function _getLangClient(){
			if(!array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) || !$_SERVER['HTTP_ACCEPT_LANGUAGE'] ) { return DEFAULTLANG; }
			else{
				$langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
				$langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
				$langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
				$langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
				return $langcode['0'];
			}
		}
		
		private function _createLangInstance(){
			$this->langInstance = new langGc($this->lang);
		}
			
		private function _useLang($sentence){
			return $this->langInstance->loadSentence($sentence);
		}
		
		private function _showError(){
			foreach($this->error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		private function _addError($error){
			array_push($this->error, $error);
		}
		
		public  function __desctuct(){
		
		}
	}
?>