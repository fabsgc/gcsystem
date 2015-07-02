{{php: $var='<?php
	namespace '.$src.';

	use System\Model\Model;

	class Manager'.ucfirst($model).' extends Model{
	}';
}}
{$var}