<?php
	/*\
	 | ------------------------------------------------------
	 | @file : MissingControllerException.class.php
	 | @author : fab@c++
	 | @description : overriding of php exceptions
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Exception;

	class MissingControllerException extends Exception{
		public function getType(){
			return 'MissingControllerException';
		}
	}