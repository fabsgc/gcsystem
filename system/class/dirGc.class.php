<?php
	/**
	 * @dir : dirGc.class.php
	 * @author : fab@c++
	 * @description : class gèrant les opérations sur les fichiers
	 * @version : 2.0 bêta
	*/
	
    class dirGc{
		use errorGc;                            //trait
		
		protected $_dirPath                                    ;
		protected $_dirName                                    ;
		protected $_dirChmod                                   ;
		protected $_dirArbo                           = array();
		protected $_info                              = array();
		protected $_isExist                           = false  ;
		
		const NODIR   = 'Aucun répertoire n\'a été difini'     ;
		const NOACCESS = 'le répertoire n\'est pas accessible' ;
		const NOREAD   = 'le répertoire n\'est pas lisible'    ;
		
		const CHMOD0644                                = 0644  ;
		const CHMOD0755                                = 0755  ;
		const CHMOD0777                                = 0777  ;
		const CHMOD0004                                = 0004  ;
		
		public function __construct($dirpath){
			if($dirpath == NULL) { $dirpath = 'empty'; $this->_setDirDefault($dirpath); }
			if($dirpath!=NULL && !is_dir($dirpath)) { $this->_setDirDefault($dirpath); }
			
			if(is_dir($dirpath)){
				$this->setdir($dirpath);
			}
			else{
				$this->_addError(self::NOACCESS);
			}
		}
		
		public function getDirPath(){
			return $this->_dirPath;
		}
				
		public function getDirName(){
			return $this->_dirName;
		}
		
		public function getDirInfo(){
			return $this->_dirInfo;
		}
		
		public function getDirChmod(){
			return $this->_dirChmod;
		}
		
		public function getDirArbo(){
			return $this->_dirArbo;
		}
		
		public function getSize($repertoire=""){
			if($repertoire == "") { $repertoire = $this->_dirPath; }
			if($this->_isExist == true){
				$racine = opendir($repertoire);
				$poids = 0;
				while($dossier = readdir($racine)){
					if($dossier != '..' && $dossier != '.') {
						if(is_dir($repertoire.'/'.$dossier)) {
							$poids += $this->getSize($repertoire.'/'.$dossier);
						} 
						else {
							$poids += filesize($repertoire.'/'.$dossier);
						}
					}
				}
				closedir($racine);
				return $poids;
			}
			else{
				$this->_addError(self::NODIR);
				return false;
			}
		}
		
		public function getExist(){
			return $this->_isExist;
		}
		
		public function getLastAccess(){
			if($this->_isExist == true){
				return $this->_dirInfo['atime'];
			}
			else{
				$this->_addError(self::NODIR);
				return false;
			}
		}
		
		public function getLastUpdate(){
			if($this->_isExist == true){
				return $this->_dirInfo['ctime'];
			}
			else{
				$this->_addError(self::NODIR);
				return false;
			}
		}
		
		public function setChmod($chmod = self::CHMOD644){
			chmod($this->_dirPath, $chmod);
			$this->_setDirChmod($this->_dirPath);
		}
		
		public function moveTo($dir, $src=""){
			if($src == "") { $src = $this->_dirPath; }
			if(is_dir($src)){
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
								$this->_addError('le fichier '.$fichier.' n\'a pas pu être copié');
							}
						} 
					} 
				}
				$this->setDir($dest);
				closedir ($dossier); 
				return true;				
			}
			else{
				$this->_isExist = false;
				$this->_addError(self::NODIR);
				return false;
			}
		}
		
		public function copyTo($dir, $src=""){
			if($src == "") { $src = $this->_dirPath; }
			if($this->_isExist == true){
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
								$this->_addError('le fichier '.$fichier.' n\'a pas pu être copié');
							}
						} 
					} 
				}
				closedir ($dossier);
				return true;
			}
			else{
				$this->_addError(self::NODIR);
				return false;
			}
		}
		
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
				$this->_addError(self::NODIR);
				return false;
			}
		}
		
		public function setDir($dirpath){
			if($dirpath == NULL) $dirpath = 'empty'; $this->_setDirDefault($dirpath);
			if($dirpath!=NULL && !is_dir($dirpath)) { $this->_setDirDefault($dirpath); }
			
			$dirpath = strval($dirpath);
			if(is_dir($dirpath)){
				$this->_setdirPath($dirpath);
				$this->_setdirName($dirpath);
				$this->_setdirInfo($dirpath);
				$this->_setdirChmod($dirpath);
				$this->_setdirArbo($dirpath);
				$this->_isExist = true;
			}
			else{
				$this->_addError(self::NOACCESS);
			}
		}

		protected function _setDirDefault($dir){
			mkdir($dir);
		}
		
		protected function _setDirPath($dir){
			if(is_dir($dir)){
				$this->_dirPath = $dir;
				return true;
			}
			else{
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
		
		protected function _setdirName($dir){
			if(is_dir($dir)){
				$this->_dirName = basename($dir);
				return true;
			}
			else{
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
		protected function _setdirInfo($dir){
			if(is_dir($dir)){
				$this->_dirInfo = stat($dir);
				return true;
			}
			else{
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
		protected function _setdirChmod($dir){
			if(is_dir($dir)){
				$this->_dirChmod = substr(sprintf('%o', fileperms($dir)), -4);;
				return true;
			}
			else{
				$this->_addError(self::NOACCESS);
				return false;
			}
		}
		
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

		public function __destruct(){
		}
	}