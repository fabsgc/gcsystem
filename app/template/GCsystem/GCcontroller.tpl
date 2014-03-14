<gc:variable var ="<?php
	class ".$rubrique." extends applicationGc{
		public function init(){
		}

		public function end(){
		}
		
		public function actionDefault(){
			".'$this'."->showDefault();
		}
	}" />
{var}