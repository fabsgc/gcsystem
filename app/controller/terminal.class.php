<?php
	class terminal extends applicationGc{		
		public function init(){
			require_once(CLASS_TERMINAL);
			require_once(CLASS_INSTALL);
		}

		public function end(){
		}
		
		public function actionDefault(){
			$t= new templateGC(GCSYSTEM_PATH.'GCterminal', 'GCterminal', '0');
			if(ENVIRONMENT == 'development') $t->assign(array('moins' => 50, 'moins2'=>80));
				else $t->assign(array('moins' => 0, 'moins2' => 30));
			$t->show();
		}
		
		public function actionTerminal(){
			$this->loadHelper(array('fileGc', 'dirGc', 'zipGc', 'dateGc'));
			$terminal = new terminalGC(strip_tags(html_entity_decode($_POST['command'])), $this->bdd);
			echo $terminal->parse();
		}
	}