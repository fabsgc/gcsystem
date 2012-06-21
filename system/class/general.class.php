<?php
	/*\
	 | ------------------------------------------------------
	 | @file : general.class.php
	 | @author : fab@c++
	 | @description : interface
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    interface general{
		public function setErrorLog($file, $message);
		public function sendMail($email, $message_html, $sujet, $envoyeur);
		public function windowInfo($Title, $Content, $Time, $Redirect);
		public function BlockInfo($Title, $Content, $Time, $Redirect);
    }