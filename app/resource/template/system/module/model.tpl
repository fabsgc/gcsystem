{{php: $var='<?php
	namespace '.ucfirst($src).';

	use System\Model\Model;

	class Manager'.ucfirst($model).' extends Model{
	}';
}}
{$var}