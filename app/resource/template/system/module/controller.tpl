{{php: $var='<?php
	namespace '.$src.';

	class '.$controller.' extends \system\controller{		
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