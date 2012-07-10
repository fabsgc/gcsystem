<?php
	/**
	 * @file : logGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les erreurs php
	 * @version : 2.0 bêta
	*/
	
	class TestErrorHandling { 
		protected $error; 
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function __construct () { 
			$this->error = new TestErrorHandler; 
			set_error_handler( array($this->error, 'errorManager' ) ); 
		} 
	} 

	class TestErrorHandler{
		/**
		 * Fonction gérant l'enregistrement des erreurs php dans le fichier de log
		 * @access	public
		 * @return	boolean
		 * @param string $errno : erreur php (constante)
		 * @param string $errstr : erreur php (string)
		 * @param string $errfile : fichier ayant généré l'erreur
		 * @param string $errline : ligne ayant généré l'erreur
		 * @since 2.0
		*/
		
		function errorManager($errno, $errstr, $errfile, $errline){
			switch($errno){
				case E_USER_NOTICE:
					// On ignore :
					break;

				case E_USER_WARNING:
					// on ignore aussi :
					break;

				case E_WARNING:
					// On ignore toujours :
					break;

				case E_USER_ERROR:
					// On envoie un mail aux dev :
					$dest = "contact@legeekcafe.com";
					$sujet = "Erreur sur l'appli legeekcafe.com";
					$d = date("d/m/Y à H:i !",time());
					$msg = sprintf("%s : Erreur n°%d (%s) dans le fichier %s à la ligne %d\n", $d, $errno, $errstr, $errfile, $errline);
		  
					error_log($msg, 1, $dest, "subject: ".$sujet);
					break;

				default:
					// Par défaut, on log l'erreur dans un fichier :
					$d = date("d/m/Y à H:i !",time());
					$msg = sprintf("%s : Erreur n°%d (%s) dans le fichier %s à la ligne %d\n", $d, $errno, $errstr, $errfile, $errline);
					
					error_log($msg, 3, LOG_PATH."errors".LOG_EXT);
					break;
			}

		   return true;
		}
	} 
?>