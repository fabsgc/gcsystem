<?php
	/*\
	 | ------------------------------------------------------
	 | @file : pdo.class.php
	 | @author : fab@c++
	 | @description : override PDO to keep connection informations
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Pdo;

	class Pdo extends \PDO{
		protected $_host     = '';
		protected $_username = '';
		protected $_password = '';
		protected $_database = '';

		/**
		 * constructor
		 * @access public
		 * @param $dsn string
		 * @param $username string
		 * @param $password string
		 * @param $driverOptions string
		 * @since 3.0
		 * @package system\Pdo
		*/

		public function __construct($dsn, $username , $password, $driverOptions){
			$this->_host = preg_replace('#([a-zA-Z0-9_]*):host=([a-zA-Z_0-9]*);dbname=([a-zA-Z0-9_]*)#i', '$2', $dsn);
			$this->_database = preg_replace('#([a-zA-Z0-9_]*):host=([a-zA-Z_0-9]*);dbname=([a-zA-Z0-9_]*)#i', '$3', $dsn);
			$this->_username = $username;
			$this->_password = $password;

			parent::__construct($dsn, $username , $password, $driverOptions);
		}

		/**
		 * get SQL host
		 * @return string
		 * @since 3.0
		 * @package system\Pdo
		*/

		public function getHost(){
			return $this->_host;
		}

		/**
		 * get SQL username
		 * @return string
		 * @since 3.0
		 * @package system\Pdo
		*/

		public function getUsername(){
			return $this->_username;
		}

		/**
		 * get SQL password
		 * @return string
		 * @since 3.0
		 * @package system\Pdo
		*/

		public function getPassword(){
			return $this->_password;
		}

		/**
		 * get SQL database name
		 * @return string
		 * @since 3.0
		 * @package system\Pdo
		*/

		public function getDatabase(){
			return $this->_database;
		}
	}
