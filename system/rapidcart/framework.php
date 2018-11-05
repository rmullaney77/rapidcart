<?php

require_once modification(DIR_SYSTEM . 'rapidcart/action.php');
require_once modification(DIR_SYSTEM . 'rapidcart/controller.php');
require_once modification(DIR_SYSTEM . 'rapidcart/event.php');
require_once modification(DIR_SYSTEM . 'rapidcart/router.php');
require_once modification(DIR_SYSTEM . 'rapidcart/loader.php');
require_once modification(DIR_SYSTEM . 'rapidcart/model.php');
require_once modification(DIR_SYSTEM . 'rapidcart/registry.php');
require_once modification(DIR_SYSTEM . 'rapidcart/proxy.php');

$registry = new Registry();

if (empty($app)) {
	if (defined('DIR_RAPIDCART')) {
		$app = 'install';
	} elseif (defined('DIR_CATALOG')) {
		$app = 'admin';
	} else {
		$app = 'catalog';
	}
}

$config = new Config();
$config->load('default');
$config->load($app);
$registry->set('config', $config);

date_default_timezone_set($config->get('date_timezone'));

$log = new Log($config->get('error_filename'));
$registry->set('log', $log);

set_error_handler(function($code, $message, $file, $line) use($log, $config) {
	if (error_reporting() === 0) {
		return false;
	}

	switch ($code) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	if ($config->get('error_display')) {
		echo sprintf('<b>%s</b>: %s in <b>%s</b> on line <b>%s</b>', $error, $message, $file, $line);
	}

	if ($config->get('error_log')) {
		$log->write(sprintf('PHP %s: $s in %s on line %s', $error, $message, $file, $line));
	}

	return true;
});

$event = new Event($registry);
$registry->set('event', $event);

if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		foreach ($value as $priority => $action) {
			$event->register($key, new Action($action), $priority);
		}
	}
}

$loader = new Loader($registry);
$registry->set('load', $loader);

$registry->set('request', new Request());

$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

$registry->set('db', new DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'), $config->get('db_driver')));

$session = new Session($config->get('session_engine'), $registry);
$registry->set('session', $session);
$session_id = (empty($_COOKIE[$config->get('session_name')])) ? '' : $_COOKIE[$config->get('session_name')];
$session->start($session_id);
setcookie($config->get('session_name'), $session->getId(), ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));

$registry->set('cache', new Cache($config->get('cache_engine'), $config->get('cache_expire')));

$registry->set('url', new Url($config->get('site_url'), $config->get('site_ssl')));

$language = new Language($config->get('language_directory'));
$registry->set('language', $language);

$registry->set('document', new Document());

if ($config->has('config_autoload')) {
	foreach ($config->get('config_autoload') as $value) {
		$loader->config($value);
	}
}

if ($config->has('language_autoload')) {
	foreach ($config->get('language_autoload') as $value) {
		$loader->language($value);
	}
}

if ($config->has('library_autoload')) {
	foreach ($config->get('library_autoload') as $value) {
		$loader->library($value);
	}
}

if ($config->has('model_autoload')) {
	foreach ($config->get('model_autoload') as $value) {
		$loader->model($value);
	}
}

$route = new Router($registry);

if ($config->has('action_pre_action')) {
	foreach ($config->get('action_pre_action') as $value) {
		$route->addPreAction(new Action($value));
	}
}

$route->dispatch(new Action($config->get('action_router')), new Action($config->get('action_error')));

$response->output();
