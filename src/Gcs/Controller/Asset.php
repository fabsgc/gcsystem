<?php
namespace Src\Gcs\Controller;

use Gcs\Framework\Core\Cache\Cache;
use Gcs\Framework\Core\Config\Config;
use Gcs\Framework\Core\Controller\Controller;
use Gcs\Framework\Core\Response\Response;

/**
 * Class Asset
 * @package Src\Gcs\Controller
 * @Before(class="\Src\Gcs\Controller\Asset", method="init")
 */

class Asset extends Controller {

    /**
     * @Routing(name="gcs.asset.default", url="/gcs/asset/(.[^\/]+)\.([css|js]+)", vars="id,type", method="get")
     * @return mixed
     */

    public function actionDefault() {
        if ($_GET['type'] == 'js' || $_GET['type'] == 'css') {
            Response::instance()->contentType("text/" . $_GET['type']);
            Response::instance()->header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));

            $cache = new Cache(html_entity_decode($_GET['id'] . '.' . $_GET['type']), Config::config()['user']['output']['asset']['cache']);

            return $cache->getCache();
        }
        else {
            Response::instance()->status(404);
        }

        return null;
    }
}