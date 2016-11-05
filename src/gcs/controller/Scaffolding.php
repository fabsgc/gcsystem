<?php
	namespace Gcs;

	use System\Config\Config;
	use System\Response\Response;

	/**
	 * Class Scaffolding
	 * @package Gcs
	 * @Before(class="\Gcs\Scaffolding", method="init")
	 */

	class Scaffolding extends \Scaffolding\Scaffolding {

		public function init() {
			if (Config::config()['user']['debug']['environment'] != 'development') {
				Response::instance()->status(404);
			}
		}
	}