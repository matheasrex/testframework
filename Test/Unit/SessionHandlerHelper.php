<?

namespace Test\Unit;

use Framework\Session\SessionHandler;
use Test\Unit\TestCase;

require_once(__DIR__.'/TestCase.php');

/**
 * Helper class to test SessionHandler related classes
 */ 
class SessionHandlerHelper extends TestCase
{
	/**
	 * @var array $memcacheConfig Memcache config
	 */
	protected $memcacheConfig;
	/**
	 * @var array $options Session.handler.options
	 */
	protected $options;
	/**
	 * @var \Framework\Session\SessionHandler $sessionHandler Session handler object
	 */
	protected $sessionHandler;
	
	/**
	 * set up configuration
	 */
	protected function setUp()
	{
		$configuration = new \Framework\Configuration($this->configPath);
		$this->memcacheConfig = $configuration->get('memcache.params');
		$this->options = $configuration->get('session.handler.options');
	}
	/**
	 * function to provide session handler instance to functions
	 *
	 * @return array 
	 */
	protected function getHandler()
	{
		if ($this->sessionHandler instanceof \Framework\Session\SessionHandler) {
			return $this->sessionHandler;
		}
		return $this->sessionHandler = new SessionHandler($this->memcacheConfig, $this->options);
	}
	/**
	 * get mocked storage
	 *
	 * @return \Framewok\Session\Storage
	 */
	protected function getMockedStorage()
	{
		$sessionHandler = $this->getHandler();
		$storage = $this->getMock(
			'\Framework\Session\SessionStorage', 
			array('start'),
			array('handler' => $sessionHandler)
		);
		
		$storage->expects($this->any())
			->method('start')
			->will($this->returnValue(true));
		
		return $storage;
	}
}