{{php: $var='<?php
	namespace gcs;

	class '.$controller.' extends \system\controller{		
		public function init(){
		}

		public function end(){
		}
		
		public function actionDefault(){
			return $this->showDefault();
		}
	}'; }}