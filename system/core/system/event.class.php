<?php
	/*\
	 | ------------------------------------------------------
	 | @file : event.class.php
	 | @author : fab@c++
	 | @description : class permettant l'utilisation du design pattern observer
	 | @version : 2.4 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class event {
			use error;

			public $parent              ; //référence vers l'objet qui a lancé l'évènement
			public $data       =    null; //données supplémentaires passables à l'évènement

			protected $_name            ; //nom de l'evènement qui est lancé
			protected $_status =    true; //statut de l'évènement : arrêté ou en marche
			protected $_result = array(); //données de retour de l'évènement après chacun de ses appels

			const START = true;
			const STOP = false;
			
			/**
			 * Constructeur de la classe
			 * @access	public
			 * @param $name string : nom de l'évènement
			 * @param $parent object : l'objet parent
			 * @param $data array : les données passées
			 * @since 2.3
			*/

			public function __construct($name = 'event', $parent = null, $data = null) {
				$this->_name = $name;
				$this->data = $data;
				$this->parent = $parent;
			}

			/**
			 * Récupère le nom de l'évènement créé
			 * @access	public
			 * @return	string
			 * @since 2.3
			*/

			public function getName(){
				return $this->_name;
			}

			/**
			 * Récupère le statut de l'évènement (true en cours ou false arrêté)
			 * @access	public
			 * @return	bool
			 * @since 2.3
			*/

			public function getStatus(){
				return $this->_status;
			}

			/**
			 * Récupère le résultat renvoyé par les écouteurs de l'évènement
			 * @access	public
			 * @return	array
			 * @since 2.3
			*/

			public function getResult(){
				return $this->_result;
			}

			/**
			 * Modifie le nom de l'évènement
			 * @access	public
			 * @param string $name
			 * @return void
			 * @since 2.3
			*/

			public function setName($name = 'event'){
				$this->_name = $name;
			}

			/**
			 * Modifie le statut de l'évènement (true en cours ou false arrêté)
			 * @access	public
			 * @param $status
			 * @return void
			 * @since 2.3
			*/

			public function setStatus($status = self::START){
				$this->_status = $status;
			}

			/**
			 * Modifie le résultat retourné par l'écouteur (fonction utilisée par le FW)
			 * @access	public
			 * @param $result string : résultat à retourner par l'écouteur
			 * @param int $index : numéro de l'event dans l'ordre (0,1,2,3)
			 * @param string $class : nom de la classe appelée
			 * @param string $function : nom de la fonction appelée
			 * @return void
			 * @since 2.3
			*/

			public function setResult($result = '', $index, $class, $function){
				$this->_result[$index] = array('class' => $class, 'function' => $function, 'return' => $result);
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