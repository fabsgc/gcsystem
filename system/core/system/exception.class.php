<?php
	/*\
	 | ------------------------------------------------------
	 | @file : exception.class.php
	 | @author : fab@c++
	 | @description : override of php exception and error
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class exception extends \Exception{
			
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
			 * @param string
			 * @since 3.0
 			 * @package system
			*/
			
			public function __toString(){
				return $this->message;
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

		class errorHlander { 
			use error;

			/**
			 * constructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/
			
			public function __construct () { 
				set_error_handler(array($this, 'errorHlander'));
				set_exception_handler(array($this, 'exceptionHandler')); 
			}

			/**
			 * capture error
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function errorHlander($errno, $errstr, $errfile, $errline){
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
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function exceptionHandler($e){
				$this->addError($e->getMessage(), $e->getFile(), $e->getLine(), ERROR_EXCEPTION);
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
	}