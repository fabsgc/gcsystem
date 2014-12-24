<?php
	/*\
	 | ------------------------------------------------------
	 | @file : TerminalHelp.class.php
	 | @author : fab@c++
	 | @description : terminal command help
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Terminal;

	class TerminalHelp extends TerminalCommand{
		public function help(){
			echo " - create module\n";
			echo " - create controller\n";
			echo " - create entity\n";
			echo " - delete module\n";
			echo " - delete controller\n";
			echo " - clear cache\n";
			echo " - clear log";
		}
	}