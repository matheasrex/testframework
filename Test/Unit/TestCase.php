<?

namespace Test\Unit;

ini_set('include_path',ini_get('include_path').':'.dirname(dirname(dirname(__FILE__))).'/');

if (!defined('ERROR_LOG_PATH')) {
	define('ERROR_LOG_PATH', __DIR__.'/../../Tmp/log/');
}
if (!defined('CONFIG_FILE')) {
	define('CONFIG_FILE', __DIR__.'../../../Config/config.php');
}

require_once('Framework/ClassLoader.php');
$loader = new \Framework\ClassLoader();
$loader->register();

/**
 * customized tester class
 */ 
class TestCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var array $config config object
	 */
	protected $config;
	/**
	 * @var string $configPath config object path
	 */
	protected $configPath;
	/**
	 * global constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->configPath = CONFIG_FILE;
		$this->config = require_once($this->configPath);
	}
}