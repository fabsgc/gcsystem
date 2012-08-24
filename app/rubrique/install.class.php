<?php
	/**
	 * @info : contrôleur créé automatiquement par le GCsystem
	*/
	
	class install extends applicationGc{
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
			//$this->showDefault();
			$install = new installGc('installtest.zip', $this->_lang);
			$install->check();
			echo $install->showError();

			function _mkmap($dir){
				$dossier = opendir ($dir);
			   	$i = 1;
				while ($fichier = readdir ($dossier)){
					if ($fichier != "." && $fichier != ".."){
						if(filetype($dir.$fichier) == 'dir'){
							_mkmap($dir.$fichier.'/');
						}
						else{
							//$dir2 = preg_replace('#asset\/image\/GCsystem\/#isU', 'IMG_PATH.GCSYSTEM_PATH.\'', $dir);
							//$dir2 = preg_replace('#asset\/image\/jquery\/#isU', 'IMG_PATH.\'jquery/', $dir2);

							$dir2 = preg_replace('#^system\/#isU', 'SYSTEM_PATH.\'', $dir);
							$dir2 = preg_replace('#^SYSTEM_PATH\.\'class\/helper\/#isU', 'SYSTEM_PATH.SYSTEM_HELPER_PATH.\'', $dir2);
							$dir2 = preg_replace('#^SYSTEM_PATH\.\'class\/system\/#isU', 'SYSTEM_PATH.SYSTEM_SYSTEM_PATH.\'', $dir2);
							$dir2 = preg_replace('#^SYSTEM_PATH\.\'lang\/#isU', 'LANG_PATH.\'', $dir2);
							$dir2 = preg_replace('#^SYSTEM_PATH\.\'log\/#isU', 'LOG_PATH.\'', $dir2);
							$dir2 = preg_replace('#^SYSTEM_PATH\.\'lib\/#isU', 'LIB_PATH.\'', $dir2);

							if($i == 4 || $i == 8 || $i == 12 || $i == 16 || $i == 20){
								echo $dir2.$fichier."',\n";
							}
							else{
								echo $dir2.$fichier."', ";
							}
						}					
					}
					$i++;
				}
				closedir ($dossier);    
			}
		}
	}