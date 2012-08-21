<?php
	/**
	 * @file : installGc.class.php
	 * @author : fab@c++
	 * @description : class gérant l'installation de rubriques externes
	 * @version : 2.0 bêta
	*/

	class installGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait
		
		protected $_file                           ;

		public  function __construct($lang = 'fr'){
		}

		public  function __destruct(){
		}
	}