<?php
	namespace app\event;

	class mail implements \system\eventListener{
		public function implementedEvents(){
			return array(
				'sendMailDefault' => array('sendMailDefault')
			);
		}

		public function sendMailDefault($event){
			if(empty($event->data['sender'])){
				$event->data['sender'] = array('My Great Dating', 'contact@mygreatdating.com');
			}
		}
	}