<?php
	namespace gcs;

	class gcs extends \system\controller{		
		public function init(){
			if(ENVIRONMENT != 'development'){
				$this->response->status(404);
			}
		}

		public function end(){
		}
		
		public function actionDefault(){
			/*$this->event->add(new \system\event('sendNotifDefault', $this, ''));
			$this->event->add(new \system\event('sendMailDefault', $this, ''));
			$this->event->dispatch();*/

			/*$sql = $this->sql($this->bdd);

			$sql->query('test', 'SELECT * FROM test', 2);
			print_r($sql->fetch('test'));*/

			//print_r($this->config->config);

			//echo $this->getUrl('gcs.assetManager', array('id1', 'css'));

			//var_dump($this->resolve(RESOLVE_CSS, '.app'));
			//var_dump($this->resolve(RESOLVE_CSS, '.app/font/test.css'));

			//print_r($this->config);

			$tpl = $this->template('gcs/profiler', 'profiler');
			$tpl->assign('test', $this);
			$tpl->assign('test2', 'salut');
			return $tpl->show();

			/*echo $this->getUrl('gcs.profiler');
			echo "<br />";
			echo $this->useLang('system.http.title', array('code' => '404'));

			echo '<form action="" method="POST">';
			echo '<input type="text" name="token[gcs]" />';
			echo '<input type="submit" value="envoyer"/>';
			echo '</form>';

			$table = $this->entity->keeplove_user_user();

			$table = new \entity\keeplove_user_user($this->profiler, $this->config, $this->request, $this->response, $this->lang, $this->bdd);

			//$_SESSION['logged']['gcs'] = true;
			//$_SESSION['role']['gcs'] = 'USER';
			//$_SESSION['token']['gcs'] = 'sdfsdfqsdf';

			//print_r($this->config->config);

			//print_r(get_included_files());

			$this->response->status(200);*/

			/*$dir = ".";

			$nb = counter($dir);
			print("<br />Le projet comporte un total de <strong>".$nb."</strong> lignes<br />\n");*/
		}

		public function actionLang(){
		}

		public function actionProfiler(){
		}

		public function actionAssetManager(){
		}
	}

	function counter($dir)
	{
		$handle = opendir($dir);

		$nbLines = 0;

		while( ($file = readdir($handle)) != false )
		{
			if( $file != "." && $file != ".." && $file != ".idea")
			{
				if( !is_dir($dir."/".$file) )
				{
					if( preg_match("#\.(php|tpl|xml)$#", $file) )
					{
						$nb = count(file($dir."/".$file));
						echo $dir,"/",$file," => <strong>",$nb,"</strong><br />n";
						$nbLines += $nb;
					}
				}
				else
				{
					$nbLines += counter($dir."/".$file);
				}
			}
		}
		closedir($handle);

		return $nbLines;
	}