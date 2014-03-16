<?php
	/**
	 * @file : zip.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers compressés
	 * @version : 2.3 Bêta
	*/
	
	namespace helper{
		class zip extends file{
			protected $_zipContent          = array()                       ;
			protected $_zipFileContent      = array()                       ;
			protected $_zip                                                 ;
			protected $_FileCompressedSize  = array()                       ;
			protected $_FilesCompressedSize                                 ;
			
			const NOPUTDIR                                           = false;
			const PUTDIR                                             = true ;
			const NODIR   = 'le répertoire n\'existe pas'                   ;
			
			/**
			 * Crée l'instance de la classe
			 * @param string $filepath : chemin vers le zip
			 * @access public
			 * @return void
			 * @since 2.0
			*/
			
		 	public function __construct($filepath){
				$filepath = strval($filepath);
				$this->_filePath = $filepath; // pour filetoZip
				if(is_file($filepath) && file_exists($filepath) && is_readable($filepath) and zip_open($filepath)){
					$this->setFile($filepath);
				}
				else{
					$this->_addError(self::NOACCESS.' si vous utilisez putFileToZip et que vous créez un .zip, ne prêtez pas attention à cette erreur', __FILE__, __LINE__, WARNING);
				}
			}
			

			/**
			 * Retourne sous le forme d'un tableau la liste des fichiers (pas des répertoires) contenus dans le zip sous cette forme :
			 *  Array ( [0] => asset/css/default.css [1] => default.css )
			 * @access public
			 * @return array of string
			 * @since 2.0
			*/

		 	public function getContentZip(){
				if($this->_isExist == true){
					$this->_zipContent = array();
					$this->_setZip($this->_filePath);
					
					while ($zip_entry = zip_read($this->_zip)){
						array_push($this->_zipContent, zip_entry_name($zip_entry));
					}
					
					$this->_closeZip($this->_filePath);
					return $this->_zipContent;
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * Retourne sous le forme d'un tableau la liste des fichiers ainsi que de leur contenu (pas des répertoires) contenus dans le zip sous cette forme :
			 * Array(
	    	 *		[asset/css/default.css] => contenu
	    	 *		[default.css] => contenu
	    	 *  )
			 * @access public
			 * @return array of string
			 * @since 2.0
			*/
			
		 	public function getContentFileZip(){
				if($this->_isExist == true){
					$this->_zipContentFile = array();
					$this->_setZip($this->_filePath);
					
					while ($zip_entry = zip_read($this->_zip)){
						$this->_zipContentFile[zip_entry_name($zip_entry)] = zip_entry_read($zip_entry, 900000);
					}
					
					$this->_closeZip($this->_filePath);
					return $this->_zipContentFile;
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}			
			}

			/**
			 * Permet d'extraire tout une archive zip et de la placer dans un répertoire
	    	 * @param string $dir : répertoire de destination
	    	 * @param bool $option : true : replacer les fichiers avec leur arborescence. false : replacer les fichiers à la racine du dossier de destination
	    	 * @param array $filter : array contenant les extensions non autorisées
			 * @access public
			 * @return true or false
			 * @since 2.0
			*/
			
			public function putFileToFtp($dir, $option = self::NOPUTDIR, $filter = array()){
				if($this->_isExist == true){
					if(is_dir($dir) && file_exists($dir)){
						$this->_zipContentFile = array();
						$this->_setZip($this->_filePath);
						
						while ($zip_entry = zip_read($this->_zip)){
							if($option == self::NOPUTDIR){
								//les filtres sont corrects
								if((!in_array((substr(zip_entry_name($zip_entry),-3)), $filter) && !in_array((substr(zip_entry_name($zip_entry),-4)), $filter) && !in_array((substr(zip_entry_name($zip_entry),-2)), $filter) && !in_array((substr(zip_entry_name($zip_entry),-5)))) || count($filter)==0){
									if(!preg_match('#\/$#i', zip_entry_name($zip_entry))){
										file_put_contents($dir.basename(zip_entry_name($zip_entry)), zip_entry_read($zip_entry, 900000));
									}							
								}
							}
							elseif($option == self::PUTDIR){
								if((!in_array((substr(zip_entry_name($zip_entry),-3)), $filter) && !in_array((substr(zip_entry_name($zip_entry),-4)), $filter) && !in_array((substr(zip_entry_name($zip_entry),-2)), $filter) && !in_array((substr(zip_entry_name($zip_entry),-5)))) || count($filter)==0){
									$this->_createDirFtp($dir.zip_entry_name($zip_entry));
									file_put_contents($dir.zip_entry_name($zip_entry), zip_entry_read($zip_entry, 900000));
								}
							}
						}
						
						$this->_closeZip($this->_filePath);
						return true;
					}
					else{
						$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * Permet de remplir une archive zip à partir d'un fichier ou d'un répertoire
	    	 * @param string $path : chemin du fichier ou du répertoire à copier
	    	 * @param bool $option : true : replacer les fichiers avec leur arborescence. false : replacer les fichiers à la racine du dossier de destination
	    	 * @param array $filter : array contenant les extensions non autorisées
			 * @access public
			 * @return true or false
			 * @since 2.0
			*/

		 	public function putFileToZip($path, $option = self::NOPUTDIR, $filter = array()){
				$zip = new ZipArchive();

			  	if($zip->open($this->_filePath, ZipArchive::CREATE) == true || $zip->open($this->_filePath) == TRUE){
					if(preg_match('#\/$#i', $path) && file_exists($path)){ //on doit copier un répertoire
						$dir = new dir($path);
						foreach ($dir->getDirArboContent() as $key => $value){
							if($option == self::NOPUTDIR){
								//les filtres sont corrects
								if((!in_array(substr($key,-3), $filter) && !in_array(substr($key,-4), $filter) && !in_array(substr($key,-2), $filter) && !in_array(substr($key,-5), $filter)) || count($filter)==0){
									if(!preg_match('#\/$#i', $key)){ //c'est un fichier
										$file = new file($key);
										$zip->addFromString($file->getFileName(), $file->getFileContent());
									}
								}
							}
							elseif($option == self::PUTDIR){
								//les filtres sont corrects
								if((!in_array(substr($key,-3), $filter) && !in_array(substr($key,-4), $filter) && !in_array(substr($key,-2), $filter) && !in_array(substr($key,-5), $filter)) || count($filter)==0){
									$zip->addFile($key);
								}
							}
						}
					}
					elseif(file_exists($path)){ //on doit copier un fichier
						if((!in_array((substr($path,-3)), $filter) && !in_array((substr($path,-4)), $filter) && !in_array((substr($path,-2)), $filter)) || count($filter)==0){
							if($option == self::NOPUTDIR){
								$file = new file($path);
								$zip->addFromString($file->getFileName(), $file->getFileContent());
								return true;
							}
							elseif($option == self::PUTDIR){
								$zip->addFile($path);
								return true;
							}
						}
					}
					else{
						$this->_addError('le fichier ou le répertoire de fichiers que vous tentez de copier dans l\'archive n\'existe pas', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError('L\'archive n\'a pas pu être créée', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * retourne le poids total de l'archive
			 * @access public
			 * @return int
			 * @since 2.0
			*/
			
		 	public function getFilesCompressedSize(){ //donne le poids total
				return $this->_FilesCompressedSize;
			}

			/**
			 * retourne le poids de chaque fichier contenu dans l'archive
			 * @access public
			 * @return array of int
			 * @since 2.0
			*/
			
		 	public function getFileCompressedSize(){ //donne un array avec le poids de chaque fichier
				return $this->_FileCompressedSize;
			}

			/**
			 * permet de paramétrer la classe à partir du chemin vers une archive
			 * @access public
			 * @param  string $filepath : chemin vers l'archive
			 * @return void
			 * @since 2.0
			*/
			
		 	public function setFile($filepath){
				$filepath = strval($filepath);
				if(is_file($filepath) && file_exists($filepath) && is_readable($filepath)){
					$this->_setFilePath($filepath);
					$this->_setFileName($filepath);
					$this->_setFileExt($filepath);
					$this->_setFileInfo($filepath);
					$this->_setFileContent($filepath);
					$this->_setFileChmod($filepath);
					$this->_setFileCompressedSize($filepath);
					$this->_setFilesCompressedSize($filepath);
					$this->_isExist = true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
				}
			}

			/**
			 * remplit l\'attribut contenant le poids total de l'archive
			 * @access protected
			 * @param  string $filepath : chemin vers l'archive
			 * @return void
			 * @since 2.0
			*/
			
			protected function _setFilesCompressedSize($filepath){
				$this->_setZip($filepath);

				while ($zip_entry = zip_read($this->_zip)){
					$this->_FilesCompressedSize += zip_entry_compressedsize($zip_entry);
				}
				$this->_closeZip($filepath);
			}

			/**
			 * remplit l\'attribut contenant le poids de chaque fichier de l'archive
			 * @access protected
			 * @param  string $filepath : chemin vers l'archive
			 * @return void
			 * @since 2.0
			*/
			
			protected function _setFileCompressedSize($filepath){
				$this->_setZip($filepath);
				while ($zip_entry = zip_read($this->_zip)){
					$this->_FileCompressedSize[zip_entry_name($zip_entry)] = zip_entry_compressedsize($zip_entry);
				}
				$this->_closeZip($filepath);
			}

			/**
			 * ouvre une archive
			 * @access protected
			 * @param  string $filepath : chemin vers l'archive
			 * @return void
			 * @since 2.0
			*/
			
			protected function _setZip($filepath){
				$this->_zip = zip_open($filepath);	
			}
			
			/**
			 * ferme une archive
			 * @access protected
			 * @param  string $filepath : chemin vers l'archive
			 * @return void
			 * @since 2.0
			*/

			protected function _closeZip($filepath){
				$this->_zip = zip_close($filepath);
			}

			/**
			 * retourne true si le zip existe à l'instanciation false dans le cas contraire
			 * @access protected
			 * @return true or false
			 * @since 2.0
			*/

			public function getIsExist(){
				return $this->_isExist;
			}

			/**
			 * comme avec zip, on ne retourne apparemment que les fichiers et non les dossiers, il faut créer les répertoires avant d'ajouter le fichier :(
			 * @access protected
			 * @param  string $filepath : chemin vers le fichier à créer
			 * @return void
			 * @since 2.0
			*/

			protected function _createDirFtp($filepath){
				$dirs = explode('/', $filepath);
				array_pop($dirs);
				$dir = "";

				foreach($dirs as $key => $value){
					$dir .= $value.'/';

					if(!file_exists($dir)){
						mkdir($dir);
					}
				}
			}
			
			/**
			 * Desctructeur
			 * @access public
			 * @since 2.0
			*/
			
		 	public  function __destruct(){
			}
		}
	}