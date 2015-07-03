<?php
	namespace gcs;

	class Scaffolding extends \Scaffolding\Scaffolding{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
	}