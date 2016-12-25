{$php}

	namespace {ucfirst($src)};

	use System\Controller\Controller;

	/**
	 * Class Index
	 * @package {ucfirst($src)}

	 * @Before(class="\{ucfirst($src)}\{ucfirst($controller)}", method="init")
	 */

	class {ucfirst($controller)} extends Controller{

		/**
		 * @Routing(name="{lcfirst($src)}-{strtolower($controller)}-default", url="/{lcfirst($src)}/{strtolower($controller)}/default(/*)", method="*")
		 */

		public function actionDefault(){
			return $this->showDefault();
		}
	}