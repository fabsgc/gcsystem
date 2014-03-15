<?php
	class index extends system\controller{
		public function init(){
		}

		public function end(){
		}

		public function actionDefault(){
			$t= new system\template(GCSYSTEM_PATH.'GCsystem', 'GCsystem', 0, $this->lang);
			$t->show();
		}
	}