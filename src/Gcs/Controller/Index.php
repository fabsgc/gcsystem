<?php
namespace Src\Gcs\Controller;

use Gcs\Framework\Core\Config\Config;
use Gcs\Framework\Core\Controller\Controller;
use Gcs\Framework\Core\Response\Response;
use Gcs\Framework\Core\Template\Template;

/**
 * Class Index
 * @package Gcs
 * @Before(class="\Src\Gcs\Controller\Index", method="init")
 * @After(class="\Src\Gcs\Controller\Index", method="end")
 */

class Index extends Controller {

    public function init() {
        if (Config::config()['user']['debug']['environment'] != 'development') {
            Response::instance()->status(404);
        }
    }

    /**
     * @Routing(name="index", url="(/*)", method="get,post,put")
     */

    public function actionDefault() {
        return (new Template('index/default', 'gcsDefault'))
            ->assign('title', 'GCsystem V' . VERSION)
            ->show();
    }
}