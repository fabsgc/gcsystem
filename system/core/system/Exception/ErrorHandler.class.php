<?php
	/*\
	 | ------------------------------------------------------
	 | @file : ErrorHandler.class.php
	 | @author : fab@c++
	 | @description : overriding of php errors
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Exception;

	class ErrorHandler {
		use \system\General\Error;

		/**
		 * constructor
		 * @access public
		 * @since 3.0
		 * @package system
		*/

		public function __construct () {
			set_error_handler(array($this, 'errorHandler'));
			set_exception_handler(array($this, 'exceptionHandler'));
		}

		/**
		 * capture error
		 * @access public
		 * @param $errno
		 * @param $errstr
		 * @param $errfile
		 * @param $errline
		 * @return void
		 * @since 3.0
		 * @package system
		*/

		public function errorHandler($errno, $errstr, $errfile, $errline){
			$error = sprintf("[%d] (%s)", $errno, $errstr);

			switch($errno){
				case E_USER_NOTICE:
					$this->addError($error, $errfile, $errline, ERROR_ERROR, LOG_ERROR);
				break;

				case E_USER_WARNING:
					$this->addError($error, $errfile, $errline, ERROR_ERROR, LOG_ERROR);
				break;

				case E_WARNING:
					$this->addError($error, $errfile, $errline, ERROR_ERROR, LOG_ERROR);
				break;

				case E_USER_ERROR:
					$this->addError($error, $errfile, $errline, ERROR_ERROR, LOG_SYSTEM);
				break;

				default:
					$this->addError($error, $errfile, $errline, ERROR_ERROR, LOG_ERROR);
				break;
			}
		}

		/**
		 * capture exception
		 * @access public
		 * @param $e
		 * @return void
		 * @since 3.0
		 * @package system
		*/

		public function exceptionHandler($e){
			if(method_exists($e, 'getType'))
				$this->addError($e->getMessage(), $e->getFile(), $e->getLine(), $e->getType());
			else
				$this->addError($e->getMessage(), $e->getFile(), $e->getLine(), gettype($e));
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