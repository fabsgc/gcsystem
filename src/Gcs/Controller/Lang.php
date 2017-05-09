<?php
namespace Src\Gcs\Controller;

use Gcs\Framework\Core\Config\Config;
use Gcs\Framework\Core\Controller\Controller;
use Gcs\Framework\Core\Response\Response;

/**
 * Class Lang
 * @package Gcs
 * @Before(class="\Src\Gcs\Controller\Lang", method="init")
 */

class Lang extends Controller {

    public function init() {
        if (Config::config()['user']['debug']['environment'] != 'development') {
            Response::instance()->status(404);
        }
    }

    /**
     * @Routing(name="gcs.lang.default", url="/gcs/lang(/*)", method="get")
     * @return mixed
     */

    public function actionDefault() {
        return $this->showDefault();
    }
}