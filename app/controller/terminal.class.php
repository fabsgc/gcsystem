<?php
	class terminal extends system\controller{		
		public function init(){
		}

		public function end(){
		}
		
		public function actionDefault(){
			$t= new system\template(GCSYSTEM_PATH.'GCterminal', 'GCterminal', '0');
			if(ENVIRONMENT == 'development') $t->assign(array('moins' => 50, 'moins2'=>80));
				else $t->assign(array('moins' => 0, 'moins2' => 30));
			$t->show();
		}
		
		public function actionTerminal(){
			$terminal = new terminal(strip_tags(html_entity_decode($_POST['command'])), $this->bdd);
			echo $terminal->parse();
		}
	}