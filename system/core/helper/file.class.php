<?php
	/*\
	 | ------------------------------------------------------
	 | @file : file.class.php
	 | @author : fab@c++
	 | @description : class gérant les opérations sur les fichiers, très complète
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace helper{
	    class file extends \system\constMime{
			use \system\error;
			
			protected $_filePath                                   ;
			protected $_fileName                                   ;
			protected $_name                                       ;
			protected $_fileExt                                    ;
			protected $_fileContent                                ;
			protected $_fileChmod                                  ;
			protected $_info                              = array();
			protected $_isExist                           = false  ;
			
			const NOFILE   = 'Aucun fichier n\'a été difini'       ;
			const NOACCESS = 'le fichier n\'est pas accessible'    ;
			const NOREAD   = 'le fichier n\'est pas lisible'       ;
			
			const CHMOD0644                               = 0644   ;
			const CHMOD0755                               = 0755   ;
			const CHMOD0777                               = 0777   ;
			const CHMOD0004                               = 0004   ;
			
			/**
			 * Crée l'instance de la classe
			 * @access	public
			 * @param $filepath string : chemin complet ou relatif vers le fichier
			 * @since 2.0
			*/
			
			public function __construct($filepath){
				if($filepath == NULL) { $filepath = 'empty.txt'; $this->_setFileDefault($filepath); }
				if($filepath != NULL && !is_file($filepath) && file_exists($filepath) && is_readable($filepath)) { $this->_setFileDefault($filepath); }
				
				$filepath = strval($filepath);
				if(is_file($filepath) && file_exists($filepath) && is_readable($filepath)){
					$this->setFile($filepath);
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
				}
			}
			
			/**
			 * Retourne le chemin vers le fichier
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getFilePath(){
				return $this->_filePath;
			}
			
			/**
			 * Retourne le nom du fichier (avec son extension)
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getFileName(){
				return $this->_fileName;
			}

			/**
			 * Retourne le nom du fichier (sans son extension)
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getName(){
				return $this->_name;
			}
			
			/**
			 * Retourne l'extension du fichier
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getFileExt(){
				return $this->_fileExt;
			}
			
			/**
			 * Retourne l'extension du fichier passé en paramètre
			 * @access	public
			 * @param string $ext : chemin du fichier
			 * @return	string
			 * @since 2.0
			*/
			
			public function getExtension($ext){
				$extension = explode('.', basename($ext));
				return $extension[count($extension)-1];
			}
			
			/**
			 * Retourne les informations du fichier dans un array
			 *	0	dev	volume
			 *	1	ino	Numéro d'inode (*)
			 *	2	mode	droit d'accès à l'inode
			 *	3	nlink	nombre de liens
			 *	4	uid	userid du propriétaire (*)
			 *	5	gid	groupid du propriétaire (*)
			 *	6	rdev	type du volume, si le volume est une inode
			 *	7	size	taille en octets
			 *	8	atime	date de dernier accès (Unix timestamp)
			 *	9	mtime	date de dernière modification (Unix timestamp)
			 *	10	ctime	date de dernier changement d'inode (Unix timestamp)
			 *	11	blksize	taille de bloc (**)
			 *	12	blocks	nombre de blocs de 512 octets alloués (**)
			 * @access	public
			 * @return	array
			 * @since 2.0
			*/
			
			public function getFileInfo(){
				return $this->_fileInfo;
			}
			
			/**
			 * Retourne le contenu du fichier
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getFileContent(){
				return $this->_fileContent;
			}
			
			/**
			 * Retourne le chmod du fichier
			 * @access	public
			 * @return	int
			 * @since 2.0
			*/
			
			public function getFileChmod(){
				return $this->_fileChmod;
			}
			
			/**
			 * Retourne la taille du fichier
			 * @access	public
			 * @return	int
			 * @since 2.0
			*/
			
			public function getSize(){
				if($this->_isExist == true){
					return $this->_fileInfo['size'];
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Retourne true si le fichier existe et false si le fichier n'existe pas
			 * @access	public
			 * @return	boolean
			 * @since 2.0
			*/
			
			public function getExist(){
				return $this->_isExist;
			}
			
			/**
			 * Retourne la date du dernier accès au fichier sous la forme d'un timestamp UNIX
			 * @access	public
			 * @return	int
			 * @since 2.0
			*/
			
			public function getLastAccess(){
				if($this->_isExist == true){
					return $this->_fileInfo['atime'];
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Retourne la date de la dernière modification du fichier sous la forme d'un timestamp UNIX
			 * @access	public
			 * @return	int
			 * @since 2.0
			*/
			
			public function getLastUpdate(){
				if($this->_isExist == true){
					return $this->_fileInfo['ctime'];
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Retourne le répertoire contenant le fichier
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getFolder(){
				return dirname($this->_filePath);
			}
			
			/**
			 * Configure le chemin vers le fichier. Si aucun chemin n'est spécifié, la valeur par défaut est empty.txt
			 * @access	public
			 * @return	void
			 * @param string $filepath : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			public function setFile($filepath){
				if($filepath == NULL) $filepath = 'empty.txt'; $this->_setFileDefault($filepath);
				if($filepath!=NULL && !is_file($filepath) && file_exists($filepath) && is_readable($filepath)) { $this->_setFileDefault($filepath); }
				
				$filepath = strval($filepath);
				if(is_file($filepath) && file_exists($filepath) && is_readable($filepath)){
					$this->_setFilePath($filepath);
					$this->_setFileName($filepath);
					$this->_setFileExt($filepath);
					$this->_setName($filepath);
					$this->_setFileInfo($filepath);
					$this->_setFileContent($filepath);
					$this->_setFileChmod($filepath);
					$this->_isExist = true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
				}
			}
			
			/**
			 * Configure le chmod du fichier
			 * @access	public
			 * @return	void
			 * @param string $chmod : contient le chmod à appliquer au fichier. La valeur par défaut est 0644
			 * @since 2.0
			*/
			
			public function setChmod($chmod =self::CHMOD644){
				chmod($this->_filePath, $chmod);
				$this->_setFileChmod($this->_filePath);
			}
			
			/**
			 * Configure le contenu du fichier
			 * @access	public
			 * @return	void
			 * @param string $content : contient le contenu du fichier
			 * @since 2.0
			*/
			
			public function setContent($content){
				file_put_contents($this->_fileContent, $content);
				$this->_setFileContent($this->_filePath);
			}
			
			/**
			 * Déplace le fichier dans un autre répertoire. Le fichier de départ sera alors supprimé
			 * @access	public
			 * @return	boolean
			 * @param string $dir : répertoire où sera déplacé le fichier
			 * @since 2.0
			*/
			
			public function moveTo($dir){
				if($this->_isExist == true){
					if(copy($this->_filePath, $dir.$this->_fileName)){
						if(unlink($this->_filePath)){
							$this->setFile($dir.$this->_fileName);
							return true;
						}
						else{
							$this->_addError('le fichier n\'a pas pu être déplcé; du répertoire original', __FILE__, __LINE__, ERROR);
							return false;
						}
					}
					else{
						$this->_addError('le fichier n\'a pas pu être déplacé', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Copie le fichier dans un autre répertoire.
			 * @access	public
			 * @return	boolean
			 * @param string $dir : répertoire où sera copié le fichier
			 * @since 2.0
			*/
			
			public function copyTo($dir){
				if($this->_isExist == true){
					if(copy($this->_filePath, $dir.$this->_fileName)){
						return true;
					}
					else{
						$this->_addError('le fichier n\'a pas pu être copié', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Copie le contenu du fichier dans un autre fichier
			 * @access	public
			 * @return	boolean
			 * @param string $file : fichier dans lequelle sera copié le contenu du fichier de départ
			 * @since 2.0
			*/
			
			public function contentTo($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					if(is_readable($file)){
						file_put_contents($file, $this->_fileContent);
					}
					else{
						$this->_addError(self::NOAREAD, __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Permet de savoir si le fichier est accessible en écriture
			 * @access	public
			 * @return	boolean
			 * @since 2.0
			*/
			
			public function isWritable() {
				if(is_writable($this->_filePath)){
					return true;
				}
				else{
					return false;
				}
			}
			
			/**
			 * Permet de savoir si le fichier est exécutable
			 * @access	public
			 * @return	boolean
			 * @since 2.0
			*/
			
			public function iseExecutable() {
				if(is_executable($this->_filePath)){
					return true;
				}
				else{
					return false;
				}
			}
			
			/**
			 * Permet de savoir si le fichier est accessible en lecture
			 * @access	public
			 * @return	boolean
			 * @since 2.0
			*/
			
			public function isReadable() {
				if(is_readable($this->_filePath)){
					return true;
				}
				else{
					return false;
				}
			}
			
			/**
			 * Configure le fichier par défaut
			 * @access	public
			 * @return	void
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			protected function _setFileDefault($file){
				$fileCreate = fopen($file, 'a');
				fclose($fileCreate);
			}
			
			/**
			 * Configure le chemin d'accès vers le fichier
			 * @access	public
			 * @return	boolean
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			protected function _setFilePath($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					$this->_filePath = $file;
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Configure le nom du fichier (avec son extension)
			 * @access	public
			 * @return	boolean
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			protected function _setFileName($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					$this->_fileName = basename($file);
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Configure l'extension du fichier
			 * @access	public
			 * @return	boolean
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			protected function _setFileExt($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					$extension = explode('.', basename($file));
					$this->_fileExt = $extension[count($extension)-1];
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * Configure le nom du fichier sans son extension)
			 * @access	public
			 * @return	boolean
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/

			protected function _setName($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					$this->_name = basename($file, '.'.$this->getFileExt());
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Configure le contenu du fichier
			 * @access	public
			 * @return	boolean
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			protected function _setFileContent($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					if(is_readable($file)){
						$this->_fileContent = file_get_contents($file);
						return true;
					}
					else{
						$this->_addError(self::NOAREAD, __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Configure les infos du fichier
			 * @access	public
			 * @return	boolean
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			protected function _setFileInfo($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					$this->_fileInfo = stat($file);
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Configure le chmod du fichier
			 * @access	public
			 * @return	boolean
			 * @param string $file : chemin d'accès vers le fichier
			 * @since 2.0
			*/
			
			protected function _setFileChmod($file){
				if(is_file($file) && file_exists($file) && is_readable($file)){
					$this->_fileChmod = substr(sprintf('%o', fileperms($file)), -4);;
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			/**
			 * Desctructeur
			 * @access	public
			 * @return	void
			 * @since 2.0
			*/
			
			public function __destruct(){
			}
		}
	}