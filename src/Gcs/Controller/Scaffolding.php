<?php
namespace Src\Gcs\Controller;

use Gcs\Framework\Core\Config\Config;
use Gcs\Framework\Core\Response\Response;

/**
 * Class Scaffolding
 * @package Src\Gcs\Controller
 * @Before(class="\Src\Gcs\Controller\Scaffolding", method="init")
 */

class Scaffolding extends \Gcs\Scaffolding\Scaffolding {

    public function init() {
        if (Config::config()['user']['debug']['environment'] != 'development') {
            Response::instance()->status(404);
        }
    }
}