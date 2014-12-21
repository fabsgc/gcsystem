<gc:variable var ="<?php
	class ".$controller." extends system\controller{
		public function init(){
		}

		public function end(){
		}
		
		public function actionDefault(){
			".'$this'."->showDefault();
		}
	}" />
{var}