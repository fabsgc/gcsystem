<?php
	class terminal extends applicationGc{
		public function index(){
			$this->setInfo(array('title'=>'Terminal - GCsystem', 'css'=>''));
			echo $this->affHeader();
				$t= new templateGC(GCSYSTEM_PATH.'GCterminal', 'GCterminal', '0');
				if(ENVIRONMENT == 'development') $t->assign(array('moins' => 50, 'moins2'=>80));
					else $t->assign(array('moins' => 0, 'moins2' => 30));
				$t->show();
			echo $this->affFooter();
		}
		
		public function commande(){
			$terminal = new terminalGC($_POST['message']);
			echo $terminal->parse();
		}
	}
?>