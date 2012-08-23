<?php
	/**
	 * @info : contrôleur créé automatiquement par le GCsystem
	*/
	
	class install extends applicationGc{
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
			//$this->showDefault();
			$install = new installGc('installtest.zip', $this->_lang);
			$install->check();
			echo $install->showError();
		}
	}