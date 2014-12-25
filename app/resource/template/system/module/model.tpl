{{php: $var='<?php
	namespace '.$src.';

	use system\Model\Model;

	class Manager'.ucfirst($model).' extends Model{
		public function init(){
		}
	}';
}}
{$var}