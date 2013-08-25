<?php
    class index extends applicationGc{
        protected $model                         ;
        protected $bdd                           ;
        protected $forms                = array();
        protected $sql                           ;
        
        public function init(){
            $this->model = $this->loadModel(); //chargement du model
        }

        public function end(){
        }

        public function actionDefault(){
            /*$this->loadHelper('feedGc');
            //$t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', 0, $this->_lang);
            $t= new templateGC('test', 'test', 0, $this->_lang);
            $t->show();

            $ftp = new ftpGc();*/
        }
    }