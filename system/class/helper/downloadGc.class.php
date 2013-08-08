<?php
	/**
	 * @file : downloadGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les téléchargement
	 * @version : 2.0 bêta
	*/
	
	class downloadGc extends fileGc{
		
		const NAME_DEFAULT             	  = 'telechargement'                           ;
		
		protected $_listExt               = array(self::EXT_GZ, self::EXT_PDF, self::EXT_JS, self::EXT_OGG, self::EXT_EXE,
												self::EXT_DOC, self::EXT_XLS, self::EXT_PPT, self::EXT_DEFAULT, self::EXT_XML,
												self::EXT_FLASH, self::EXT_JSON, self::EXT_PNG, self::EXT_GIF, self::EXT_JPG,
												self::EXT_TIFF, self::EXT_ICO, self::EXT_SVG, self::EXT_JPEG, self::EXT_TXT,
												self::EXT_HTM, self::EXT_HTML, self::EXT_CSV, self::EXT_MPEGAUDIO, self::EXT_MP3,
												self::EXT_RPL, self::EXT_WAV, self::EXT_MPEG, self::EXT_MP4, self::EXT_QUICKTIME,
												self::EXT_WMV, self::EXT_AVI, self::EXT_FLV, self::EXT_ODT, self::EXT_ODTCALC,
												self::EXT_ODTPRE, self::EXT_ODTGRA, self::EXT_XLS2007, self::EXT_DOC2007, self::XUL,
												self::TAR, self::TGZ)                  ;
												
		protected $_fileNameDownload                                                   ;
		protected $_fileSize                                                           ;
		protected $_fileMd5                                                            ;
		protected $_succesParameters      = true                                       ;
		protected $_dateformat            = 'D, d M Y H:i:s'                           ;
		
		/**
		 * Crée l'instance de la classe
		 * @param string $filepath : chemin vers le fichier qu'il faudra faire télécharger
		 * @access public
		 * @return void
		 * @since 2.0
		*/

		public function __construct($filepath, $filename, $fileext){
			if($filepath == "") { $filepath = 'no'; $this->_succesParameters = false; $this->_addError('aucun fichier n\'a été spécifié. Le téléchargement ne pourra pas être lancé', __FILE__, __LINE__, ERROR); }
			if($filename == "") { $filename = self::NAME_DEFAULT; }
			
			if(is_file($filepath) && file_exists($filepath) && is_readable($filepath)) $this->setFile($filepath, $filename, $fileext);
				else $this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
		}

		/**
		 * Retourne le type du fichier
		 * @access public
		 * @return void
		 * @since 2.0
		*/
		
		public function getFileExt(){
			return $this->_fileExt;
		}

		/**
		 * Retourne le nom du fichier
		 * @access public
		 * @return void
		 * @since 2.0
		*/
		
		public function getFileNameDownload(){
			return $this->_fileNameDownload;
		}

		/**
		 * Permet de modifier le fichier à télécharger
		 * @param string $dirpath : chemin vers le répertoire
		 * @access public
		 * @return void
		 * @since 2.0
		*/
		
		public function setFile($filepath, $filename="nom", $fileext=downloadGc::EXT_DEFAULT){
			if(is_file($filepath) && file_exists($filepath) && is_readable($filepath)){
				$this->_setFilePath($filepath);
				$this->_setFileName($filepath);
				$this->_setFileExt($fileext);
				$this->_setFileInfo($filepath);
				$this->_setFileContent($filepath);
				$this->_setFileChmod($filepath);
				$this->_setFileNameDownload($filename);
				$this->_isExist = true;
			}
			else{
				$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
			}
		}

		/**
		 * Récupère le type du fichier
		 * @param string $appext : type du fichier entré par le client
		 * @access protected
		 * @return void
		 * @since 2.0
		*/
		
		protected function _setFileExt($appext){
			if($appext == NULL){
				$this->_fileExt = self::EXT_DEFAULT;
			}
			elseif(in_array($appext, $this->_listExt)){
				$this->_fileExt = $appext;
			}
			else{
				$this->_fileExt = self::EXT_DEFAULT;
				$this->_addError('L\'extension n\'est pas gérée pas la classe, le processus de téléchargement ne fonctionnera peut-être pas', __FILE__, __LINE__, ERROR);
			}
		}

		/**
		 * Donne un nom au fichier téléchargé
		 * @param string $file : nom
		 * @access protected
		 * @return void
		 * @since 2.0
		*/
		
		protected function _setFileNameDownload($file){
			$this->_fileNameDownload = $file;
		}

		/**
		 * Envoie le téléchargement du fichier
		 * @access protected
		 * @return void
		 * @since 2.0
		*/
		
		public function download(){
			$this->_fileSize = filesize($this->_filePath);
			$this->_fileMd5  = md5_file($this->_filePath);
		
			error_reporting(0);
			ini_set('zlib.output_compression', 0);
			header('Pragma: public');
			header('Last-Modified: '.gmdate($this->_dateformat).' GMT');
			header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
			header('Content-Tranfer-Encoding: '.$this->_fileExt.'');
			header('Content-Length: '.$this->_fileSize);
			header('Content-MD5: '.base64_encode($this->_fileMd5));
			header('Content-Type: "'.$this->_fileExt.'"');
			header('Content-Disposition: attachment; filename="'.$this->_fileNameDownload.'"');
			header('Date: '.gmdate($this->_dateformat, time()).' GMT');
			header('Expires: '.gmdate($this->_dateformat, time()+1).' GMT');
			header('Last-Modified: '.gmdate($this->_dateformat, time()).' GMT');
			
			if(readfile($this->_filePath))
				return true;
		}

		/**
		 * Desctructeur
		 * @access public
		 * @return void
		 * @since 2.0
		*/
		
		public  function __destruct(){
		}
	}