<?php
	namespace event{
		class myEvent implements \system\eventListener{
			public function implementedEvents(){
				return array(
					'myEvent' => array('myEventFirst', 'myEventSecond'),
					'myEvent2' => array('myEventFirst', 'myEventSecond'),
				);
			}

			public function myEventFirst($event){
				return 'salut1';
			}

			public function myEventSecond($event){
				return 'hello';
			}
		}
	}