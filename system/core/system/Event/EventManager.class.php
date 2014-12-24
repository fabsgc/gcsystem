<?php
	/*\
	 | ------------------------------------------------------
	 | @file : eventManager .class.php
	 | @author : fab@c++
	 | @description : class permettant l'utilisation du design pattern observer
	 | @version : 2.4 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Event;

	use system\General\error;

	class EventManager {
		use error;

		protected $_listeners = array(); //listeners
		protected $_events    = array(); //events
		
		/**
		 * constructor
		 * @access public
		 * @since 3.0
		 * @package system\Event
		*/

		public function __construct() {
			$this->_listeners = $GLOBALS['eventListeners'];
		}

		/**
		 * add an event to he pile
		 * @access public
		 * @param event $event : object
		 * @return void
		 * @since 3.0
		 * @package system\Event
		*/

		public function add($event) {	
			$this->_events[$event->getName()] = $event;
		}

		/**
		 * destroy an event
		 * @access public
		 * @param string $name
		 * @return bool
		 * @since 3.0
		 * @package system\Event
		*/

		public function destroy($name) {	
			if(isset($this->_events[$name])){
				unset($this->_events[$name]);
				$this->_events = array_values($this->_events);
			}
		}

		/**
		 * call listeners
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Event
		*/

		public function dispatch() {	
			foreach ($this->_listeners as $key => $listeners) {
				foreach ($this->_events as $events) {
					if(isset($listeners->implementedEvents()[$events->getName()])){
						foreach ($listeners->implementedEvents()[$events->getName()] as $event) {
							if($events->getStatus() == event::START && method_exists($listeners, $event)){
								ob_start();
									$this->addError('EVENT call the listener "'.$key.'" "'.get_class($listeners).'::'.$event.'" for the event "'.$events->getName().'"', __FILE__, __LINE__, ERROR_INFORMATION);
									$events->setResult($listeners->$event($events), get_class($listeners).'::'.$event, get_class($listeners), $event);
									$output = ob_get_contents();
								ob_get_clean();

								$this->addError('['.$key.'] ['.get_class($listeners).'::'.$event.'] ['.$events->getName()."]\n[".$output.']', 0, 0, 0, LOG_EVENT);
							}
						}
					}
				}
			}
		}

		/**
		 * get result returned by the listener
		 * @access public
		 * @param string $name : name of the event. If it's empty, get results of all events
		 * @return array
		 * @since 3.0
		 * @package system\Event
		*/

		public function getResult($name = '') {
			$result = array();

			if($name != ''){
				if(isset($this->_events[$name])){
					return $this->_events[$name]->getResult();
				}
				else{
					return false;
				}
			}
			else{
				foreach ($this->_events as $events) {
					$result[$events->getName()] =  $events->getResult();
				}

				return $result;
			}
		}

		/**
		 * set the status
		 * @access public
		 * @param string $name : name of the event
		 * @param $status int
		 * @return bool
		 * @since 3.0
		 * @package system\Event
		*/

		public function setStatus($name = '', $status = self::START) {
			if($name != ''){
				if(isset($this->_events[$name])){
					$this->_events[$name]->setStatus($status);

					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		/**
		 * get the status of the event
		 * @access public
		 * @param $name string : name of the event
		 * @return mixed
		 * @since 3.0
		 * @package system\Event
		*/

		public function getStatus($name = ''){
			if($name != ''){
				if(isset($this->_events[$name])){
					return $this->_events[$name]->getStatus();
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		/**
		 * Destructor
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Event
		*/
		
		public function __destruct(){
		}	
	}