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
			echo $this->affHeader();
				$t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
				$t->assign(array(
					'var'=> 'salutsalut',
					'var2'=>'bonsoir'
				));
				$t->setShow(FALSE);
				echo $t->show();
			echo $this->affFooter();
		}
		
		public function actionSql(){
			$this->getVar('sql')->setVar(array('id' => $this->getVar('id')));
			$data = $this->getVar('sql')->fetch('nom');
			$data = $this->getVar('sql')->fetch('nom2');
		}
		
		public function actionForms(){
			echo $this->getVar('forms')->showForms();
		}
		
		public function actionTemplate(){
			$t = new templateGc('tpl1', 'tpl1', 0);
			$t->assign(array(
				'var'=> 'salutsalut',
				'var2'=>'bonsoir',
				'var3'=>'bonsoir numero 2',
				'prout'=>'on dit prout'
			));
			$t->show();
		}
		
		public function actionPagination(){
			// $this->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
			// echo $this->affHeader();
				// $ar = array('Agnaflai', 'Amagan', 'Anani Sikerim', 'Anayin Ami', 'Anus De Poulpe', 'Arschloch', 'Artaïl', 'Aspirateur à Bites', 'Aspirateur à Muscadet',
					 // 'Asshole', 'Ateye', 'Balafamouk', 'Baptou', 'Balai De Chiottes', 'Bassine A Foutre', 'Bite Molle', 'Bit molle',
					 // 'Bite de moll', 'Bit moll', 'Bleubite', 'Bordel', 'Bordel à Cul', 'Bordel de merde', 'Bordel de con', 'Bolosse', 'Bouche à Pipe',
					 // 'Bouffon', 'Bougre De Con', 'Bougre De Conne', 'Boursemolle', 'Boursouflure', 'Bouseux', 'Boz', 'Branleur', 'Butor', 'Cabron', 'Caja De Miera',
					 // 'Chancreux', 'Chien D\'infidèle', 'Chien Galeux', 'Chieur', 'Chiant', 'Clawi', 'Con', 'Conard', 'Connard', 'Connasse', 'Conne', 'Cono', 'Couille De Loup',
					 // 'Couille De Moineau', 'Couille De Tétard', 'Couille Molle', 'Couillon', 'Crevard', 'Crevure', 'Crétin', 'Cul De Babouin', 'Cul Terreux',
					 // 'Degueulasse', 'Ducon', 'Dégénéré Chromozomique', 'Embrayage', 'Emmerdeur', 'Encule Ta Mère', 'Enculeur De Mouches', 'enculé', 'Enfant De Tainpu',
					 // 'Face de bite', 'Face de caca', 'Face De Cul', 'Face De Pet', 'Face De Rat', 'Fils De Pute', 'Fouille Merde', 'Grognasse', 'Gros Con', 'Hijo De Puta', 'Lopette', 'Manche à Couille',
					 // 'Mange Merde', 'Merde', 'Mist', 'Moudlabite', 'Nike ta mère', 'Pauvre Con', 'Pendejo', 'Perra', 'Petite Merde',
					 // 'Ptit con', 'Petit con', 'Playboy De Superette', 'Pouffiasse', 'Putain',
					 // 'Pute', 'Put', 'Pute Au Rabais', 'Pétasse', 'Quéquette', 'Raclure De Bidet', 'Raclure De Chiotte', 'Sac à Merde', 'Safali', 'Salaud', 'Sale Pute', 'Sal pute', 'Sal put', 'Sale put', 'Sale con', 'Sal con', 'Sale connard', 'Sal connard', 'Sal conard', 'Sale connard', 'Sale conard', 'Saligaud',
					 // 'Salopard', 'Salope', 'Sous Merde', 'Spermatozoide Avarié', 'Suce Bites', 'Trou De Balle', 'Trou Du Cul', 'Trou du kul', 'Trou du qu', 'Trou du ku', 'Trou de bite', 'Tête De Bite', 'Va Te Faire', 'Va te faire niker', 'Vieux Con');
			
				// $page = new paginationGc(array(
					// 'url' => $this->getUrl('page2', array('{page}')),
					// 'entry' => $ar,
					// 'pageActuel' => $_GET['page'],
					// 'cut' => 8,
					// 'bypage' => 10
					// )
				// );
				
				// $page->show();
				
				// foreach($page->getData($ar) as $val){
					// echo $val.'<br />';
				// }
				
				// $page->show();
			// echo $this->affFooter();
		}
	}