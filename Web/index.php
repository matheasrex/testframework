<?
/**
 * this file is responsible for
 * * autoloader
 * * finding proper route
 * * handle request
 * * return response
 */
error_reporting(E_ALL);
ini_set('include_path',ini_get('include_path').':'.dirname(dirname(__FILE__)).'/');

require_once('Framework/ClassLoader.php');
$loader = new Framework\ClassLoader();
$loader->register();

if (!defined('ERROR_LOG_PATH')) {
	define('ERROR_LOG_PATH', __DIR__.'/../Tmp/log/');
}
if (!defined('CONFIG_FILE')) {
	define('CONFIG_FILE', __DIR__.'../../Config/config.php');
}

register_shutdown_function(array('\Framework\ErrorHandler', 'handleFatal'));
set_error_handler(array('\Framework\ErrorHandler', 'handleError'));
set_exception_handler(array('\Framework\ErrorHandler', 'handleException'));

$configuration = new Framework\Configuration(CONFIG_FILE);

$request = new Framework\Request($configuration);

$router = new Framework\Router($request);

$response = $router->handle();

$response->send();