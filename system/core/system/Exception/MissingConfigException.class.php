<?php
	/*\
	 | ------------------------------------------------------
	 | @file : MissingLangException.class.php
	 | @author : fab@c++
	 | @description : overriding of php exceptions
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Exception;

	class MissingLangException extends Exception{
		public function getType(){
			return 'MissingLangException';
		}
	}