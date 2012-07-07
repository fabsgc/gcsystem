<?php
	/**
	 * @file : zipGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers compressés
	 * @version : 2.0 bêta
	*/
	
	class zipGc{
		use errorGc;                                //trait
		
		protected $_file                          ; //chemin vers le fichier compressé
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 *
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public  function __construct(){
		}
		
		/**
		 * Desctructeur
		 *
		 * @access	public
		 * @return	boolean
		 * @since 2.0
		*/
		
		public  function __desctuct(){
		
		}
	}
?>