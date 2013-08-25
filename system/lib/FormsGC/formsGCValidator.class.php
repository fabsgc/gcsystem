<?php
	class formsGCValidator {
		
		/* -------- FORMULAIRE -------- */
		
		private $field_array=array();
		private $error=array();
		private $method=array();
		private $bdd=array();
		
		private $valid=array();
		private $content;
		private $nameForm;            //permet d'identifier un formulaire et couplé au champ hidden de formsgc de savoir si le form a été envoyé ou non
		private $token;               //permettra d'obliger la personne a avoir un champs token dans ce formulaire
		private $tokenName;
		private $tokenError;
		
		private $i=0;
		
		/* ---------- CONSTRUCTEUR --------- */
		
		/**
		 * création d'un validateur de formulaire
		 * @access public
		 * @param string $method : méthode d'envoi du formulaire
		 * @param PDO $bdd : connexion à une base de données dans le cas où vous utilisez par la suite des requêtes SQL comme contraintes
		 * @param string $nameForm : nom du formulaire
		 * @return void
		 * @since 2.0
		*/

		public  function __construct($method="GET", $bdd="", $nameForm=""){
			$this->method=$method;
			$this->bdd=$bdd;
			$this->nameForm = $nameForm;
		}

		/**
		 * activation du token anti CRSF
		 * @access public
		 * @param string $name : nom du champ input hidden qui sera créé
		 * @param string $error : erreur qui sera affichée dans le cas où le token CRSF est incorrect
		 * @return void
		 * @since 2.0
		*/

		public function setToken($name, $error = ""){
			$this->token = true;
			$this->tokenName = $name;
			$this->tokenError = $error;
		}

		/**
		 * ajout de contraintes sur un champ
		 * @access public
		 * @param string $type : type du champ : input, radio, checkbox, textarea, file, listebox, listebox_date, listebox_time
		 * @param string $name : nom du champ
		 * @param string $name_content : nom qui sera affiché
		 * @param array $contraint : liste des contraintes
		 * @param array $error : liste des erreurs à afficher
		 * @return void
		 * @since 2.0
		*/
		
		public  function addfield($type="", $name="", $name_content="", $contraint=array(), $error=array()){
			if($type=="textarea"){
				$type="input";
			}
			if($this->method=='post' && $type!="file"){
				if(isset($_POST[$name])){
					$field = new formsGCField($type, $name, $name_content, $contraint, $error);
					array_push($this->field_array, $field);
				}
				else{
					if($type!="checkbox" && $type!="listebox_date" && $type!="listebox_time"){
						array_push($this->error, $name_content.' : le champs n\'existe pas');
						array_push($this->valid, 'false');
					}
					else{
						if($type=="checkbox"){
							//cas particulier des checkbox
							foreach($contraint as $contraint_checkbox => $valeur){
								switch($contraint_checkbox){
									case 'isset':
										array_push($this->error, $name_content.' : Vous devez cocher cette case');
										array_push($this->valid, 'false');
									break;
									
									case 'required':
										if($contraint_valeur=='true'){
											if(isset($_POST[$value[1]])){
												array_push($this->valid, 'true');
											}
											else{
												array_push($this->valid, 'false');
												array_push($this->error, $value[2].' : '.$value[4][$this->i]);
											}
										}
									break;
									
									default:
									break;
								}
							}
						}
						elseif($type=="listebox_date"){
							$field = new formsGCField($type, $name, $name_content, $contraint, $error);
							array_push($this->field_array, $field);
						}
						elseif($type=="listebox_time"){
							$field = new formsGCField($type, $name, $name_content, $contraint, $error);
							array_push($this->field_array, $field);
						}
					}
				}
			}
			elseif($this->method=='post' AND $type=="file"){
				if(isset($_FILES[$name])){
					$field = new formsGCField($type, $name, $name_content, $contraint, $error);
					array_push($this->field_array, $field);
				}
				else{
					array_push($this->error, $name_content.' : le champs n\'existe pas');
					array_push($this->valid, 'false');
				}
			}
			elseif($this->method=='get' AND $type!='file'){
				if(isset($_POST[$name])){
					if($type!="checkbox" && $type!="listebox_date" && $type!="listebox_time"){
						$field = new formsGCField($type, $name, $name_content, $contraint, $error);
						array_push($this->field_array, $field);
					}
					else{
						if(isset($_POST[$name])){
							$field = new formsGCField($type, $name, $name_content, $contraint, $error);
							array_push($this->field_array, $field);
						}
						else{
							if($type=="checkbox"){
								//cas particulier des checkbox
								foreach($contraint as $contraint_checkbox => $valeur){
									switch($contraint_checkbox){
										case 'isset':
											array_push($this->error, $name_content.' : Vous devez cocher cette case');
											array_push($this->valid, 'false');
										break;
										
										case 'required':
											if($contraint_valeur=='true'){
												if(isset($_POST[$value[1]])){
													array_push($this->valid, 'true');
												}
												else{
													array_push($this->valid, 'false');
													array_push($this->error, $value[2].' : '.$value[4][$this->i]);
												}
											}
										break;
										
										default:
										break;
									}
								}
							}
							elseif($type=="listebox_date"){
								$field = new formsGCField($type, $name, $name_content, $contraint, $error);
								array_push($this->field_array, $field);
							}
							elseif($type=="listebox_time"){
								$field = new formsGCField($type, $name, $name_content, $contraint, $error);
								array_push($this->field_array, $field);
							}
						}
					}
				}
				else{
					array_push($this->error, $name_content.' : le champs n\'existe pas');
					array_push($this->valid, 'false');
				}
			}
			else{
				array_push($this->error, $name_content.' : Ce champs n\'est pas compatible avec l\'upload');
				array_push($this->valid, 'false');
			}
		}
		
		/**
		 * teste si le formulaire a été envoyé ou non
		 * @access public
		 * @return bool
		 * @since 2.0
		*/

		public  function sentForm(){
			if($this->method=='get'){
				if(count($_GET)>0){
					if(isset($_GET[$this->nameForm]))
						return true;
					else
						return false;
				}
				else{
					return false;
				}
			}
			elseif($this->method=='post'){
				if(count($_POST)>0){
					if(isset($_POST[$this->nameForm]))
						return true;
					else
						return false;
				}
				else{
					return false;
				}
			}
		}
		
		private function validateField($name){
			$names=explode(',', $name);
			foreach($names as $name){
				foreach($this->field_array as $field){
					$value=$field->return_array(); //$field contient un objet, on retourne tout ça sous forme de tableau
					if($value[1]==$name){
						$this->validateFunction($value);
					}
				}
			}
		}
		
		private  function validateFields(){
			foreach($this->field_array as $field){
				$value=$field->return_array(); //$field contient un objet, on retourne tout ça sous forme de tableau
				$this->validateFunction($value);
			}
		}
		
		private function validateFunction($value){
			if($this->method=='get'){ //on passe tout en post pour simplfier
				if($value[0]=="listebox_date" && $value[0]=="listebox_time"){
					$_POST[$value[1]]=$_GET[$value[1]];
				}
				elseif($value[0]=="listebox_date"){
					$_POST[$value[1]]=$_GET[$value[1]];
					
					$_POST['mois_'.$value[1]]=$_GET['mois_'.$value[1]];
					$_POST['jour_'.$value[1]]=$_GET['joure_'.$value[1]];
					$_POST['annee_'.$value[1]]=$_GET['annee_'.$value[1]];
				}
				elseif($value[0]=="listebox_time"){
					$_POST[$value[1]]=$_GET[$value[1]];
					
					$_POST['heure_'.$value[1]]=$_GET['heure_'.$value[1]];
					$_POST['minute_'.$value[1]]=$_GET['minute_'.$value[1]];
					$_POST['seconde_'.$value[1]]=$_GET['seconde_'.$value[1]];
					$_POST['mois_'.$value[1]]=$_GET['mois_'.$value[1]];
					$_POST['jour_'.$value[1]]=$_GET['joure_'.$value[1]];
					$_POST['annee_'.$value[1]]=$_GET['annee_'.$value[1]];
				}
			}

			switch($value[0]){
				case 'input':
					foreach($value[3] as $contraint => $contraint_valeur){
						switch($contraint){
							case 'minsize':
								if(strlen($_POST[$value[1]])>=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'maxsize':
								if(strlen($_POST[$value[1]])<=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'size':
								if(strlen($_POST[$value[1]])==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'intersize':
								$contraint_val=explode('-', $contraint_valeur);
								if(strlen($_POST[$value[1]])>=$contraint_val[0] && strlen($_POST[$value[1]])<=$contraint_val[1]){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;

							case 'between':
								$contraint_val=explode('-', $contraint_valeur);
								if($_POST[$value[1]]>=$contraint_val[0] && $_POST[$value[1]]<=$contraint_val[1]){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;

							case 'between':
								$contraint_val=explode('-', $contraint_valeur);
								if($_POST[$value[1]]>=$contraint_val[0] && $_POST[$value[1]]<=$contraint_val[1]){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'different' :
								if(($_POST[$value[1]])!=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'equalto' :
								if(($_POST[$value[1]])==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'regex' :
								if(preg_match('#'.$contraint_valeur.'#isU', $_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isset' :
								if(isset($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'empty' :
								if(empty($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isUrl' :
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_URL)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isMail' :
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_EMAIL)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isInt':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_INT)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isFloat':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_FLOAT)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isChar':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_CHAR)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isAlphaNum':
								if(!preg_match('#\W#',$_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isIp':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_IP)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'noEspace' :
								if($_POST[$value[1]]!="" AND preg_match('#[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\.\(\)\[\]\"\'\-,;:\/!\?]#isU', $_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								elseif($_POST[$value[1]]!=""){
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
								else{
									array_push($this->valid, 'true');
								}
							break;
							
							case 'whitoutEspace' :
								if(!preg_match('#\s#isU', $_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								elseif($_POST[$value[1]]!=""){
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
								else{
									array_push($this->valid, 'true');
								}
							break;
							
							case 'required':
								if($contraint_valeur=='true'){
									if(isset($_POST[$value[1]]) && $_POST[$value[1]]!=""){
										array_push($this->valid, 'true');
									}
									else{
										array_push($this->valid, 'false');
										array_push($this->error, $value[2].' : '.$value[4][$this->i]);
									}
								}
							break;
							
							case 'nullTo':
								if(isset($_POST[$value[1]]) && $_POST[$value[1]]==""){
									$_POST[$value[1]]=$contraint_valeur;
								}
							break;
							
							case 'sqlCount' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 

								$var = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($var as $key => $values) {
									$query->bindParam(1+$key, trim($values));
								}

								$query->execute();

								$query = $query->fetchColumn();
								
								switch($contrainte){
									case '>':
										if($query>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'sql' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$champs = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$5', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($var as $key => $values) {
									$query->bindParam(1+$key, trim($values));
								}

								$query->execute();

								$query = $query->fetch();
								
								switch($contrainte){
									case '>':
										if($query[''.$champs.'']>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query[''.$champs.'']>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query[''.$champs.'']<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query[''.$champs.'']<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query[''.$champs.'']!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query[''.$champs.'']==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'sql' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$champs = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$5', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetch();
								
								switch($contrainte){
									case '>':
										if($query[''.$champs.'']>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query[''.$champs.'']>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query[''.$champs.'']<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query[''.$champs.'']<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query[''.$champs.'']!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query[''.$champs.'']==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'process':
								$this->fieldProcess($contraint_valeur, $value[1]);
							break;
						}
						$this->i++;
					}
				break;
				
				case 'file':
					foreach($value[3] as $contraint => $contraint_valeur){
						switch($contraint){
							case 'minsize':
								if(strlen($_FILES[$value[1]]['size'])>=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'maxsize':
								if(strlen($_FILES[$value[1]]['size'])<=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'size':
								if(strlen($_FILES[$value[1]]['size'])==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'intersize':
								$contraint_val=explode('-', $contraint_valeur);
								if(strlen($_FILES[$value[1]]['size'])>=$contraint_val[0] && strlen($_FILES[$value[1]]['size'])<=$contraint_val[1]){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;

							case 'between':
								$contraint_val=explode('-', $contraint_valeur);
								if($_POST[$value[1]]>=$contraint_val[0] && $_POST[$value[1]]<=$contraint_val[1]){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'different' :
								if(($_FILES[$value[1]]['size'])!=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'equalto' :
								if(($_FILES[$value[1]]['size'])==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'regex' :
								if(preg_match('#'.$contraint_valeur.'#isU', $_FILES[$value[1]]['name'])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isset' :
								if(isset($_FILES[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'empty' :
								if(empty($_FILES[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'file_accept':
								if(in_array($_FILES[$value[1]]['type'], $contraint_valeur)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'required':
								if($contraint_valeur=='true'){
									if(isset($_POST[$value[1]]) && $_POST[$value[1]]!=""){
										array_push($this->valid, 'true');
									}
									else{
										array_push($this->valid, 'false');
										array_push($this->error, $value[2].' : '.$value[4][$this->i]);
									}
								}
							break;
							
							case 'nullTo':
								if(isset($_POST[$value[1]]) && $_POST[$value[1]]==""){
									$_POST[$value[1]]=$contraint_valeur;
								}
							break;
							
							case 'process':
								$this->fieldProcess($contraint_valeur, $value[1]);
							break;
						}
						$this->i++;
					}		
				break;
				
				case 'checkbox':
					foreach($value[3] as $contraint => $contraint_valeur){
						switch($contraint){
							case 'isset' :
								if(isset($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'empty' :
								if(empty($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'value':
								if($_POST[$value[1]]==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'required':
								if($contraint_valeur=='true'){
									if(isset($_POST[$value[1]]) && $_POST[$value[1]]!=""){
										array_push($this->valid, 'true');
									}
									else{
										array_push($this->valid, 'false');
										array_push($this->error, $value[2].' : '.$value[4][$this->i]);
									}
								}
							break;
							
							case 'nullTo':
								if(isset($_POST[$value[1]]) && $_POST[$value[1]]==""){
									$_POST[$value[1]]=$contraint_valeur;
								}
							break;
							
							case 'process':
								$this->fieldProcess($contraint_valeur, $value[1]);
							break;
						}
						$this->i++;
					}
				break;
				
				case 'radio':
					foreach($value[3] as $contraint => $contraint_valeur){
						switch($contraint){
							case 'different' :
								if(($_POST[$value[1]])!=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'equalto' :
								if(($_POST[$value[1]])==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'regex' :
								if(preg_match('#'.$contraint_valeur.'#isU', $_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isset' :
								if(isset($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'empty' :
								if(empty($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'required':
								if($contraint_valeur=='true'){
									if(isset($_POST[$value[1]]) && $_POST[$value[1]]!=""){
										array_push($this->valid, 'true');
									}
									else{
										array_push($this->valid, 'false');
										array_push($this->error, $value[2].' : '.$value[4][$this->i]);
									}
								}
							break;
							
							case 'nullTo':
								if(isset($_POST[$value[1]]) && $_POST[$value[1]]==""){
									$_POST[$value[1]]=$contraint_valeur;
								}
							break;
							
							case 'sqlCount' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetchColumn();
								
								switch($contrainte){
									case '>':
										if($query>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'sql' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$champs = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$5', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetch();
								
								switch($contrainte){
									case '>':
										if($query[''.$champs.'']>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query[''.$champs.'']>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query[''.$champs.'']<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query[''.$champs.'']<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query[''.$champs.'']!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query[''.$champs.'']==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'process':
								$this->fieldProcess($contraint_valeur, $value[1]);
							break;
						}
						$this->i++;
					}
				break;
				
				case 'listebox':
					foreach($value[3] as $contraint => $contraint_valeur){
						switch($contraint){
							case 'minsize':
								if(strlen($_POST[$value[1]])>=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'maxsize':
								if(strlen($_POST[$value[1]])<=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'size':
								if(strlen($_POST[$value[1]])==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'intersize':
								$contraint_val=explode('-', $contraint_valeur);
								if(strlen($_POST[$value[1]])>=$contraint_val[0] && strlen($_POST[$value[1]])<=$contraint_val[1]){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;

							case 'between':
								$contraint_val=explode('-', $contraint_valeur);
								if($_POST[$value[1]]>=$contraint_val[0] && $_POST[$value[1]]<=$contraint_val[1]){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'different' :
								if(($_POST[$value[1]])!=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'equalto' :
								if(($_POST[$value[1]])==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'regex' :
								if(preg_match('#'.$contraint_valeur.'#isU', $_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isset' :
								if(isset($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'empty' :
								if(empty($_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isUrl' :
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_URL)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isMail' :
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_EMAIL)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isInt':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_INT)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isFloat':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_FLOAT)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isChar':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_CHAR)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isAlphaNum':
								if(!preg_match('#\W#',$_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isIp':
								if(filter_var($_POST[$value[1]],FILTER_VALIDATE_IP)){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'noEspace' :
								if($_POST[$value[1]]!="" AND preg_match('#[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\.\(\)\[\]\"\'\-,;:\/!\?]#isU', $_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								elseif($_POST[$value[1]]!=""){
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
								else{
									array_push($this->valid, 'true');
								}
							break;
							
							case 'whitoutEspace' :
								if(!preg_match('#\s#isU', $_POST[$value[1]])){
									array_push($this->valid, 'true');
								}
								elseif($_POST[$value[1]]!=""){
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
								else{
									array_push($this->valid, 'true');
								}
							break;
							
							case 'required':
								if($contraint_valeur=='true'){
									if(isset($_POST[$value[1]]) && $_POST[$value[1]]!=""){
										array_push($this->valid, 'true');
									}
									else{
										array_push($this->valid, 'false');
										array_push($this->error, $value[2].' : '.$value[4][$this->i]);
									}
								}
							break;
							
							case 'nullTo':
								if(isset($_POST[$value[1]]) && $_POST[$value[1]]==""){
									$_POST[$value[1]]=$contraint_valeur;
								}
							break;
							
							case 'sqlCount' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetchColumn();
								
								switch($contrainte){
									case '>':
										if($query>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'sql' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$champs = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$5', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetch();
								
								switch($contrainte){
									case '>':
										if($query[''.$champs.'']>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query[''.$champs.'']>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query[''.$champs.'']<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query[''.$champs.'']<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query[''.$champs.'']!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query[''.$champs.'']==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'process':
								$this->fieldProcess($contraint_valeur, $value[1]);
							break;
						}
						$this->i++;
					}
				break;
				
				case 'listebox_date':
					$date = mktime(0,0,0, $_POST['mois_'.$value[1]], $_POST['jour_'.$value[1]], $_POST['annee_'.$value[1]]);
					foreach($value[3] as $contraint => $contraint_valeur){
						switch($contraint){		
							
							case 'different' :
								if(($date)!=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'equalto' :
								if(($date)==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isset' :
								if(isset($_POST['mois_'.$value[1]]) && 
								   isset($_POST['jour_'.$value[1]]) && 
								   isset($_POST['annee_'.$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'empty' :
								if(empty($_POST['mois_'.$value[1]]) && 
								   empty($_POST['jour_'.$value[1]]) && 
								   empty($_POST['annee_'.$value[1]])){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'required':
								if($contraint_valeur=='true'){
									if(isset($_POST['mois_'.$value[1]]) && $_POST['mois_'.$value[1]]!="" &&
									   isset($_POST['jour_'.$value[1]]) && $_POST['jour_'.$value[1]]!="" &&
									   isset($_POST['annee_'.$value[1]]) && $_POST['annee_'.$value[1]]!=""){
										array_push($this->valid, 'true');
									}
									else{
										array_push($this->valid, 'false');
										array_push($this->error, $value[2].' : '.$value[4][$this->i]);
									}
								}
							break;
							
							case 'nullTo':
								if(isset($_POST[$value[1]]) && $_POST[$value[1]]==""){
									$_POST[$value[1]]=$contraint_valeur;
								}
							break;
							
							case 'sqlCount' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetchColumn();
								
								switch($contrainte){
									case '>':
										if($query>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'sql' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$champs = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$5', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetch();
								
								switch($contrainte){
									case '>':
										if($query[''.$champs.'']>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query[''.$champs.'']>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query[''.$champs.'']<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query[''.$champs.'']<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query[''.$champs.'']!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query[''.$champs.'']==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
						}
					}
				break;
							
				case 'listebox_time':
					$date = mktime($_POST['heure_'.$value[1]], $_POST['minute_'.$value[1]], $_POST['seconde_'.$value[1]], $_POST['mois_'.$value[1]], $_POST['jour_'.$value[1]], $_POST['annee_'.$value[1]]);
					foreach($value[3] as $contraint => $contraint_valeur){
						switch($contraint){								
							case 'different' :
								if(($date)!=$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'equalto' :
								if(($date)==$contraint_valeur){
									array_push($this->valid, 'true');
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'isset' :
								if(isset($_POST['mois_'.$value[1]]) && 
									isset($_POST['jour_'.$value[1]]) && 
									isset($_POST['annee_'.$value[1]]) && 
									isset($_POST['heure_'.$value[1]]) && 
									isset($_POST['minute_'.$value[1]]) && 
									isset($_POST['secondee_'.$value[1]])){
										array_push($this->valid, 'true');
									}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'empty' :
								if(empty($_POST['mois_'.$value[1]]) && 
								empty($_POST['jour_'.$value[1]]) && 
								empty($_POST['annee_'.$value[1]]) && 
								empty($_POST['heure_'.$value[1]]) && 
								empty($_POST['minute_'.$value[1]]) && 
								empty($_POST['secondee_'.$value[1]])){
									array_push($this->valid, 'true');
								
								}
								else{
									array_push($this->valid, 'false');
									array_push($this->error, $value[2].' : '.$value[4][$this->i]);
								}
							break;
							
							case 'required':
								if($contraint_valeur=='true'){
									if(isset($_POST['mois_'.$value[1]]) && $_POST['mois_'.$value[1]]!="" && 
									isset($_POST['jour_'.$value[1]]) && $_POST['jour_'.$value[1]]!="" &&
									isset($_POST['annee_'.$value[1]]) && $_POST['annee_'.$value[1]]!="" && 
									isset($_POST['heure_'.$value[1]]) && $_POST['heure_'.$value[1]]!="" && 
									isset($_POST['minute_'.$value[1]]) && $_POST['minute_'.$value[1]]!="" && 
									isset($_POST['secondee_'.$value[1]]) && $_POST['seconde_'.$value[1]]!=""){
										array_push($this->valid, 'true');
									}
									else{
										array_push($this->valid, 'false');
										array_push($this->error, $value[2].' : '.$value[4][$this->i]);
									}
								}
							break;
							
							case 'nullTo':
								if(isset($_POST[$value[1]]) && $_POST[$value[1]]==""){
									$_POST[$value[1]]=$contraint_valeur;
								}
							break;
							
							case 'sqlCount' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetchColumn();
								
								switch($contrainte){
									case '>':
										if($query>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'sql' :
								$sql= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$1', $contraint_valeur); 
								$vars = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$2', $contraint_valeur); 
								$champs = preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$3', $contraint_valeur); 
								$contrainte= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$4', $contraint_valeur); 
								$valeur= preg_replace('#\[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\] \[(.*)\]#isU', '$5', $contraint_valeur); 

								$vars = explode(',', $vars);

								$query = $this->bdd->prepare(''.$sql.'');

								foreach ($vars as $key => $value) {
									$query->bindParam($key+1, trim($value));
								}

								$query = $query->fetch();
								
								switch($contrainte){
									case '>':
										if($query[''.$champs.'']>$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '>=':
										if($query[''.$champs.'']>=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<':
										if($query[''.$champs.'']<$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '<=':
										if($query[''.$champs.'']<=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '!=':
										if($query[''.$champs.'']!=$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
									
									case '==':
										if($query[''.$champs.'']==$valeur){
											array_push($this->valid, 'true');
										}
										else{
											array_push($this->valid, 'false');
											array_push($this->error, $value[2].' : '.$value[4][$this->i]);
										}
									break;
								}
							break;
							
							case 'process':
								$this->fieldProcess($contraint_valeur, $value[1]);
							break;
						}
						$this->i++;
					}
				break;
			}
			$this->i=0;
		}
		
		private  function fieldProcess($string, $variable){
			$strings = explode(',', $string);
			
			foreach($strings as $str){
				$str= trim($str);
				switch($str){
					case 'addcslashes':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=addcslashes($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=addcslashes($_POST[''.$variable.'']);
						}
					break;
					
					case 'addslashes':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=addslashes($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=addslashes($_POST[''.$variable.'']);
						}
					break;
					
					case 'bin2hex':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=bin2hex($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=bin2hex($_POST[''.$variable.'']);
						}
					break;
					
					case 'hex2bin':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=hex2bin($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=hex2bin($_POST[''.$variable.'']);
						}
					break;
					
					case 'convert_uudecode':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=convert_uudecode($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=convert_uudecode($_POST[''.$variable.'']);
						}
					break;
					
					case 'convert_uuencode':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=convert_uuencode($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=convert_uuencode($_POST[''.$variable.'']);
						}
					break;
					
					case 'crypt':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=crypt($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=crypt($_POST[''.$variable.'']);
						}
					break;
					
					case 'html_entity_decode':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=html_entity_decode($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=html_entity_decode($_POST[''.$variable.'']);
						}
					break;
					
					case 'htmlentities':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=htmlentities($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=htmlentities($_POST[''.$variable.'']);
						}
					break;
					
					case 'htmlspecialchars_decode':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=htmlspecialchars_decode($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=htmlspecialchars_decode($_POST[''.$variable.'']);
						}
					break;
					
					case 'htmlspecialchars':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=htmlspecialchars($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=htmlspecialchars($_POST[''.$variable.'']);
						}
					break;
					
					case 'intval':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=intval($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=intval($_POST[''.$variable.'']);
						}
					break;
					
					case 'lcfirst':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=lcfirst($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=lcfirst($_POST[''.$variable.'']);
						}
					break;
					
					case 'ltrim':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=ltrim($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=ltrim($_POST[''.$variable.'']);
						}
					break;
					
					case 'md5':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=md5($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=md5($_POST[''.$variable.'']);
						}
					break;
					
					case 'nl2br':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=nl2br($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=nl2br($_POST[''.$variable.'']);
						}
					break;
					
					case 'rtrim':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=rtrim($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=rtrim($_POST[''.$variable.'']);
						}
					break;
					
					case 'sha1':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=sha1($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=sha1($_POST[''.$variable.'']);
						}
					break;
					
					case 'strval':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=strval($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=strval($_POST[''.$variable.'']);
						}
					break;
					
					case 'strip_tags':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=strip_tags($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=strip_tags($_POST[''.$variable.'']);
						}
					break;
					
					case 'stripcslashes':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=stripcslashes($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=stripcslashes($_POST[''.$variable.'']);
						}
					break;
					
					case 'stripslashes':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=stripslashes($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=stripslashes($_POST[''.$variable.'']);
						}
					break;
					
					case 'strrev':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=strrev($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=strrev($_POST[''.$variable.'']);
						}
					break;
					
					case 'strtolower':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=strtolower($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=strtolower($_POST[''.$variable.'']);
						}
					break;
					
					case 'strtoupper':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=strtoupper($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=strtoupper($_POST[''.$variable.'']);
						}
					break;
					
					case 'trim':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=trim($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=trim($_POST[''.$variable.'']);
						}
					break;
					
					case 'ucfirst':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=ucfirst($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=ucfirst($_POST[''.$variable.'']);
						}
					break;
					
					case 'ucwords':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=ucwords($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=ucwords($_POST[''.$variable.'']);
						}
					break;
					
					case 'utf8_encode':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=utf8_encode($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=utf8_encode($_POST[''.$variable.'']);
						}
					break;
					
					case 'utf8_decode':
						if($this->method=="GET"){
							$_GET[''.$variable.'']=utf8_decode($_GET[''.$variable.'']);
						}
						else{
							$_POST[''.$variable.'']=utf8_decode($_POST[''.$variable.'']);
						}
					break;
				}
			}
		}
		
		/**
		 * valide les champs du formulaire
		 * @access public
		 * @param $name : nom du formulaire à valider
		 * @return bool
		 * @since 2.0
		*/

		public  function validateForm($name=""){
			//on verifie l'existence du champs token si besoin
			if($this->token == true){
				if(isset($_POST[''.$this->tokenName.''])){

				}
				else{
					$this->valid = array();
					$this->error = array();
					array_push($this->valid, 'false');
					array_push($this->error, $this->tokenError);

					return false;											
				}
			}

			if($name!=""){
				$this->validateField($name);
			}
			else{
				$this->validateFields();
			}
			
			if(in_array('false', $this->valid)){
				return false;
			}
			else{
				return true;
			}
		}

		/**
		 * affiche les erreurs
		 * @access public
		 * @return string
		 * @since 2.0
		*/
		
		public  function showError(){
			foreach($this->error as $erreur){
				$error .= $erreur."<br />\n";
			}
			return $error;
		}
		
		/**
		 * affiche les erreurs dans un div
		 * @access public
		 * @return string
		 * @since 2.0
		*/

		public  function showErrorBlock(){
			foreach($this->error as $erreur){
				$this->Content.=$erreur."<br />";
			}
			return '<div class="alert alert-error">'.$this->Content.'</div>';
		}
		
		/**
		 * efface les erreurs
		 * @access public
		 * @return void
		 * @since 2.0
		*/

		public  function cleanError(){
			$this->error=null;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class formsGCField {
		
		/* -------- FORMULAIRE -------- */
		
		private $type;
		private $name;
		private $name_content;
		private $contraint=array();
		private $error=array();
		
		/* ---------- CONSTRUCTEUR --------- */
		
		public  function __construct($type, $name="", $name_content="", $contraint="", $error=""){
			$this->type=$type;
			$this->name=$name;
			$this->name_content=$name_content;
			$this->contraint=$contraint;
			$this->error=$error;
		}
		
		public function return_array(){
			return $array = array($this->type, $this->name, $this->name_content, $this->contraint, $this->error); 
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}