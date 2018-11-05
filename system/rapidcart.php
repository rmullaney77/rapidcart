<?php

define('RC_VERSION', '0.0.0.1');

error_reporting(E_ALL);

if (version_compare(PHP_VERSION, '5.6.0', '<')) {
	exit('PHP 5.6+ required!');
}

if ( ! extension_loaded('mbstring')) {
	exit('PHP mbstring extension required!');
}

mb_internal_encoding('UTF-8');

if ( ! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

if ( ! isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	} elseif (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if ( ! isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
	
	if (isset($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

if ( ! isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

$_SERVER['HTTPS'] = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');

function modification($filename) {
	if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
		$file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
	} elseif (defined('DIR_CATALOG')) {
		$file = DIR_MODIFICATION . 'admin/' .  substr($filename, strlen(DIR_APPLICATION));
	} elseif (defined('DIR_RAPIDCART')) {
		$file = DIR_MODIFICATION . 'install/' .  substr($filename, strlen(DIR_APPLICATION));
	} else {
		$file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
	}

	if (is_file($file)) {
		return $file;
	}

	return $filename;
}

if (is_file(DIR_STORAGE . 'vendor/autoload.php')) {
	require_once(DIR_STORAGE . 'vendor/autoload.php');
}

spl_autoload_register(function($class) {
	$file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';
	
	if (is_file($file)) {
		include_once modification($file);

		return true;
	}
	
	return false;
});

require_once modification(DIR_SYSTEM . 'rapidcart/framework.php');
