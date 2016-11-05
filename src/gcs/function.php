<?php
	function printArray($a, $n = 0) {
		if (!is_array($a)) {
			$n = 0;
			echo $a . "</li>";
			return;
		}

		foreach ($a as $k => $value) {
			if ($n != 0) {
				echo '<ul>';
			}
			if ($k < 10) {
				echo '<li><strong>' . $k . '</strong> : ';
				printArray($value, $n + 1);
			}
			if ($n != 0) {
				echo '</ul>';
			}
		}
	}

	function getPhpArraySyntax($array) {
		$data = 'array(';

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$data .= getPhpArraySyntax($value) . ',';
			}
			else {
				$data .= '"' . $key . '" => "' . str_replace('"', '\"', $value) . '",' . "\n";
			}
		}

		return trim($data, ',');
	}

	function getFunctionArgNames($function = []) {
		$f = new \ReflectionFunction($function);
		$result = [];

		foreach ($f->getParameters() as $param) {
			$result[] = $param->name;
		}

		return $result;
	}