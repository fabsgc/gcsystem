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
		private $_error                  = array() ; //array contenant toutes les erreurs enregistrées
		private $_lang                             ; //gestion des langues via des fichiers XML
		private $_langInstance                     ; //instance de la class langGc
		private $_timestamp                        ; //timestamp
		private $_date                             ; //contient la date en date
		private $_i                                ; //compteur
		private $_ago                    = array() ; //tableau contenant la liste sous forme de année-mois-jour-heure-minute-seconde
		private $_age                    = array() ; //tableau contenant la liste sous forme de année-mois-jour-heure-minute-seconde
		private $_dayMonth               = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) ; //tableau contenant le nombre de jour pour chaque mois
		
		const NDAY                       = 6       ; //nombre de jour 0-6
		private $_dayLang                = array('fr' => array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'),
		                                         'en' => array('Monday', 'Thuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
		                                         'nl' => array('maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'),
		                                         'es' => array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
		                                         'de' => array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'),
		                                         'php'=> array('Mon', 'Thu', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'));
		
		const NMONTH                     = 11      ; //nombre de mois 0-11
		private $_monthLang              = array('fr' => array('janvier' ,'février' ,'mars' ,'avril' ,'mai' ,'juin' ,'juillet' ,'août' ,'septembre' ,'octobre' ,'novembre' ,'decembre'),
		                                         'en' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
		                                         'nl' => array('januari' ,'februari' ,'maart' ,'april' ,'mei' ,'juni' ,'juli' ,'augustus' ,'september' ,'oktober' ,'november' ,'december'),
		                                         'es' => array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio??', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'),
		                                         'de' => array('Januar', 'Februar', 'März', 'April', 'könnte', 'June', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'),
		                                         'php'=> array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'));
		
		private $_zones                  = array('UM12' => -12, 'UM11' => -11, 'UM10' => -10, 'UM95' => -9.5, 'UM9' => -9, 'UM8' => -8, 'UM7' => -7, 'UM6' => -6,
												 'UM5' => -5, 'UM45' => -4.5, 'UM4' => -4, 'UM35' => -3.5, 'UM3' => -3, 'UM2' => -2, 'UM1' => -1, 'UTC' => 0,
												 'UP1' => +1, 'UP2' => +2, 'UP3' => +3, 'UP35' => +3.5, 'UP4' => +4, 'UP45' => +4.5, 'UP5' => +5, 'UP55' => +5.5,
												 'UP575' => +5.75, 'UP6' => +6, 'UP65' => +6.5, 'UP7' => +7, 'UP8' => +8, 'UP875' => +8.75, 'UP9' => +9, 'UP95' => +9.5,
												 'UP10' => +10, 'UP105' => +10.5, 'UP11' => +11, 'UP115' => +11.5, 'UP12' => +12, 'UP1275' => +12.75, 'UP13' => +13, 'UP14' => +14);
		
		const PARAM_TIMESTAMP            =1;  //permet aux fonctions de gérer soit une date au format mktime stockée dans un tableau soit un timestamps normal
		const PARAM_DATETIME             =2;
		
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
		
		const DATE_COMPLETE_H_ES_1               = 'd/m/Y \a \l\a(\s\) h';
		const DATE_COMPLETE_H_ES_2               = 'j \d\e M \d\e Y \a \l\a(\s\) h';
		const DATE_COMPLETE_HM_ES_1              = 'd/m/Y \a \l\a(\s\) h \y i\m';
		const DATE_COMPLETE_HM_ES_2              = 'j \d\e M \d\e Y \a \l\a(\s\) h \y i \m\i\n\u\t\o\(\s\)';
		const DATE_COMPLETE_ES_1                 = 'd/m/Y \a \l\a(\s\) h \y i\m \y s\s';
		const DATE_COMPLETE_ES_2                 = 'j \d\e M \d\e Y \a \l\a(\s\) h \y i \m\i\n\u\t\o\(\s\) \y s \s\e\c\u\n\d\a\(\s\)';
		
		public  function __construct($lang=""){
			$this->_langInstance;
			$this->_createLangInstance();
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
		}
		
		public function getDate($time=NULL, $format=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			return date($format, $time);
		}
		
		public function getDateFr($time=NULL, $format=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->_date = $this->getDate($time, $format, $param = self::PARAM_TIMESTAMP);
			$this->_date = $this->_dayToLang('fr', $this->_date );
			$this->_date = $this->_monthToLang('fr', $this->_date );
			return $this->_date;
		}
		
		public function getDateEn($time=NULL, $format=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->_date = $this->getDate($time, $format);
			$this->_date = $this->_dayToLang('en', $this->_date);
			$this->_date = $this->_monthToLang('en', $this->_date);
			return $this->_date;
		}
		
		public function getDateNl($time=NULL, $format=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->_date = $this->getDate($time, $format);
			$this->_date = $this->_dayToLang('nl', $this->_date);
			$this->_date = $this->_monthToLang('nl', $this->_date);
			return $this->_date;
		}
		
		public function getDateDe($time=NULL, $format=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->_date = $this->getDate($time, $format);
			$this->_date = $this->_dayToLang('de', $this->_date);
			$this->_date = $this->_monthToLang('de', $this->_date);
			return $this->_date;
		}
		
		public function getDateEs($time=NULL, $format=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			if($format == NULL) $format = self::DATE_DEFAULT;
			
			$this->_date = $this->getDate($time, $format);
			$this->_date = $this->_dayToLang('es', $this->_date);
			$this->_date = $this->_monthToLang('es', $this->_date);
			return $this->_date;
		}
		
		public function getDecalTimeZone($time=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			return date('Z', $time);
		}
		
		public function getAge($time=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			
			$t = time();
			$age = ($time < 0) ? ( $t + ($time * -1) ) : $t - $time;
			$year = 60 * 60 * 24 * 365;
			
			$ageYears = $age / $year;
			$ageMonth = $ageYears - intval($ageYears);
			return array(intval($ageYears), intval($ageMonth*12));
		}
		
		//by Lucas5190 
		public function getAgo($time=NULL, $time2=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); $time2 = _getDatetoTimestamp($time2); }
			$time = intval($time); $time2 = intval($time2);
			if($time == NULL) $time = time(); if($time2 == NULL) $time2 = time();
			
			$date = $time;
			 $tampon = $time2;
			 $diff = $tampon - $date;

			 $dateDay = date('d', $date);
			 $tamponDay = date('d', $tampon);
			 $diffDay = $tamponDay - $dateDay;

			if($diff < 60 && $diffDay == 0){
				return 'Il y a '.$diff.'s';
			}
			else if($diff < 600 && $diffDay == 0){
				return 'Il y a '.floor($diff/60).'m et '.floor($diff%60).'s';
			}
			else if($diff < 3600 && $diffDay == 0){
				return 'Il y a '.floor($diff/60).'m';
			}
			else if($diff < 7200 && $diffDay == 0){
				return 'Il y a '.floor($diff/3600).'h et '.floor(($diff%3600)/60).'m';
			}
			else if($diff < 24*3600 && $diffDay == 0){
				return 'Aujourd\'hui à '.date('H\hi', $time);
			}
			else if($diff < 48*3600 && $diffDay == 1){
				return 'Hier à '.date('H\hi', $time);
			}
			else{
				return 'Le '.date('d/m/Y', $time).' à '.date('H\hi', $time).'.';
			}
		}
		
		public function getTimeZone($zone='UM0'){
			if($this->_zones[$zone]) return $this->_zones[$zone];
				else $this->addError('fuseau inconnu'); return false;
		}
		
		public function isBissextile($time=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			
			if(date('L', $time) == 1) return true;
				else return false;
		}
		
		public function isSummer($time=NULL, $param = self::PARAM_TIMESTAMP){
			if($param == self::PARAM_DATETIME){ $time = _getDatetoTimestamp($time); }
			$time = intval($time);
			if($time == NULL) $time = time();
			
			if(date('I', $time) == 1) return true;
				else return false;
		}
		
		public function getDayInMonth($time="", $month=""){
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

			return $this->_dayMonth[$month - 1];
		}
		
		private function _dayToLang($lang, $date){
			foreach($this->_dayLang as $cle => $valeur){
				if($cle!=$lang){
					for($this->_i = 0; $this->_i <= self::NDAY; $this->_i++){
						$date = preg_replace('`'.$valeur[$this->_i].'`', $this->_dayLang[$lang][$this->_i], $date);
					}
				}	
			}
			return $date;
		}
		
		private function _monthToLang($lang, $date){
			foreach($this->_monthLang as $cle => $valeur){
				if($cle!=$lang){
					for($this->_i = 0; $this->_i <= self::NMONTH; $this->_i++){
						$date = preg_replace('`'.$valeur[$this->_i].'`', $this->_monthLang[$lang][$this->_i], $date);
					}
				}	
			}
			return $date;
		}
		
		private function _getDatetoTimestamp($date = array()){
			return mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
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
			$this->_langInstance = new langGc($this->_lang);
		}
			
		private function _useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}
		
		private function _showError(){
			foreach($this->_error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		private function _addError($error){
			array_push($this->_error, $error);
		}
		
		public  function __desctuct(){
		
		}
	}
?>