<?php
	/**
	 * @file : upload.class.php
	 * @author : fab@c++
	 * @description : class gérant les uploads
	 * @version : 2.3 Bêta
	*/
	
	namespace helper{
		class upload{
			use \system\error;
			
			protected $_name                         ; //contient le nom du formulaire
			protected $_type                         ; //contient le type du fichier
			protected $_tmpName                      ; //contient l\'adresse temporaire
			protected $_size                         ; //contient le poids du fichier
			protected $_extension                    ; //contient l\'extension du fichier
			protected $_url                          ; //adresse du fichier une fois enregistré sur le serveur
			protected $_validate            = false  ; //savoir si aucun problème n\'a été décelé à l\'envoi
			protected $_checked             = array(); //savoir si la méthode checked a réussi (tableau de true/false)
			protected $_checkedAnswer       = array(); //ce tableau contient la liste des erreurs détectés mais sous forme de chaine de caractères
			protected $_i                   = 0      ; //utile pour la fonction checkFile
			
			const NOFILE       = 'Une erreur est survenue lors de l\'envoie du fichier'  ;
			const NOCONSTRAINT = 'Cette contrainte n\'existe pas'                        ;
			const NOPARAM      = 'Le paramètre entré est incorrect'                      ;
			const NODIR        = 'Ce répertoire n\'existe pas'                           ;
			
			public  function __construct($name){
				$this->_setUpload(strval($name));
			}
			
			public function checkFile($contrainte = array(), $error = array()){
				$this->_i = 0;
				if($this->_validate == true){
					foreach ($contrainte as $cle => $valeur){
						switch($cle){
							case 'minsize':
								if(is_int($valeur)){
									if($this->_size>=$valeur){
										array_push($this->_checked, true);
									}
									else{
										array_push($this->_checked, false);
										array_push($this->_checkedAnswer, $error[$this->_i]);
									}
								}
								else{
									$this->_addError(self::NOPARAM, __FILE__, __LINE__, ERROR);
									array_push($this->_checked, false);
									array_push($this->_checkedAnswer, self::NOPARAM);
								}
							break;
							
							case 'maxsize':
								if(is_int($valeur)){
									if($this->_size<=$valeur){
										array_push($this->_checked, true);
									}
									else{
										array_push($this->_checked, false);
										array_push($this->_checkedAnswer, $error[$this->_i]);
									}
								}
								else{
									$this->_addError(self::NOPARAM, __FILE__, __LINE__, ERROR);
									array_push($this->_checked, false);
									array_push($this->_checkedAnswer, self::NOPARAM);
								}
							break;
							
							case 'equalsize':
								if(is_int($valeur)){
									if($this->_size==$valeur){
										array_push($this->_checked, true);
									}
									else{
										array_push($this->_checked, false);
										array_push($this->_checkedAnswer, $error[$this->_i]);
									}
								}
								else{
									$this->_addError(self::NOPARAM, __FILE__, __LINE__, ERROR);
									array_push($this->_checked, false);
									array_push($this->_checkedAnswer, self::NOPARAM);
								}
							break;
							
							case 'differentsize':
								if(is_int($valeur)){
									if($this->_size!=$valeur){
										array_push($this->_checked, true);
									}
									else{
										array_push($this->_checked, false);
										array_push($this->_checkedAnswer, $error[$this->_i]);
									}
								}
								else{
									$this->_addError(self::NOPARAM, __FILE__, __LINE__, ERROR);
									array_push($this->_checked, false);
									array_push($this->_checkedAnswer, self::NOPARAM);
								}
							break;
							
							case 'extension':
								if(is_array($valeur)){
									if (in_array($this->_extension, $valeur)){
										array_push($this->_checked, true);
									}
									else{
										array_push($this->_checked, false);
										array_push($this->_checkedAnswer, $error[$this->_i]);
									}
								}
								else{
									$this->_addError(self::NOPARAM, __FILE__, __LINE__, ERROR);
									array_push($this->_checked, false);
									array_push($this->_checkedAnswer, self::NOPARAM);
								}
							break;
							
							case 'file_accept':
								if(is_array($valeur)){
									if(in_array($this->_typen, $valeur)){
										array_push($this->_checked, true);
									}
									else{
										array_push($this->_checked, false);
										array_push($this->_checkedAnswer, $error[$this->_i]);
									}
								}
								else{
									$this->_addError(self::NOPARAM, __FILE__, __LINE__, ERROR);
									array_push($this->_checked, false);
									array_push($this->_checkedAnswer, self::NOPARAM);
								}
							break;
							
							default:
								$this->_addError(self::NOCONSTRAINT, __FILE__, __LINE__, ERROR);
							break;
						}
						$this->_i++;
					}
				}
				else{
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
					return false;
				}
				
				if(in_array(false, $this->_checked)){
					return false;
				}
				else{
					return true;
				}
			}
			
			public function move($dir, $name = "", $extension = ""){
				if(is_dir($dir)){
					if($this->_validate == true){
						if($name!="" && strval($name)){
							if($extension != "" && strval($extension)){
								$this->_url = $dir.$name.'.'.$extension;
								move_uploaded_file($this->_tmpName, $this->_url);
								return $this->_url;
							}
							else{
								$this->_url = $dir.$name.'.'.$this->_extension;
								move_uploaded_file($this->_tmpName, $this->_url);
								return $this->_url;
							}	
						}
						else{
							$this->_url = $dir.$this->correctName($this->_name).'.'.$this->_extension;
							move_uploaded_file($this->_tmpName, $this->_url);
							return $this->_url;
						}
					}
					else{
						$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError(self::NODIR, __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			
			public function setUpload($name){
				$this->_checked       = array();
				$this->_checkedAnswer = array();
				$this->_i             = 0      ;
				$this->_validate      = false  ;
				$this->_setUpload(strval($name));
			}
			
			public function correctName($name){
				$search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_]@');
				$replace = array ('e','a','i','u','o','c','_','');
				return uniqid().preg_replace($search, $replace, $name);
			}
			
			public function getName(){
				return $this->_name;
			}
			
			public function getType(){
				return $this->_type;
			}
			
			public function getTmp(){
				return $this->_tmpName;
			}
			
			public function getSize(){
				return $this->_size;
			}
			
			public function getExtension(){
				return $this->_extension;
			}
			
			public function getUrl(){
				return $this->_url;
			}
			
			public function getError(){
				return $this->_checkedAnswer;
			}
			
			public function getErrorHtml(){
				foreach($this->_checkedAnswer as $valeur){
					$retour .= $valeur.'<br />';
				}
				
				return $retour;
			}
			
			protected function _setUpload($name){
				if($_FILES[$name] && $_FILES[$name]['error']==0){
					$this->_setName($name);
					$this->_setType($name);
					$this->_setTmp($name);
					$this->_setError($name);
					$this->_setSize($name);
					$this->_setExtension($name);
					$this->_validate = true;
				}
				else{
					$this->_validate = false;
					$this->_addError(self::NOFILE, __FILE__, __LINE__, ERROR);
				}
			}
			
			protected function _setName($name){
				$this->_name = $name;
			}
			
			protected function _setType($name){
				$this->_type = $_FILES[$name]['type'];
			}
			
			protected function _setTmp($name){
				$this->_tmpName = $_FILES[$name]['tmp_name'];
			}
			
			protected function _setError($name){
				$this->_erreur = $_FILES[$name]['error'];
			}
			
			protected function _setSize($name){	
				$this->_size = $_FILES[$name]['size'];
			}
			
			protected function _setExtension($name){	
				$extension = pathinfo($_FILES[$name]['name']);
				$this->_extension = strtolower($extension['extension']);
			}
			
			public  function __destruct(){
			}
		}
	}