<?php
	/**
	 * @dir : dir.class.php
	 * @author : fab@c++
	 * @description : class gèrant les opérations sur les fichiers
	 * @version : 2.3 Bêta
	*/
	
	namespace helper{
	    class dir{
			use \system\error;
			
			protected $_dirPath                                    ;
			protected $_dirName                                    ;
			protected $_dirChmod                                   ;
			protected $_dirArbo                           = array();
			protected $_dirArboContent                    = array();
			protected $_info                              = array();
			protected $_isExist                           = false  ;
			
			const NODIR    = 'Aucun répertoire n\'a été difini'    ;
			const NOACCESS = 'le répertoire n\'est pas accessible' ;
			const NOREAD   = 'le répertoire n\'est pas lisible'    ;
			
			const CHMOD0644                                = 0644  ;
			const CHMOD0755                                = 0755  ;
			const CHMOD0777                                = 0777  ;
			const CHMOD0004                                = 0004  ;
			
			/**
			 * Crée l'instance de la classe
			 * @param string $dirpath : chemin vers le répertoire
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public function __construct($dirpath){			
				if(is_dir($dirpath) && file_exists($dirpath)){
					$this->setdir($dirpath);
				}
				else{
					$this->_isExist = false;
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
				}
			}

			/**
			 * retourne le chemin vers le répertoire
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getDirPath(){
				return $this->_dirPath;
			}

			/**
			 * retourne le nom du répertoire
			 * @access public
			 * @return string
			 * @since 2.0
			*/
					
			public function getDirName(){
				return $this->_dirName;
			}

			/**
			 * retourne des informations sur le répertoire sous la forme d'un array. voir ici : http://php.net/manual/fr/function.stat.php
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getDirInfo(){
				return $this->_dirInfo;
			}

			/**
			 * retourne le chmod du dossier. attention, le chmod n'existe pas sous windows
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getDirChmod(){
				return $this->_dirChmod;
			}

			/**
			 * retourne un array contenant l'arborescence interne du répertoire (seulement les fichiers)
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getDirArbo(){
				return $this->_dirArbo;
			}

			/**
			 * retourne un array contenant l'arborescence interne du répertoire. les clés sont les noms des fichiers; les valeurs, le contenu (seulement pour les fichiers)
			 * @access public
			 * @return string
			 * @since 2.0
			*/

			public function getDirArboContent(){
				return $this->_dirArboContent;
			}

			/**
			 * retourne le poids total d'un répertoire
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getSize($repertoire=""){
				if($repertoire == "") { $repertoire = $this->_dirPath; }
				if($this->_isExist == true){
					$racine = opendir($repertoire);
					$poids = 0;
					while($dossier = readdir($racine)){
						if($dossier != '..' && $dossier != '.') {
							if(is_dir($repertoire.'/'.$dossier)) {
								$poids .= $this->getSize($repertoire.'/'.$dossier);
							} 
							else {
								$poids .= filesize($repertoire.'/'.$dossier);
							}
						}
					}
					closedir($racine);
					return $poids;
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * permet de savoir si le répertoire existe au moment de l'instanciation de la classe
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getExist(){
				return $this->_isExist;
			}

			/**
			 * retour la date au format unix timestamp du dernier accès à ce répertoire
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getLastAccess(){
				if($this->_isExist == true){
					return $this->_dirInfo['atime'];
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * retour la date au format unix timestamp de la dernière modification du répertoire
			 * @access public
			 * @return string
			 * @since 2.0
			*/
			
			public function getLastUpdate(){
				if($this->_isExist == true){
					return $this->_dirInfo['ctime'];
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * permet de changer le chmod du répertoire (ne fonctionne pas sous windows)
			 * @access public
			 * @param int $chmod : valeur du chmod sur 4 chiffres
			 * @return void
			 * @since 2.0
			*/
			
			public function setChmod($chmod = self::CHMOD644){
				if($this->_isExist == true){
					chmod($this->_dirPath, $chmod);
					$this->_setDirChmod($this->_dirPath);
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * permet de déplacer un répertoire
			 * @access public
			 * @param string $dir : répertoire de destination
			 * @param string $src : répertoire source. si aucune valeur n'est données, on prend le répertoire défini lors de l'instanciation
			 * @return string
			 * @since 2.0
			*/
			
			public function moveTo($dir, $src=""){
				if($src == "") { $src = $this->_dirPath; }

				if($this->_isExist == true || (file_exists($src) && is_dir($src))){
					$dossier = opendir ($src);
					while ($fichier = readdir ($dossier)) {
						if ($fichier != "." && $fichier != "..") {          
							if(is_dir($src.$fichier)){ 
								$this->moveTo($dir.$fichier, $src.$fichier); 
							} 
							else{
								mkdir($dir.'/');
								if(rename($src.'/'.$fichier, $dir.'/'.$fichier)){
								}
								else{
									$this->_addError('le fichier '.$fichier.' n\'a pas pu être copié', __FILE__, __LINE__, ERROR);
								}
							} 
						} 
					}
					$this->setDir($src);
					closedir ($dossier); 
					return true;				
				}
				else{
					$this->_isExist = false;
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * permet de copier un répertoire ailleurs
			 * @access public
			 * @param string $dir : répertoire de destination
			 * @param string $src : répertoire source. si aucune valeur n'est données, on prend le répertoire défini lors de l'instanciation
			 * @return string
			 * @since 2.0
			*/
			
			public function copyTo($dir, $src=""){
				if($src == "") { $src = $this->_dirPath; }

				if($this->_isExist == true || (file_exists($src) && is_dir($src))){
					$dossier = opendir ($src);
					while ($fichier = readdir ($dossier)) {
						if ($fichier != "." && $fichier != "..") {          
							if(is_dir($src.$fichier)){ 
								$this->copyTo($dir.$fichier, $src.$fichier); 
							} 
							else{
								mkdir($dir.'/');
								if(copy($src.'/'.$fichier, $dir.'/'.$fichier)){
								}
								else{
									$this->_addError('le fichier '.$fichier.' n\'a pas pu être copié', __FILE__, __LINE__, ERROR);
								}
							} 
						} 
					}
					closedir ($dossier);
					return true;
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * permet de supprimer un répertoire
			 * @access public
			 * @param string $dir : répertoire à supprimer. Si aucune valeur n'est données, on prend le répertoire défini lors de l'instanciation
			 * @return string
			 * @since 2.0
			*/
			
			public function delete($dir=""){
				$this->_isExist = false;
				
				if($dir == "") { $dir = $this->_dirPath; }

				if (is_dir($dir)){
					echo $dir;
					$objects = scandir($dir);
						foreach ($objects as $object) {
							if ($object != "." && $object != "..") {
								if (filetype($dir."/".$object) == "dir") $this->delete($dir."/".$object); else unlink($dir."/".$object);
							}
						}
					reset($objects);
					rmdir($dir);
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * permet de définir un peu tout
			 * @access public
			 * @param string $dir : répertoire de travail
			 * @return string
			 * @since 2.0
			*/
			
			public function setDir($dirpath){
				$dirpath = trim(strval($dirpath));

				if(is_dir($dirpath)){
					$this->_setdirPath($dirpath);
					$this->_setdirName($dirpath);
					$this->_setdirInfo($dirpath);
					$this->_setdirChmod($dirpath);
					$this->_setdirArbo($dirpath);
					$this->_setdirArboContent($dirpath);
					$this->_isExist = true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
				}
			}

			/**
			 * configure variable
			 * @access protected
			 * @param string $dir : répertoire de travail
			 * @return string
			 * @since 2.0
			*/
			
			protected function _setDirPath($dir){
				if(is_dir($dir)){
					$this->_dirPath = $dir;
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * configure variable
			 * @access protected
			 * @param string $dir : répertoire de travail
			 * @return string
			 * @since 2.0
			*/
			
			protected function _setdirName($dir){
				if(is_dir($dir)){
					$this->_dirName = basename($dir);
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * configure variable
			 * @access protected
			 * @param string $dir : répertoire de travail
			 * @return string
			 * @since 2.0
			*/
			
			protected function _setdirInfo($dir){
				if(is_dir($dir)){
					$this->_dirInfo = stat($dir);
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * configure variable
			 * @access protected
			 * @param string $dir : répertoire de travail
			 * @return string
			 * @since 2.0
			*/
			
			protected function _setdirChmod($dir){
				if(is_dir($dir)){
					$this->_dirChmod = substr(sprintf('%o', fileperms($dir)), -4);;
					return true;
				}
				else{
					$this->_addError(self::NOACCESS, __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * configure variable
			 * @access protected
			 * @param string $dir : répertoire de travail
			 * @return string
			 * @since 2.0
			*/
			
			protected function _setDirArbo($dir){
				$dossier = opendir ($dir);
			   
				while ($fichier = readdir ($dossier)) {   
					if ($fichier != "." && $fichier != "..") {           
						if(filetype($dir.$fichier) == 'dir'){          
							$this->_setDirArbo($dir.$fichier.'/');               
						}
						else{
							array_push($this->_dirArbo, $dir.$fichier);
						}					
					}       
				}
				closedir ($dossier); 			
			}

			/**
			 * configure variable
			 * @access protected
			 * @param string $dir : répertoire de travail
			 * @return string
			 * @since 2.0
			*/

			protected function _setDirArboContent($dir){
				$dossier = opendir ($dir);
			   
				while ($fichier = readdir ($dossier)) {   
					if ($fichier != "." && $fichier != "..") {           
						if(filetype($dir.$fichier) == 'dir'){          
							$this->_setDirArboContent($dir.$fichier.'/');               
						}
						else{
							$this->_dirArboContent[$dir.$fichier] = file_get_contents($dir.$fichier);
						}					
					}       
				}
				closedir ($dossier); 			
			}

			/**
			 * desctructeur
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public function __destruct(){
			}
		}
	}