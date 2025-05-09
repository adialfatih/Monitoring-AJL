<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

	function filternama($input){
		$text = strip_tags(htmlspecialchars($input));
	    $filter = str_replace(array(':', '\\', '/', '*', '"', ';', '&', '=', '+', '|', '!', '?', '>', '<', ')', '(', ',', '.', '^', '%'. '$', '#', '`', '~'), '', $text);
	}

	function filter($input){
		$text = strip_tags(htmlspecialchars($input));
	    $filter = str_replace(array(':', '\\', '/', '*', '"', ';', '&', '=', '+', '|', '!', '?', '>', '<', ')', '(', ',', '.', '^', '%'. '$', '#', "'", '`', '~'), '', $text);
	}