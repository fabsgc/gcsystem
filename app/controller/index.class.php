<?php
	class index extends applicationGc{
		public function init(){
		}

		public function end(){
		}

		public function actionDefault(){
			$t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', 0, $this->lang);
            $t->show();
		}
	}