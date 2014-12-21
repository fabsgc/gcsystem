<?php
	/*\
	 | ------------------------------------------------------
	 | @file : pdo.class.php
	 | @author : fab@c++
	 | @description : surchage de pdo permettant de conserver des informations sur la connexion
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class pdo extends \PDO{
			protected $_host     = '';
			protected $_username = '';
			protected $_password = '';
			protected $_database = '';

			public function __construct($dsn, $username , $password, $driver_options){
				$this->_host = preg_replace('#([a-zA-Z0-9_]*):host=([a-zA-Z_0-9]*);dbname=([a-zA-Z0-9_]*)#i', '$2', $dsn);
				$this->_database = preg_replace('#([a-zA-Z0-9_]*):host=([a-zA-Z_0-9]*);dbname=([a-zA-Z0-9_]*)#i', '$3', $dsn);
				$this->_username = $username;
				$this->_password = $password;

				parent::__construct($dsn, $username , $password, $driver_options);
			}

			public function getHost(){
				return $this->_host;
			}

			public function getUsername(){
				return $this->_username;
			}

			public function getPassword(){
				return $this->_password;
			}

			public function getDatabase(){
				return $this->_database;
			}
		}
	}