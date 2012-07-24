<?php
	/**
	 * @file : exceptionGC.class.php
	 * @author : fab@c++
	 * @description : class gérant les exceptions
	 * @version : 2.0 bêta
	*/
	
    class ExceptionGc extends Exception{
        public function __construct ($message, $code = 0){
            parent::__construct ($message, $code);
        }
        
        public function __toString(){
			return $this->message;
        }
    }