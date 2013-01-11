<?

namespace Test\Unit\Framework;

use Framework\Session\SessionHandler;
use Test\Unit\SessionHandlerHelper;

require_once(dirname(__DIR__).'/SessionHandlerHelper.php');

/**
 * class to test SessionHandler class
 */ 
class SessionHandlerTest extends SessionHandlerHelper
{
	/**
	 * session handler constructor tester
	 */
	public function testSessionHander()
	{
		$memcachedClient = new \Framework\MemcachedClient(
			$this->memcacheConfig['persistentId'], 
			$this->memcacheConfig['options']
		);
		$this->assertInstanceOf('\Memcached', $memcachedClient->getInstance());

		$sessionHandler = new SessionHandler($memcachedClient->getInstance(), $this->options);
		$this->assertInstanceOf('\Framework\Session\SessionHandler', $sessionHandler);
	}
	/**
	 * session handler constructor tester
	 */
	public function testSessionHander2()
	{
		$sessionHandler = $this->getHandler();
		$this->assertInstanceOf('\Framework\Session\SessionHandler', $sessionHandler);
	}
	/**
	 * session open function tester
	 */
	public function testOpen()
	{
		$sessionHandler = $this->getHandler();
		$this->assertTrue($sessionHandler->open('', ''));
	}
	/**
	 * session close function tester
	 */
	public function testClose()
	{
		$sessionHandler = $this->getHandler();
		$this->assertTrue($sessionHandler->close());
	}
	/**
	 * session write and read function tester
	 */
	public function testWriteReadArray()
	{
		$sessionHandler = $this->getHandler();
		$this->assertTrue($sessionHandler->write('myTestSessionId', array('data' => 'testDataInSession')));
		$readBack = $sessionHandler->read('myTestSessionId');
		$this->assertCount(1, $readBack);
		$this->assertEquals('testDataInSession', $readBack['data']);
	}
	/**
	 * session write and read function tester
	 */
	public function testWriteReadObject()
	{
		$sessionHandler = $this->getHandler();
		$testObj = new \stdClass();
		$testObj->data = 'testObjectInSession';
	
		$sessionHandler->write('myTestSessionId', $testObj);
		$readBack = $sessionHandler->read('myTestSessionId');
		$this->assertInstanceOf('\stdClass', $readBack);
		$this->assertEquals('testObjectInSession', $readBack->data);
	}
	/**
	 * session gc function tester
	 */
	public function testGc()
	{
		$sessionHandler = $this->getHandler();
		$this->assertTrue($sessionHandler->gc(3600));
	}
	/**
	 * session destroy function tester
	 */
	public function testDestroy()
	{
		$sessionHandler = $this->getHandler();
		$this->assertTrue($sessionHandler->destroy('myTestSessionId'));
		
	}
}
