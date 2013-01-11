<?

namespace Test\Unit\Framework;

use Framework\Configuration;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test Configurator class
 */ 
class ConfigurationTest extends TestCase
{
	/**
	 * positive test: check if config is loadable
	 */
	public function testLoadConfig()
	{
		$this->assertInstanceOf('\Framework\Configuration', new Configuration($this->configPath));
	}
	/**
	 * negative test: try to load it with wrong path
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testLoadWrongConfig()
	{
		$config = new Configuration('../wrong/path');
	}
	/**
	 * positive test: get an egzisting key
	 */
	public function testConfigValue()
	{
		$config = new Configuration($this->configPath);
		$this->assertEquals($config->get('templating.layout'), 'layout.tpl');
	}
	/**
	 * negative test: get a nonegzisting key
	 */
	public function testConfigNonegzistingValue()
	{
		$config = new Configuration($this->configPath);
		$this->assertEmpty($config->get('nonegzisting.key'));
	}
	/**
	 * positive test: set a value
	 */
	public function testConfigSetNewValue()
	{
		$config = new Configuration($this->configPath);
		$config->set('nonegzisting.key.test', 'test');
		$this->assertEquals($config->get('nonegzisting.key.test'), 'test');
	}
}
