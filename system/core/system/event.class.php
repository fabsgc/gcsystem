<?php
	/*\
	 | ------------------------------------------------------
	 | @file : event.class.php
	 | @author : fab@c++
	 | @description : implementation of the pattern design observer
	 | @version : 2.4 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class event {
			use error;

			public $parent              ; //reference to the object which start the event
			public $data       =    null; //data you can pass from the controller to the event

			protected $_name            ; //name of the event which is started
			protected $_status =    true; //status of the event : stopped or running
			protected $_result = array(); //data returned by the event after each call

			const START = true;
			const STOP = false;
			
			/**
			 * Constructor
			 * @access public
			 * @param $name string : name of the event
			 * @param $parent object : parent object
			 * @param $data array : data given
			 * @since 3.0
 			 * @package system
			*/

			public function __construct($name = 'event', $parent = null, $data = null) {
				$this->_name  = $name;
				$this->data   = $data;
				$this->parent = $parent;
			}

			/**
			 * Return the name
			 * @access public
			 * @return string
			 * @since 3.0
 			 * @package system
			*/

			public function getName(){
				return $this->_name;
			}

			/**
			 * Return the status : true if it's running and false otherwise
			 * @access public
			 * @return boolean
			 * @since 3.0
 			 * @package system
			*/

			public function getStatus(){
				return $this->_status;
			}

			/**
			 * Return data returned by the event after his call
			 * @access public
			 * @return array
			 * @since 3.0
 			 * @package system
			*/

			public function getResult(){
				return $this->_result;
			}

			/**
			 * Set the name
			 * @access public
			 * @param $name string 
			 * @return array
			 * @since 3.0
 			 * @package system
			*/

			public function setName($name = 'event'){
				$this->_name = $name;
			}

			/**
			 * Set the status
			 * @access public
			 * @param $status
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function setStatus($status = self::START){
				$this->_status = $status;
			}

			/**
			 * Set the result returned by the event (function used by the framework at the end of the call)
			 * @access public
			 * @param $result string : result returned by the listener
			 * @param $index int : order of the event (0,1,2,3)
			 * @param $class string : name of the called class
			 * @param $function string : name of the method called
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function setResult($result = '', $index, $class, $function){
				$this->_result[$index] = array('class' => $class, 'function' => $function, 'return' => $result);
			}

			/**
			 * Desctructor
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/
			
			public function __destruct(){
			}	
		}
	}