<?php
    interface general{
		public function setErrorLog($file, $message);
		public function sendMail($email, $message_html, $sujet, $envoyeur);
		public function windowInfo($Title, $Content, $Time, $Redirect);
		public function BlockInfo($Title, $Content, $Time, $Redirect);
    }