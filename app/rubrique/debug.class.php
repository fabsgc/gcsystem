<?php
	/**
	 * @info : contrôleur créé automatiquement par le GCsystem
	*/
	
	class debug extends applicationGc{
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
			/*--------------------------------FORMULAIRE------------------------------*/
			$this->forms['forms_inscription'] = new formsGC(array('name' => 'register', 'action' => '#', 'method' => 'post'));
			$this->forms['forms_inscription']->addFieldset('Inscription');
			$this->forms['forms_inscription']->addInputText('Inscription', "", "text", array('name'=>"nom", 'id' => "nom", 'maxlenght'=>10000, 'alt'=>'', 'placeholder' => 'Nom'),  0);
			$this->forms['forms_inscription']->addHtml('Inscription', '<span class="error_nom"></span><br />');
			$this->forms['forms_inscription']->addInputText('Inscription', "", "text", array('name'=>"prenom", 'id' => "prenom", 'maxlenght'=>10000, 'alt'=>'', 'placeholder' => 'Prénom'),  0);
			$this->forms['forms_inscription']->addHtml('Inscription', '<span class="error_prenom"></span><br />');
			$this->forms['forms_inscription']->addInputText('Inscription', "", "text", array('name'=>"pseudo", 'id' => "pseudo", 'maxlenght'=>10000, 'alt'=>'', 'placeholder' => 'Pseudo'),  0);
			$this->forms['forms_inscription']->addHtml('Inscription', '<span class="error_pseudo"></span><br />');
			$this->forms['forms_inscription']->addInputText('Inscription', "", "password", array('name'=>"mdp", 'id' => "mdp", 'maxlenght'=>10000, 'alt'=>'', 'placeholder' => 'Mot de passe'),  0);
			$this->forms['forms_inscription']->addHtml('Inscription', '<span class="error_mdp"></span><br />');
			$this->forms['forms_inscription']->addInputText('Inscription', "", "password", array('name'=>"mdp_conf", 'id' => "mdp_conf", 'maxlenght'=>10000, 'alt'=>'', 'placeholder' => 'Confirmation du Mot de passe'),  0);
			$this->forms['forms_inscription']->addHtml('Inscription', '<span class="error_mdp_conf"></span><br />');			
			$this->forms['forms_inscription']->addInputText('Inscription', "", "email", array('name'=>"email", 'id' => "email", 'maxlenght'=>10000, 'alt'=>'', 'placeholder' => 'Email'),  0);
			$this->forms['forms_inscription']->addHtml('Inscription', '<span class="error_email"></span><br />');
			$this->forms['forms_inscription']->addHtml('Inscription', '<input type="submit" value="Envoyer" />', 1);
			 
		        
			if(isset($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['mdp'], $_POST['mdp_conf'], $_POST['email'])){
			
				/*---------------------------------VALIDATION---------------------------------*/
				$this->forms['validate_inscription'] = new formsGCValidator('post', $this->bdd[BDD]);
				$this->forms['validate_inscription']->addfield('input', 'pseudo', 'pseudo', 
					array('different' => '', 'sqlCount'=>'[SELECT COUNT(*) FROM membre_membre WHERE membre_pseudo = "'.@$_POST['pseudo'].'"] [==] [1]', 'noEspace'=>'', 'isAlphaNum'=>''), 
					array('Vous devez entrer un pseudo.', 'Le pseudo existe déjà.', 'Votre pseudo ne peut pas commencer par un espace', 'Seuls les caractères alphanumériques sont autorisés'));
				$this->forms['validate_inscription']->addfield('input', 'mdp', 'mot de passe', array('different' => '', 'whitoutEspace'=>''), array('Vous devez choisir un mot de passe', 'Un mot de passe ne peut pas contenir d\'espaces'));	
				$this->forms['validate_inscription']->addfield('input', 'mdp_conf', ' confirmation du mot de passe', array('different' => '', 'whitoutEspace'=>'', 'equalto'=>$_POST['mdp']), array('Vous devez retaper votre mot de passe', 'Un mot de passe ne peut pas contenir d\'espaces', 'Les deux mot de passes sont différents.'));	
				//$this->forms['validate_inscription']->addfield('input', 'email', 'email', 
				//	array('different' => '', 'whitoutEspace'=>'', 'isMail'=>'','sqlCount'=>'[SELECT COUNT(*) FROM membre_membre WHERE membre_email = "'.@$_POST['email'].'"] [==] [1]'), 
				//	array('Vous devez donner une adresse email', 'Une adresse mail ne peut pas contenir d\'espaces', 'Veuiller saisir une adresse mail valide', 'Votre adresse email est déjà associée à un compte'));
					
				if($this->forms['validate_inscription']->ValidateForm()==true){
				    $this->windowInfo('Inscription', 'Vous êtes inscrit !', 5, 'index'.FILES_EXT); 
	            }
	            else{
					echo $this->forms['validate_inscription']->showErrorBlock();
	                echo $this->forms['forms_inscription']->showForms();
	            }
           	} 
           	else {
                echo $this->forms['forms_inscription']->showForms();
            }
		}

		public function actionInstall(){
			$install = new installGc();
            if($install->checkUninstall('22662503a6463b6c706.64500547')){
            	$install->uninstall();
            }else{
            	echo $install->showError();
            }
		}

		public function actionSyntaxhighlighter(){
			$this->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
			echo $this->showHeader();
			$bbcode = new bbcodeGc();
			$bbcode-> editor('', array('theme' => 'grayish'));

			echo $bbcode->parse('
				[code type="css"].css{

				<script>alert(\'salut\');</script> [a url="zz"]zzzzzzzzz[/a]
					http://localhost:82/GCsystem/asset/image/GCsystem/bbcode/code.png
					<br />
					<a href="http://dddd">aaaaaaaaa</a>
				}[/code]
				[a url="sa"]sa[/a]

				<a href="http://dddd" > aaaaaaaaa</a>
				dfqsdqsdf <br />[font val="courier"]salut[/font] 
				http://localhost:82/GCsystem/asset/image/GCsystem/bbcode/code.png

				salut');
			echo $this->showFooter();
		}

		public function actionTplHtmlHeader(){
			$t = new templateGc('test2','test2', 0);
			$t -> show();

			$this->errorPerso(15);
		}

		public function actionToZip(){
			$zip = new zipGc('test.zip');
			$zip->putFileToZip('asset/css/default.css', zipGc::PUTDIR);
		}
	}