<?php
	class index extends system\controller{
		public $attribute;

		public function init(){
		}

		public function end(){
		}

		public function actionDefault(){
			$t = new system\template(GCSYSTEM_PATH.'system', 'system', 0, $this->lang);
			$t->show();
		}
	}