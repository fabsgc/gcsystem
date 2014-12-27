<?php
	/*\
	 | ------------------------------------------------------
	 | @file : TerminalCreate.class.php
	 | @author : fab@c++
	 | @description : terminal command create
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Terminal;

	use system\Sql\Sql;
	use system\Template\Template;

	class TerminalCreate extends TerminalCommand{
		public function module(){
			$src = '';
			$controllers = array();

			//choose the module name
			while(1==1){
				echo ' - choose module name : ';
				$src = ArgvInput::get(STDIN);

				if(!file_exists(DOCUMENT_ROOT.SRC_PATH.$src.'/')){
					break;
				}
				else{
					echo "[ERROR] this module already exists\n";
				}
			}

			//choose the number of controllers
			while(1==1){
				echo ' - add a controller (keep empty to stop) : ';
				$controller = argvInput::get(STDIN);
					
				if($controller != ''){
					if(!in_array($controller, $controllers)){
						array_push($controllers, $controller);
					}
					else{
						echo "[ERROR] you have already chosen this controller\n";
					}
				}
				else{
					if(count($controllers) > 0){
						break;
					}
					else{
						echo "[ERROR] you must add at least one controller\n";
					}
				}
			}

			//load all template to fill the new files
			$tpl['cron'] = $this->template('.app/system/module/cron', 'terminalCreateCron');
			$tpl['define'] = $this->template('.app/system/module/define', 'terminalCreateDefine');
			$tpl['lang'] = $this->template('.app/system/module/lang', 'terminalCreateLang');
			$tpl['library'] = $this->template('.app/system/module/library', 'terminalCreateLibrary');
			$tpl['route'] = $this->template('.app/system/module/route', 'terminalCreateRoute');
			$tpl['firewall'] = $this->template('.app/system/module/firewall', 'terminalCreateFirewall');
			$tpl['firewall']->assign('src', $src);

			//creation of directories and files
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_MODEL_PATH);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_PATH);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_EVENT_PATH);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LANG_PATH);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LIBRARY_PATH);
			mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_TEMPLATE_PATH);

			mkdir(DOCUMENT_ROOT.WEB_PATH.$src);
			mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_CSS_PATH);
			mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_FILE_PATH);
			mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_IMAGE_PATH);
			mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_JS_PATH);

			file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_CSS_PATH.'/index.html', '');
			file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_FILE_PATH.'/index.html', '');
			file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_IMAGE_PATH.'/index.html', '');
			file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_JS_PATH.'/index.html', '');

			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/.htaccess', 'Deny from all');
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_EVENT_PATH.'.htaccess', 'Deny from all');
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LIBRARY_PATH.'.htaccess', 'Deny from all');
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LIBRARY_PATH.'.htaccess', 'Deny from all');
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_TEMPLATE_PATH.'.htaccess', 'Deny from all');

			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'cron.xml', $tpl['cron']->show());
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'define.xml', $tpl['define']->show());
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'firewall.xml', $tpl['firewall']->show());
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'library.xml', $tpl['library']->show());
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'route.xml', '');

			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LANG_PATH.'fr.xml', $tpl['lang']->show());

			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_FUNCTION_PATH, '');

			$routeGroup = '';

			foreach ($controllers as $value) {
				$tpl['routeGroup'] = $this->template('.app/system/module/routeGroup', 'terminalCreateRouteGroup'.$value);
				$tpl['routeGroup']->assign(array('src' => $src, 'controller' => $value));
				$routeGroup .= $tpl['routeGroup']->show();

				$tpl['controller'] = $this->template('.app/system/module/controller', 'terminalCreateController'.$value);
				$tpl['controller']->assign(array('src' => $src, 'controller' => ucfirst($value)));
				$tpl['model'] = $this->template('.app/system/module/model', 'terminalCreateModel'.$value);
				$tpl['model']->assign(array('src' => $src, 'model' => ucfirst($value)));

				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH.ucfirst($value).EXT_CONTROLLER.'.php', $tpl['controller']->show());
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_MODEL_PATH.ucfirst($value).EXT_MODEL.'.php',  $tpl['model']->show());
			}

			$tpl['route']->assign('route', $routeGroup);
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'route.xml', $tpl['route']->show());

			$exist = false;
			$xml = simplexml_load_file(APP_CONFIG_SRC);
			$datas =  $xml->xpath('//src');

			foreach ($datas as $data) {
				if($data['name'] == $src)
					$exist = true;
			}

			if($exist == false){
				$node = $xml->addChild('src', null);
				$node->addAttribute('name', $src);

				$dom = new \DOMDocument("1.0");
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				$dom->loadXML($xml->asXML());
				$dom->save(APP_CONFIG_SRC);
			}

			echo ' - the module has been successfully created';
		}

		public function controller(){
			$src = '';
			$controllers = array();

			//choose the module name
			while(1==1){
				echo ' - choose a module : ';
				$src = ArgvInput::get(STDIN);

				if(file_exists(DOCUMENT_ROOT.SRC_PATH.$src.'/')){
					break;
				}
				else{
					echo " - [ERROR] this module doesn't exist\n";
				}
			}

			//choose the controllers
			while(1==1){
				echo ' - add a controller (keep empty to stop) : ';
				$controller = argvInput::get(STDIN);
					
				if($controller != ''){
					if(!in_array($controller, $controllers) AND !file_exists(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH.'/'.ucfirst($controller).EXT_CONTROLLER.'.php')){
						array_push($controllers, $controller);
					}
					else{
						echo "[ERROR] you have already chosen this controller or it's already created.\n";
					}
				}
				else{
					if(count($controllers) > 0){
						break;
					}
					else{
						echo "[ERROR] you must add at least one controller\n";
					}
				}
			}

			foreach ($controllers as $value) {
				$tpl['controller'] = $this->template('.app/system/module/controller', 'terminalCreateController'.$value);
				$tpl['controller']->assign(array('src' => $src, 'controller' => ucfirst($value)));
				$tpl['model'] = $this->template('.app/system/module/model', 'terminalCreateModel'.$value);
				$tpl['model']->assign(array('src' => $src, 'model' => ucfirst($value)));

				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH.ucfirst($value).EXT_CONTROLLER.'.php', $tpl['controller']->show(Template::TPL_COMPILE_ALL, Template::TPL_COMPILE_TO_STRING));
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_MODEL_PATH.ucfirst($value).EXT_MODEL.'.php',  $tpl['model']->show(Template::TPL_COMPILE_ALL, Template::TPL_COMPILE_TO_STRING));
				
				echo " - the controller ".$value." have been successfully created";
			}
		}

		public function entity(){
			$table = '';

			if(DATABASE){
				while(1==1){
					echo ' - choose a table (*) : ';
					$table = ArgvInput::get(STDIN);

					if($table != ''){
						break;
					}
					else{
						$table = ArgvInput::get(STDIN);
					}
				}

				if($table != '*'){
					TerminalCreate::addEntity($table);
				}
				else{
					$sql = $this->sql($this->_bdd);
					$sql->query('add-entity', 'SHOW TABLES FROM '.$this->_bdd->getDatabase());
					$data = $sql->fetch('add-entity', sql::PARAM_FETCH);

					foreach($data as $value){
						$this->addEntity($value[0]);
					}
				}
			}
			else{
				echo ' - you\'re not logged to any database';
			}
		}

		/**
		 * Create Entity
		 * @access public
		 * @param $table string
		 * @return string
		 * @since 3.0
		 * @package system\Terminal
		*/

		private function addEntity($table) {
			if(file_exists(APP_RESOURCE_ENTITY_PATH.$table.EXT_ENTITY.'.php')){
				unlink(APP_RESOURCE_ENTITY_PATH.$table.EXT_ENTITY.'.php');
			}

			$sql = $this->sql($this->_bdd);
			$sql->query('add-entity', 'SELECT COLUMN_NAME, EXTRA, COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :bdd AND TABLE_NAME = :table');
			$sql->setVar(array('bdd' => $this->_bdd->getDatabase()));
			$sql->setVar(array('table' => $table));

			$column = '';

			foreach($sql->fetch('add-entity', Sql::PARAM_FETCH) as $value){
				$options = '';

				if($value['EXTRA'] == 'auto_increment')
					$options .= "'autoincrement' => true,";

				if($value['COLUMN_KEY'] == 'PRI')
					$options .= "'primary' => true,";

				$options = preg_replace('#,$#isU', '', $options);
				$column.= '			$this->addColumn(\''.$value['COLUMN_NAME'].'\', array('.$options.'));'."\n";
			}

			if($column != ''){
				$t = $this->template('.app/system/module/entity', 'gcsEntity_'.$table, '0');
				$t->assign(array('class'=> $table, 'column'=> $column));
				file_put_contents(APP_RESOURCE_ENTITY_PATH.$table.EXT_ENTITY.'.php', $t->show(Template::TPL_COMPILE_ALL, Template::TPL_COMPILE_TO_STRING));

				echo ' - the entity "'.$table.'" has been successfully created'."\n";
			}
			else{
				echo ' - the table "'.$table.'" does\'t exist';
			}
		}
	}