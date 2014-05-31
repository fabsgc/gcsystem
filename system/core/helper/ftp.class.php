<?php
	/**
	 * @dir : ftp.class.php
	 * @author : fab@c++
	 * @description : class gèrant les connexion ftp, elle permet de gérer plusieurs connexion dans la même instance
	 * @version : 2.3 Bêta
	*/
	
	namespace helper{
	    class ftp{
			use \system\error;
			
			protected $_connexionId  = array();
			protected $_connected    = array();
			protected $_connexion    = array();
			
			/**
			 * Crée l'instance de la classe
			 * @param string $dirpath : chemin vers le répertoire
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public function __construct(){
			}

			/**
			 * ajoute une nouvelle connexion ftp à l'instance de la classe. ne se connecte pas forcément, la connexion se fait dans chaque fonction pour permettre une multi 
			 * connexion. pour ne pas perdre en performance, si on a déjà la bonne connexion, on ne réfait pas la procédure
			 * @access public
			 * @param array $ftp : le nom de la connexion ftp
			 * @param array $connect : les identifiants pour la connexion :
			 * host, port, timeout, username, password
			 * @return string
			 * @since 2.0
			*/
			
			public function AddFtp($ftp, $connect = array()){
				$this->_connexionId[''.$ftp.''] = array(
					'host' => 'empty',
					'port' => 21,
					'timeout' => 90,
					'username' => 'empty', 
					'password' => 'empty'
				);

				foreach ($connect as $key => $value) {
					switch($key){
						case 'host':
							$this->_connexionId[''.$ftp.''][$key] = $value;
						break;

						case 'port':
							$this->_connexionId[''.$ftp.''][$key] = $value;
						break;

						case 'timeout':
							$this->_connexionId[''.$ftp.''][$key] = $value;
						break;

						case 'username':
							$this->_connexionId[''.$ftp.''][$key] = $value;
						break;

						case 'password':
							$this->_connexionId[''.$ftp.''][$key] = $value;
						break;
					}
				}

				$this->_connected[''.$ftp.''] = false;

				return true;
			}

			/**
			 * supprime la connexion ftp voulue de l'instance
			 * @access public
			 * @param array $ftp : le nom de la connexion ftp
			 * @param array $connect : les identifiants pour la connexion
			 * @return string
			 * @since 2.0
			*/

			public function closeFtp($ftp){
				if(isset($this->_connexionId[''.$ftp.'']) && $this->_connected[''.$ftp.''] == true){
					ftp_close($this->_connexion);

					unset($this->_connexionId[''.$ftp.'']);
					unset($this->_connected[''.$ftp.'']);

					return true;
				}
				elseif(isset($this->_connexionId[''.$ftp.'']) && $this->_connected[''.$ftp.''] == false){
					unset($this->_connexionId[''.$ftp.'']);
					unset($this->_connected[''.$ftp.'']);

					return true;
				}
				else{
					$this->_addError('La connexion ftp de nom '.$ftp.' que vous voulez supprimer n\'existe pas', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * envoie un fichier sur le serveur
			 * @access public
			 * @param string $ftp : le nom de la connexion ftp
			 * @param string $file : le chemin (relatif) vers le fichier à envoyer avec un / à la fin
			 * @param string $to : le répertoire où envoyer le fichier
			 * @param string $nom : nom du fichier sur le serveur
			 * @return bool
			 * @since 2.0
			*/

			public function putFileToFtp($ftp, $file, $to, $nom = ''){
				if($this->_connectFtp($ftp) == true){
					if(file_exists($file) && is_readable($file)){
						$file = new file($file);

						if($nom != ''){
							if(ftp_put($this->_connexion, $to.$nom, $file, FTP_BINARY) == true){
							}
							else{
								$this->_addError('La copie du fichier "'.$file->getFileName().'" a échoué', __FILE__, __LINE__, ERROR);
								return false;
							}
						}
						else{
							if(ftp_put($this->_connexion, $to.$file->getFileName(), $file, FTP_BINARY) == true){
							}
							else{
								$this->_addError('La copie du fichier "'.$file->getFileName().'" a échoué', __FILE__, __LINE__, ERROR);
								return false;
							}
						}
					}
					else{
						$this->_addError('Le fichier ('.$file.') que vous voulez copier sur le serveur ftp n\'existe pas ou n\'est pas accessible', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError('La connexion ftp de nom '.$ftp.' a échoué, le fichier n\'a pas pu être envoyé', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * envoie un fichier créé à partir d'une chaîne de caractère sur le serveur
			 * @access public
			 * @param string $ftp : le nom de la connexion ftp
			 * @param string $nom : nom du fichier
			 * @param string $content : contenu du fichier
			 * @param string $to : le répertoire où envoyer le fichier
			 * @return bool
			 * @since 2.0
			*/

			public function putStringToFtp($ftp, $nom, $content, $to){
				if($this->_connectFtp($ftp) == true){
					file_put_contents(CACHE_PATH.'_temp_ftp_'.$nom.'.cache', $content);

					if(ftp_put($this->_connexion, $to.$nom, CACHE_PATH.'_temp_ftp_'.$nom.'.cache', FTP_BINARY) == true){
						unlink(CACHE_PATH.'_temp_ftp_'.$nom.'.cache');
					}
					else{
						$this->_addError('La copie du fichier "'.$nom.'" a échoué', __FILE__, __LINE__, ERROR);
						unlink(CACHE_PATH.'_temp_ftp_'.$nom.'.cache');
						return false;
					}
				}
				else{
					$this->_addError('La connexion ftp de nom '.$ftp.' a échoué, le fichier n\'a pas pu être envoyé', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * récupère le contenu d'un fichier présent sur le serveur
			 * @access public
			 * @param string $ftp : le nom de la connexion ftp
			 * @param string $file : chemin vers le fichier à récupérer
			 * @return string
			 * @since 2.0
			*/

			public function getFileFromFtp($ftp, $file){
				if($this->_connectFtp($ftp) == true){
					if(ftp_nb_get ($this->_connexion, CACHE_PATH.'_temp_ftp.cache' , $file, FTP_BINARY, -1) == true){
						return file_get_contents(CACHE_PATH.'_temp_ftp.cache');
					}
					else{
						$this->_addError('Le fichier n\'a pas pu être téléchargé sur le serveur distant', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError('La connexion ftp de nom '.$ftp.' a échoué, le fichier n\'a pas pu être envoyé', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * récupère la liste des fichiers du répertoire ainsi que des sous répertoires, sans retourner le répertoire
			 * @access public
			 * @param string $ftp : le nom de la connexion ftp
			 * @param string $dir : répertoire de base
			 * @return string
			 * @since 2.0
			*/

			public function getFilesFromFtp($ftp, $dir){

			}

			/**
			 * renvoie l'objet ftp_stream demandé
			 * @access public
			 * @param string $ftp : le nom de la connexion ftp
			 * @return ftp_stream
			 * @since 2.0
			*/

			public function getFtpStream($ftp){
				if($this->_connectFtp($ftp) == true){
					return $this->_connexion;
				}
				else{
					$this->_addError('La connexion ftp de nom '.$ftp.' n\'a pas pu être retournée', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * supprime la connexion ftp voulue
			 * @access public
			 * @param array $ftp : le nom de la connexion ftp
			 * @param array $connect : les identifiants pour la connexion
			 * @return string
			 * @since 2.0
			*/

			protected function _connectFtp($ftp){
				if(isset($this->_connexionId[''.$ftp.'']) && $this->_connected[''.$ftp.''] == true){
					return true; //on a déjà la bonne connexion
				}
				elseif(isset($this->_connexionId[''.$ftp.'']) && $this->_connected[''.$ftp.''] == false){ //on n'est pas encore connecté
					$this->_connexion = ftp_connect(
						$this->_connexionId[''.$ftp.'']['host'], 
						$this->_connexionId[''.$ftp.'']['port'],
						$this->_connexionId[''.$ftp.'']['timeout']
					);

					if($this->_connexion == true){
						if(ftp_login($this->_connexion, $this->_connexionId[''.$ftp.'']['username'], $this->_connexionId[''.$ftp.'']['password']) == true){
							$this->_connected[''.$ftp.''] = true;
							return true;
						}
						else{
							$this->_addError('La connexion ftp de nom '.$ftp.' a échoué avec : username : '.$this->_connexionId[''.$ftp.'']['username'].', password : '.$this->_connexionId[''.$ftp.'']['password'], __FILE__, __LINE__, ERROR);
							return false;
						}
					}
					else{
						$this->_addError('La connexion ftp de nom '.$ftp.' a échoué avec : hôte : '.$this->_connexionId[''.$ftp.'']['host'].', port : '.$this->_connexionId[''.$ftp.'']['port'], __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError('La connexion ftp de nom '.$ftp.' à laquelle vous voulez vous connecter n\'existe pas', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * desctructeur
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public function __destruct(){
				ftp_close($this->_connexion);
			}
		}
	}