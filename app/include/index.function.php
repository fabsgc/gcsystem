<?php
	class index extends applicationGc{
		public function defaultindex(){
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
	}
?>