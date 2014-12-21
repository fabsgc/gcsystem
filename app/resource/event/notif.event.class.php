<?php
	namespace app\event;

	class notif implements \system\eventListener{
		public function implementedEvents(){
			return array(
				'sendNotifDefault' => array('sendNotifDefault')
			);
		}

		public function sendNotifDefault($event){
			//addNotification($event->parent->bdd, $event->data['message'], $event->data['user']);
		}
	}