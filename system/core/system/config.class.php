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
				$domXml = new \DomDocument('1.0', CHARSET);
				if($domXml->load(APPCONFIG)){				
					$nodeXml = $domXml->getElementsByTagName('definitions')->item(0);
					$markupXml = $nodeXml->getElementsByTagName('define');

					foreach($markupXml as $sentence){
						if (!defined(strtoupper(CONST_APP_PREFIXE.strval($sentence->getAttribute("id"))))){
							define(CONST_APP_PREFIXE.strtoupper(strval($sentence->getAttribute("id"))).'', htmlspecialchars_decode(strval($sentence->nodeValue)));
						}
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