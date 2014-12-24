<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Exception.class.php
	 | @author : fab@c++
	 | @description : overriding of php exceptions
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Exception;

	class Exception extends \Exception{

		/**
		 * constructor
		 * @access public
		 * @since 3.0
		 * @package system
		*/

		public function __construct ($message, $code = 0, Exception $previous = null){
			parent::__construct ($message, $code, $previous);
		}

		/**
		 * toString
		 * @access public
		 * @since 3.0
		 * @package system
		*/

		public function __toString(){
			return $this->message;
		}

		/**
		 * @return string
		*/

		public function getType(){
			return ERROR_EXCEPTION;
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system
		*/

		public function __destruct(){
		}
	}