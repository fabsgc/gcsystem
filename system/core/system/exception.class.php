<?php
	/*\
	 | ------------------------------------------------------
	 | @file : exception.class.php
	 | @author : fab@c++
	 | @description : class gérant les exceptions
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class exception extends \Exception{
			public function __construct ($message, $code = 0){
				parent::__construct ($message, $code);
			}
			
			public function __toString(){
				return $this->message;
			}

			public  function __destruct(){
			}
		}
	}