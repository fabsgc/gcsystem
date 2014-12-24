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

	class TerminalCreate extends TerminalCommand{
		public function module(){
			$src = '';
			$controllers = array();

			//choose the module name
			while(1==1){
				echo ' - choose module name : ';
				$src = argvInput::get(STDIN);

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

			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'cron.xml', $tpl['cron']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'define.xml', $tpl['define']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'firewall.xml', $tpl['firewall']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'library.xml', $tpl['library']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'route.xml', '');

			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LANG_PATH.'fr.xml', $tpl['lang']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));

			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_FUNCTION_PATH, '');

			$routeGroup = '';

			foreach ($controllers as $value) {
				$tpl['routeGroup'] = $this->template('.app/system/module/routeGroup', 'terminalCreateRouteGroup'.$value);
				$tpl['routeGroup']->assign(array('src' => $src, 'controller' => $value));
				$routeGroup .= $tpl['routeGroup']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING);

				$tpl['controller'] = $this->template('.app/system/module/controller', 'terminalCreateController'.$value);
				$tpl['controller']->assign(array('src' => $src, 'controller' => ucfirst($value)));
				$tpl['model'] = $this->template('.app/system/module/model', 'terminalCreateModel'.$value);
				$tpl['model']->assign(array('src' => $src, 'model' => ucfirst($value)));

				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH.ucfirst($value).EXT_CONTROLLER.'.php', $tpl['controller']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_MODEL_PATH.ucfirst($value).EXT_MODEL.'.php',  $tpl['model']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
			}

			$tpl['route']->assign('route', $routeGroup);
			file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'route.xml', $tpl['route']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));

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
			
		}

		public function entity(){
			if(DATABASE){
				while(1==1){
					echo ' - choose a table (*) : ';
					$table = argvInput::get(STDIN);

					if($table != ''){
						$sql = $this->sql($this->_bdd);
						$sql->query('add-entity', 'SHOW TABLES FROM '.$this->_bdd->getDatabase());
						$data = $sql->fetch('add-entity', sql::PARAM_FETCH);

						foreach($data as $value){
							terminal::addEntity($this->_bdd->getDatabase(), $value[0]);
						}
					}
				}

				echo ' - the entity has been successfully created';
			}
			else{
				echo ' - you\'re not logged to any database';
			}
		}
	}