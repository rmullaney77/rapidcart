<?php

$protocol = (empty($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'off' ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'];

define('HTTP_RAPIDCART', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/install.\\') . '/');
define('HTTP_APPLICATION', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');

unset($host);

define('DIR_RAPIDCART', str_replace('\\', '/', realpath(__DIR__ . '/../')) . '/');
define('DIR_APPLICATION', str_replace('\\', '/', realpath(__DIR__ . '/')) . '/');

define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_IMAGE', DIR_RAPIDCART . 'image/');
define('DIR_SYSTEM', DIR_RAPIDCART . 'system/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_DATABASE', DIR_SYSTEM . 'database/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOG', DIR_STORAGE . 'log/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

require DIR_SYSTEM . 'rapidcart.php';
