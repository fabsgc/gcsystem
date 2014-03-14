<gc:variable var ="<?php
	class ".$rubrique." extends controller{
		public function init(){
		}

		public function end(){
		}
		
		public function actionDefault(){
			".'$this'."->showDefault();
		}
	}" />
{var}