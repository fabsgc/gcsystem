{$php}


    namespace {ucfirst($src)};

    use System\Controller\Controller;

    /**
     * Class Index
     * @package Gcs
     * @Before(class="\{ucfirst($src)}\{ucfirst($controller)}", method="init")
     */

    class {ucfirst($controller)} extends Controller{

        /**
         * @Routing(name="default", url="/'.{lcfirst($src)}.'/default(/*)", method="*")
         */

        public function actionDefault(){
            return $this->showDefault();
        }
    }