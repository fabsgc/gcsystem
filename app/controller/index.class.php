<?php
	class index extends system\controller{
		public function init(){
		}

		public function end(){
		}

		public function actionDefault(){
			$this->event->add(new system\event('myEvent', $this, array()));
			$this->event->add(new system\event('myEvent2', $this, array()));
			$this->event->dispatch();

			//print_r($this->event->getResult());

			$t = new system\template(GCSYSTEM_PATH.'GCsystem', 'GCsystem', 0, $this->lang);
			$t->show();
		}
	}