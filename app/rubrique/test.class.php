<?php
	class test extends applicationGc{
		public $forms                = array();
		public $sql                  = array();
		public $model                         ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
		}
	}