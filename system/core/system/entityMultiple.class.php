<?php
	/*\
	 | ------------------------------------------------------
	 | @file : entityMultiple.class.php
	 | @author : fab@c++
	 | @description : ORM du GCsystem : permet de manipuler des requêtes sur plusieurs tables sans les fonctions CRUD
	 | @version : 2.4 bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class entityMultiple {
			use error;

			protected $_data   = array();

			public function __construct($data = array()) {
				$this->_data = $data;
			}

			/**
			 * permet de modifier un des champs de la table
			 * @access	public
			 * @param string $key
			 * @param array $value
			 * @return	void
			 * @since 2.4
			 */
			public function __set($key, $value) {
				if(array_key_exists($key, $this->_data)){
					$this->_data[''.$key.''] = $value;
				}
			}

			/**
			 * permet de modifier un des champs de la table
			 * @access	public
			 * @param $key string
			 * @return	string
			 * @since 2.4
			 */
			public function get($key) {
				if(array_key_exists($key, $this->_data)){
					return $this->_data[''.$key.''];
				}
			}

			/**
			 * permet de modifier un des champs de la table
			 * @access	public
			 * @param string $key
			 * @param array $value
			 * @return	void
			 * @since 2.4
			 */
			public function set($key, $value) {
				if(array_key_exists($key, $this->_data)){
					$this->_data[''.$key.''] = $value;
				}
			}

			/**
			 * permet de modifier un des champs de la table
			 * @access	public
			 * @param $key string
			 * @return	string
			 * @since 2.4
			 */
			public function __get($key) {
				if(array_key_exists($key, $this->_data)){
					return $this->_data[''.$key.''];
				}
			}

			/**
			 * récupère les données
			 * @access	public
			 * @return	array
			 * @since 2.4
			 */
			public function toArray(){
				return $this->_data;
			}

			/**
			 * modifie les données
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */
			public function setData($data = array()){
				$this->_data = $data;
			}

			/**
			 * Destructeur
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */

			public function __destruct(){
			}
		}
	}