<?php
	/**
	 * @file : config.class.php
	 * @author : fab@c++
	 * @description : class gérant le fichier de config de l'application
	 * @version : 2.2 bêta
	*/
	
	namespace system{
		class config {
			use error;
			
			/**
			 * Constructeur de la classe. Créé les constantes utilisateurs
			 * @access	public
			 * @return	void
			 * @since 2.0
			*/

			public function __construct(){
				$dom = new htmlparser();
				$dom->load(file_get_contents(APPCONFIG), false, false);

				foreach ($dom->find('define') as $element) {
					if (!defined(strtoupper(CONST_APP_PREFIXE.strval($element->getAttribute('id'))))){

						define(CONST_APP_PREFIXE.strtoupper($element->getAttribute('id')), $element->innertext);
					}
				}
			}

			/**
			 * Destructeur
			 * @access	public
			 * @return	void
			 * @since 2.0
			*/
			
			public function __destruct(){
			}	
		}
	}