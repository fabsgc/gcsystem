{{php: $var='<?php
	namespace '.$src.';

	use system\Controller\Controller;

	class '.ucfirst($controller).' extends Controller{
		public function init(){
		}

		public function end(){
		}
		
		public function actionDefault(){
			return $this->showDefault();
		}
	}';
}}
{$var}