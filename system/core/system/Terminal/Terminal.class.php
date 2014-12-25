<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Terminal.class.php
	 | @author : fab@c++
	 | @description : terminal
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Terminal;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\General\resolve;
	use system\Database\Database;

    class Terminal{
		use error, facades, langs, resolve;

		protected $_argv = array();
		protected $_bdd           ;

		/**
		 * init terminal
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @since 3.0
		 * @package system\Terminal
		*/
		
		public function __construct(&$profiler, &$config, &$request, &$response, $lang){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_createlang();

			$this->_bdd = Database::connect($GLOBALS['db']);

			$this->_parseArg($_SERVER['argv']);

			if(isset($this->_argv[0]))
				$this->_command();
		}

		/**
		 * Parse terminal parameters to allow user to use spaces
		 * @access public
		 * @param $argv string
		 * @return void
		 * @since 3.0
		 * @package system\Terminal
		*/

		protected function _parseArg($argv){
			for($i = 0; $i < count($argv); $i++){
				if($argv[$i] != 'console'){
					if(!preg_match('#\[#', $argv[$i])){
						array_push($this->_argv, $argv[$i]);
					}
					else{
						$data = '';

						for($i = 0; $i < count($argv); $i++){
							$data .= $argv[$i].' ';

							if(preg_match('#\]#', $argv[$i])){
								$data = str_replace(array('[', ']'), array('', ''), $data);
								array_push($this->_argv, trim($data));
								break;
							}
						}
					}
				}
			}
		}

		/**
		 * Terminal interpreter
		 * @access public
		 * @internal param string $argv
		 * @return void
		 * @since 3.0
	 	 * @package system\Terminal
		 */

		protected function _command(){
			$class = '\system\Terminal\Terminal'.ucfirst($this->_argv[0]);

			if(isset($this->_argv[1])){
				$method = $this->_argv[1];
			}
			else if($this->_argv[0] == 'help'){
				$method = 'help';
			}
			else{
				$method = '';
			}

			if(method_exists($class, $method)){

				$instance = new $class($this->profiler, $this->config, $this->request, $this->response, $this->lang, $this->_bdd, $this->_argv);
				$instance->$method($this->_argv);
			}
			else{
				if(isset($this->_argv[1])){
					echo '[ERROR] unrecognized command "'.$this->_argv[0].' '.$this->_argv[1].'"';
				}
				else{
					echo '[ERROR] unrecognized command "'.$this->_argv[0].'"';
				}
			}
		}

		/**
		 * Remove directory content
		 * @access public
		 * @param $dir string : path
		 * @param $removeDir : remove subdirectories too
		 * @return void
		 * @since 3.0
		 * @package system\Terminal
		*/

		public static function rrmdir($dir, $removeDir = false) {
			if (is_dir($dir)) {
				$objects = scandir($dir);
					foreach ($objects as $object) {
						if ($object != "." && $object != "..") {
							if (filetype($dir."/".$object) == "dir"){
								terminal::rrmdir($dir."/".$object, $removeDir); 

								if($removeDir == true){
									rmdir($dir."/".$object.'/');
								}
							}
							else{
								unlink ($dir."/".$object);
							}
						}
					}
				reset($objects);
			}
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system\Terminal
		*/
		
		public function __destruct(){
		}
	}

	class TerminalCommand{
		use error, facades, langs, resolve;

		/**
		 * pdo instance
		 * @var
		*/

		protected $_bdd;

		/**
		 * command content
		 * @var
		*/

		protected $_argv;

		/**
		 * init terminal command
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $bdd
		 * @param $argv
		 * @since 3.0
	 	 * @package system\Terminal
		*/

		public function __construct(&$profiler, &$config, &$request, &$response, $lang, $bdd, $argv){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_bdd     =      $bdd;
			$this->_argv    =     $argv;
			$this->_createlang();
		}
	}

	class ArgvInput{
		public static function get(){
			$data = fgets(STDIN);
			$data = substr($data, 0, -2);

			return $data;
		}
	}