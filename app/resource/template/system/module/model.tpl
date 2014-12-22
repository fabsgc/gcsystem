{{php: $var='<?php
	namespace '.$src.';

	class manager'.ucfirst($model).' extends \system\model{
		public function init(){
		}
	}';
}}
{$var}