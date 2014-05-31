<?php
	/**
	 * @file : assetManager.class.php
	 * @author : fab@c++
	 * @description : class facilitant la gestion des ressources JS et CSS
	 * @version : 2.3 Bêta
	*/

	namespace system{
		class assetManager{
			use error, general;
			
			protected $_name                    ;       //concaténation de tous les fichiers
			protected $_files          = array();       //liste des fichiers
			protected $_cache                   ;       //temps de mise en cache
			protected $_time                    ;       //temps de mise en cache
			protected $_type                    ;       //js ou css
			protected $_currentPath             ;       //chemin du fichier courant
			protected $_concatenedContent       ;       //contenu concaténé, corrigé et compressé
			
			/**
			 * Crée l'instance de la classe
			 * @access	public
			 * @return	void
			 * @param array $data
			 * @since 2.0
			*/
			
			public  function __construct($data = array()){
				foreach ($data as $key => $value) {
					switch ($key) {
						case 'files':
							$this->_setFiles($value);
						break;
						
						case 'cache':
							$this->_time = abs(intval($value));
						break;

						case 'type':
							$this->_type = $value;
						break;
					}
				}
			}

			protected function _setFiles($data = array()){
				foreach ($data as $key => $value) {
					$value = preg_replace('#\\n#isU', '', $value);
					$value = preg_replace('#\\r#isU', '', $value);
					$value = preg_replace('#\\t#isU', '', $value);

					if(is_file(ASSET_PATH.trim($value))){
						if(empty($this->_data[''.$value.''])){
							$this->_setFile($value);
						}
					}
					else if(is_dir(ASSET_PATH.trim($value))){
						$this->_setDir($value);
					}
				}

				//une fois qu'on a le nom complet, on regarde si le cache est assez vieux
				$this->_cache = new \system\cache(sha1($this->_name).'.'.$this->_type, "", $this->_time);

				if($this->_cache->isDie()){
					$this->_compress();
					$this->_save();
				}
			}

			protected function _setFile($path){
				$this->_name .= $path;
				$this->_data[''.$path.''] = file_get_contents(ASSET_PATH.$path);

				$file = new \helper\file(ASSET_PATH.$path);

				if($this->_type == 'css'){
					$this->_currentPath = $file->getFolder().'/';
					$this->_data[''.$path.''] = preg_replace_callback('`url\((.*)\)`isU', array('system\assetManager', '_parseRelativePathCss'), $this->_data[''.$path.'']);
				}
			}

			protected function _parseRelativePathCss($m){
				/*
					on prend le chemin du fichier. A chaque fois qu'on a un ../ dans un fichier dans le code css, on va enlever un dossier au chemin du fichier parent
					exemple :
						j'ai le fichier css/dossier/truc/test.css
						dedans, j'ai le fichier ../../test.png
						on a 2 ../ donc on va prend le chemin du fichier et enlevé les deux derniers dossiers donc truc/dossier/ en moins
				*/

				//on enlève le / de début
				$m[1] = preg_replace("#^/#isU", '', $m[1]);
				$this->_currentPath = preg_replace("#^/#isU", '', $this->_currentPath);

				//on compte le nombre de dossiers parents à remonter
				$numberParentDir = substr_count($m[1], '../');

				//on créé la chaîne
				for ($i=0; $i < $numberParentDir; $i++) { 
					$pathReplace .= '(.[^\/]+)\/';
				}

				$pathReplace .= '$';

				//echo $this->_currentPath."\n";

				//on élimine tous les dossiers en trop
				$newCurrentPath = preg_replace('#'.$pathReplace.'#isU', '', $this->_currentPath);
				//echo $newCurrentPath."\n";

				//on nettoie encore
				$m[1] = preg_replace('#\.\./#isU', '', $m[1]);
				$m[1] = preg_replace('#"#isU', '', $m[1]);
				$m[1] = preg_replace("#'#isU", '', $m[1]);

				//on peut rétablir les chemins relatifs
				if($newCurrentPath != $this->_currentPath){
					$m[1] = $newCurrentPath.$m[1];
				}
				else if(!preg_match('#^'.preg_quote($newCurrentPath).'#isU', $m[1])){
					if(!preg_match('#^/asset#isU', $m[1]) && !preg_match('#^asset#isU', $m[1]))
						$m[1] = $newCurrentPath.$m[1];
				}

				if(!preg_match('#^/#isU', $m[1]))
					$m[1] = '/'.$m[1];

				return 'url('.$m[1].')';
			}

			protected function _parseRelativePathJs($m){

			}

			protected function _compress(){
				foreach ($this->_data as $key => $value) {
					$this->_concatenedContent .= $value;
				}

				if($this->_type == 'css'){
					$this->_concatenedContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $this->_concatenedContent);
					$this->_concatenedContent = str_replace(': ', ':', $this->_concatenedContent);
					$this->_concatenedContent = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $this->_concatenedContent);
				}
			}

			protected function _save(){
				$this->_cache->setVal($this->_concatenedContent);
				$this->_cache->setCache();
			}

			public function getId(){
				return sha1($this->_name);
			}

			public function getType(){
				return $this->_type;
			}

			/**
			 * Desctructeur
			 * @access	public
			 * @return	boolean
			 * @since 2.0
			*/
			
			public  function __destruct(){
			
			}
		}
	}