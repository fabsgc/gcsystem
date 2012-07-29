<?php
	class index extends applicationGc{
		public function defaultIndex(){
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
		
		public function testSql(){
			$this->getVar('sql')->setVar(array('id' => $this->getVar('id')));
			$data = $this->getVar('sql')->fetch('nom');
			$data = $this->getVar('sql')->fetch('nom2');
		}
		
		public function testForms(){
			echo $this->getVar('forms')->showForms();
		}
		
		public function devPagination(){
			$page = new paginationGc(array(
				'url' => $this->getUrl('page2', array('{page}')),
				'entry' => 50,
				'pageActuel' => $_GET['page'],
				'cut' => 8
				)
			);
			
			$page->show();
		}
	}