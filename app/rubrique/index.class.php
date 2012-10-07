<?php
	class index extends applicationGc{
		protected $forms                = array();
		protected $sql                  = array();
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel();
			//$this->model->actionDefault();		
		}

		public function actionDefault(){
			$this->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
			echo $this->showHeader();
			/*	$t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
				$t->assign(array(
					'var'=> 'salutsalut',
					'var2'=>'bonsoir'
				));
				$t->setShow(FALSE);
				echo $t->show();

				$modo = new modoGc('sale pute ta salope de mère va enculer ce fils de pute', 1);
				echo $modo->censure();

				echo USER_PROUT;
*/
			echo $this->showFooter();
				$this->forms['forms_contact'] = new formsGC(array('name' => 'contact', 'action' => '#', 'method' => 'post'));
				$this->forms['forms_contact']->addFieldset('Contact');
				$this->forms['forms_contact']->addInputText('Contact', "", "text", array('name'=>"nom", 'value'=>"", 'maxlenght'=>10000, 'placeholder' => 'Nom'),  1);
				$this->forms['forms_contact']->addInputText('Contact', "", "text", array('name'=>"prenom", 'value'=>"", 'maxlenght'=>10000, 'placeholder' => 'Prénom'),  1);
			  	$this->forms['forms_contact']->addInputText('Contact', "", "email", array('name'=>"email", 'value'=>"", 'maxlenght'=>10000, 'placeholder' => 'Email'),  1);
				$this->forms['forms_contact']->addTextarea('Contact', '', "", array('name'=>'message', 'id'=>'textarea', 'cols'=>60, 'rows'=>40, 'placeholder' => 'Message'), 1);
				$this->forms['forms_contact']->addSubmitReset("Contact", array('value'=>'Envoyer'), 0);

				$this->forms['validate_contact'] = new formsGCValidator('post');
				$this->forms['validate_contact']->addfield('input', 'nom', 'nom', array('different' => '', 'noEspace'=>'', 'isAlphaNum'=>''), array('Vous devez choisir un nom', 'Votre nom ne peut pas commencer par un espace', 'Seuls les caractères alphanumériques sont autorisés'));
				$this->forms['validate_contact']->addfield('input', 'prenom', 'prenom', array('different' => '', 'noEspace'=>'', 'isAlphaNum'=>''), array('Vous devez choisir un prenom', 'Votre prenom ne peut pas commencer par un espace', 'Seuls les caractères alphanumériques sont autorisés'));
				$this->forms['validate_contact']->addfield('input', 'email', 'email', array('different' => '', 'whitoutEspace'=>'', 'isMail'=>''), array('Vous devez donner une adresse email', 'Une adresse mail ne peut pas contenir d\'espaces', 'Veuiller saisir une adresse mail valide'));

				if(isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['message'])){
				    if($this->forms['validate_contact']->ValidateForm()==true){
						$this->email['email_contact'] = new mailGc(array('expediteur' => $_POST['email'], 'destinataire' => 'mathiaduzero99@gmail.com', 'sujet' => 'Team-code - Contact'));
						$this->email['email_contact']->addText($_POST['message']);
						$this->email['email_contact']->send();
						echo 'L\'email a bien été envoyé !';
                    }
                    else{
                        echo $this->forms['validate_contact']->showErrorBlock(0, 'inscription'.FILES_EXT);
                        echo $this->forms['forms_contact']->showForms();
                    }
                } 
                else {
				    echo $this->forms['forms_contact']->showForms();
                }
		}
		
		public function actionSql(){
			$this->getVar('sql')->setVar(array('id' => $this->getVar('id')));
			$data = $this->getVar('sql')->fetch('nom');
			$data = $this->getVar('sql')->fetch('nom2');
		}
		
		public function actionForms(){
			echo $this->getVar('forms')->showForms();
		}
		
		public function actionPicture(){
			$this->setDevTool(false);
			$img = new pictureGc('asset/image/GCsystem/logo300_6.png');
		}
		
		public function actionTemplate(){
			$t = new templateGc('tpl1', 'tpl1', 0);
			$t->assign(array(
				'var'=> 'salutsalut',
				'var2'=>'bonsoir',
				'var3'=>'bonsoir numero 2',
				'prout'=>'on dit prout',
				'array' => array('1', '2', '3')
			));
			$t->show();
		}
		
		public function actionContype(){
		
		}

		public function actionActiontestdebug(){
			echo $this->getIp();
			echo $this->getFirewallConfigLoginTargetId();
		}

		public function actionCron(){
			echo 'trou du cul';
			$_SESSION['trouducul'] = 'salut ducon';
		}
		
		public function actionCron2(){
			echo 'trou du cul';
			$_SESSION['trouducul2'] = 'salut ducon';
		}

		public function actionPagination(){
			$this->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
			echo $this->showHeader();
			
			?>
			<form action="#" method="get">
				<p>
					<input type="text" name="prenom" />
					<input type="submit" value="Valider" />
				</p>
			</form>
			<?php
				echo $_GET['prenom'];
			
				$ar = array('Agnaflai', 'Amagan', 'Anani Sikerim', 'Anayin Ami', 'Anus De Poulpe', 'Arschloch', 'Artaïl', 'Aspirateur à Bites', 'Aspirateur à Muscadet',
					 'Asshole', 'Ateye', 'Balafamouk', 'Baptou', 'Balai De Chiottes', 'Bassine A Foutre', 'Bite Molle', 'Bit molle',
					 'Bite de moll', 'Bit moll', 'Bleubite', 'Bordel', 'Bordel à Cul', 'Bordel de merde', 'Bordel de con', 'Bolosse', 'Bouche à Pipe',
					 'Bouffon', 'Bougre De Con', 'Bougre De Conne', 'Boursemolle', 'Boursouflure', 'Bouseux', 'Boz', 'Branleur', 'Butor', 'Cabron', 'Caja De Miera',
					 'Chancreux', 'Chien D\'infidèle', 'Chien Galeux', 'Chieur', 'Chiant', 'Clawi', 'Con', 'Conard', 'Connard', 'Connasse', 'Conne', 'Cono', 'Couille De Loup',
					 'Couille De Moineau', 'Couille De Tétard', 'Couille Molle', 'Couillon', 'Crevard', 'Crevure', 'Crétin', 'Cul De Babouin', 'Cul Terreux',
					 'Degueulasse', 'Ducon', 'Dégénéré Chromozomique', 'Embrayage', 'Emmerdeur', 'Encule Ta Mère', 'Enculeur De Mouches', 'enculé', 'Enfant De Tainpu',
					 'Face de bite', 'Face de caca', 'Face De Cul', 'Face De Pet', 'Face De Rat', 'Fils De Pute', 'Fouille Merde', 'Grognasse', 'Gros Con', 'Hijo De Puta', 'Lopette', 'Manche à Couille',
					 'Mange Merde', 'Merde', 'Mist', 'Moudlabite', 'Nike ta mère', 'Pauvre Con', 'Pendejo', 'Perra', 'Petite Merde',
					 'Ptit con', 'Petit con', 'Playboy De Superette', 'Pouffiasse', 'Putain',
					 'Pute', 'Put', 'Pute Au Rabais', 'Pétasse', 'Quéquette', 'Raclure De Bidet', 'Raclure De Chiotte', 'Sac à Merde', 'Safali', 'Salaud', 'Sale Pute', 'Sal pute', 'Sal put', 'Sale put', 'Sale con', 'Sal con', 'Sale connard', 'Sal connard', 'Sal conard', 'Sale connard', 'Sale conard', 'Saligaud',
					 'Salopard', 'Salope', 'Sous Merde', 'Spermatozoide Avarié', 'Suce Bites', 'Trou De Balle', 'Trou Du Cul', 'Trou du kul', 'Trou du qu', 'Trou du ku', 'Trou de bite', 'Tête De Bite', 'Va Te Faire', 'Va te faire niker', 'Vieux Con');
			
				$page = new paginationGc(array(
					'url' => $this->getUrl('page2', array('{page}')),
					'entry' => $ar,
					'pageActuel' => $_GET['page'],
					'cut' => 8,
					'bypage' => 10
					)
				,'fr');
				
				$page->show();
				
				foreach($page->getData($ar) as $val){
					echo $val.'<br />';
				}
				
				$page->show();
			echo $this->showFooter();
		}
	}