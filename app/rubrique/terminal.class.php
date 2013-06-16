<?php
	class terminal extends applicationGc{
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
			$t= new templateGC(GCSYSTEM_PATH.'GCterminal', 'GCterminal', '0');
			if(ENVIRONMENT == 'development') $t->assign(array('moins' => 50, 'moins2'=>80));
				else $t->assign(array('moins' => 0, 'moins2' => 30));
			$t->show();
		}
		
		public function actionTerminal(){
			$terminal = new terminalGC($_POST['message'], $this->bdd);
			echo $terminal->parse();
		}
	}