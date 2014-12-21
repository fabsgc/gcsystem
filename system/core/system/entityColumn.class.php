<?php
	/*\
	 | ------------------------------------------------------
	 | @file : entityColumn.class.php
	 | @author : fab@c++
	 | @description : ORM du GCsystem
	 | @version : 2.4 bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class entityColumn {
			use error;

			protected $_name    =      '';
			protected $_options = array();
			protected $_value   =      '';

			/**
			 * Constructeur de la classe
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */

			public function __construct() {
				$this->_options['autoincrement'] = false;
			}

			/**
			 * modifie le nom du champs
			 * @param : array $options
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */

			public function setName($name) {
				$this->_name = $name;
			}

			/**
			 * modifie la valeur du champs
			 * @param : array $options
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */

			public function setValue($value) {
				$this->_value = $value;
			}

			/**
			 * modifie les options de la colonne :
			 * 		- autoincrement : true/false
			 * @param : array $options
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */

			public function setOptions($options) {
				foreach($options as $key => $value){
					$this->_options[''.$key.''] = $value;
				}
			}

			/**
			 * retourne la valeur
			 * @access	public
			 * @return	string
			 * @since 2.4
			 */

			public function getValue() {
				return $this->_value;
			}

			/**
			 * retourne le nom
			 * @access	public
			 * @return	string
			 * @since 2.4
			 */

			public function getName() {
				return $this->_name;
			}

			/**
			 * retoure les options
			 * @access	public
			 * @return	array
			 * @since 2.4
			 */

			public function getOptions() {
				return $this->_options;
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