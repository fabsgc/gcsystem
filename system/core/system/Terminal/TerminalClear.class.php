<?php
	/*\
	 | ------------------------------------------------------
	 | @file : TerminalClear.class.php
	 | @author : fab@c++
	 | @description : terminal command clear
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Terminal;

	class TerminalClear extends TerminalCommand{
		public function log(){
			terminal::rrmdir(APP_LOG_PATH);
			echo ' - log files were successfully deleted';
		}

		public function cache(){
			terminal::rrmdir(APP_CACHE_PATH);
			echo ' - cache files were successfully deleted';
		}
	}