<?php
	/*\
	 | ------------------------------------------------------
	 | @file : downloadGc.class.php
	 | @author : fab@c++
	 | @description : class gérant les téléchargement
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class downloadGc extends fileGc{
		
		const EXT_ZIP                   = 'application/gzip'                         ;
		const EXT_GZ                    = 'application/x-gzip'                       ;
		const EXT_PDF                   = 'application/pdf'                          ;
		const EXT_PNG                   = 'image/png'                                ;
		const EXT_GIF                   = 'image/gif'                                ;
		const EXT_JPG                   = 'image/jpeg'                               ;
		const EXT_JPEG                  = 'image/jpeg'                               ;
		const EXT_TXT                   = 'text/plain'                               ;
		const EXT_HTM                   = 'text/html'                                ;
		const EXT_HTML                  = 'text/html'                                ;
		const EXT_JS                    = 'application/javascript'                                ;
		const EXT_EXE                   = 'application/octet-stream'                 ;
		const EXT_DOC                   = 'application/msword'                       ;
		const EXT_XLS                   = 'application/vnd.ms-excel'                 ;
		const EXT_PPT                   = 'application/vnd.ms-powerpoint'            ;
		const EXT_DEFAULT               = 'application/force-download'               ;
		
		const NAME_DEFAULT              = 'telechargement'                           ;
		
		private $_listExt        = array(self::EXT_ZIP, self::EXT_GZ, self::EXT_PDF, self::EXT_PNG, self::EXT_GIF, 
										 self::EXT_JPG, self::EXT_JPEG, self::EXT_TXT, self::EXT_HTM, self::EXT_HTML, 
										 self::EXT_EXE, self::EXT_XLS, self::EXT_PPT, self::EXT_DEFAULT);
												
		private $_fileNameDownload                                                   ;
		private $_fileSize                                                           ;
		private $_fileMd5                                                            ;
		private $_succesParameters       = true                                      ;
		private $_dateformat      = 'D, d M Y H:i:s'                          ;
		
		public function __construct($filepath, $filename, $fileext){
			if($filepath == "") { $filepath = 'no'; $this->_succesParameters = false; $this->_addError('aucun fichier n\'a été spécifié. Le téléchargement ne pourra pas être lancé'); }
			if($filename == "") { $filename = self::NAME_DEFAULT; }
			
			if(is_file($filepath)) $this->setFile($filepath, $filename, $fileext);
				else $this->_addError(self::NOACCESS);
		}
		
		public function getFileExt(){
			return $this->_fileExt;
		}
		
		public function getFileNameDownload(){
			return $this->_fileNameDownload;
		}
		
		public function setFile($filepath, $filename, $fileext){
			if(is_file($filepath)){
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
				$this->_addError(self::NOACCESS);
			}
		}
		
		protected function _setFileExt($appext){
			if($appext == NULL){
				$this->_fileExt = self::EXT_DEFAULT;
			}
			elseif(in_array($appext, $this->_listExt)){
				$this->_fileExt = $appext;
			}
			else{
				$this->_fileExt = self::EXT_DEFAULT;
				$this->_addError('L\'extension n\'est pas gérée pas la classe, le processus de téléchargement ne fonctionnera peut-être pas');
			}
		}
		
		protected function _setFileNameDownload($file){
			$this->_fileNameDownload = $file;
		}
		
		public function download(){
			$this->_fileSize = filesize($this->_filePath);
			$this->_fileMd5 = md5_file($this->_filePath);
		
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
			exit();
		}
		
		public  function __desctuct(){
		}
	}
?>