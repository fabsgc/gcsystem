<?php
	/**
	 * @file : eventManager .class.php
	 * @author : fab@c++
	 * @description : class permettant l'utilisation du design pattern observer
	 * @version : 2.3 bêta
	*/
	
	namespace system{
		class eventManager {
			use error;

			protected $_listeners = array(); //tous les écouteurs
			protected $_events    = array(); //tous les évènements de la pile
			
			/**
			 * Constructeur de la classe
			 * @access	public
			 * @return	void
			 * @since 2.3
			*/

			public function __construct() {
				$this->_listeners = $GLOBALS['eventListeners'];
			}

			/**
			 * ajoute un nouvel évènement à la pile
			 * @access	public
			 * @param event $event : objet event
			 * @return	void
			 * @since 2.3
			*/

			public function add($event) {	
				$this->_events[$event->getName()] = $event;
			}

			/**
			 * détruit un évènement
			 * @access	public
			 * @param string $name : nom de l'évènement à arrêter
			 * @return	bool
			 * @since 2.3
			*/

			public function destroy($name) {	
				if(isset($this->_events[$name])){
					unset($this->_events[$name]);
					$this->_events = array_values($this->_events);
				}
			}

			/**
			 * Apppelle tous les écouteurs
			 * @access	public
			 * @return	void
			 * @since 2.3
			*/

			public function dispatch() {	
				foreach ($this->_listeners as $listeners) {
					foreach ($this->_events as $events) {
						if(isset($listeners->implementedEvents()[$events->getName()])){
							foreach ($listeners->implementedEvents()[$events->getName()] as $event) {
								if($events->getStatus() == event::START && method_exists($listeners, $event)){
									ob_start("ob_gzhandler");
										$this->_addError('EVENT : lancement de l\'écouteur "'.get_class($listeners).'::'.$event.'" de l\'événement "'.$events->getName(), __FILE__, __LINE__, INFORMATION);
										$events->setResult($listeners->$event($events), get_class($listeners).'::'.$event, get_class($listeners), $event);
									ob_get_clean();
								}
							}
						}
					}
				}
			}

			/**
			 * Récupère les résultats retournés les écouteurs d'un évènement
			 * @access	public
			 * @param string $name : nom de l'évènement dont on veut récupérer le résultat. Si vide, on récupère les résultats de tous les évènements
			 * @return	array
			 * @since 2.3
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
			 * Modifie le statut d'un évènement
			 * @access public
			 * @param string $name : nom de l'évènement dont on veut changer le statut
			 * @param int $status
			 * @return bool
			 * @since 2.3
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
			 * récupère le statut d'un évènement
			 * @access public
			 * @param string $name : nom de l'évènement dont on veut changer le statut
			 * @return int|bool
			 * @since 2.3
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
			 * Destructeur
			 * @access	public
			 * @return	void
			 * @since 2.3
			*/
			
			public function __destruct(){
			}	
		}
	}