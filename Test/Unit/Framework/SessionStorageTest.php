<?

namespace Test\Unit\Framework;

use Framework\Session\SessionStorage;
use Framework\Session\SessionHandler;
use Test\Unit\SessionHandlerHelper;

require_once(dirname(__DIR__).'/SessionHandlerHelper.php');

/**
 * class to test SessionStorage class
 */ 
class SessionStorageTest extends SessionHandlerHelper
{
	/**
	 * start function will fail because session_start() would use cookie
	 * so we have to stub it
	 * although constructor needs a session handler, without it gives an invalidArgumentException
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testConstruct()
	{
		$this->getMock('\Framework\Session\SessionStorage');
	}
	/**
	 * try constructor with proper params
	 */
	public function testClear()
	{
		$storage = $this->getMockedStorage();
		$this->assertEquals(true, $storage->clear());
	}
	/**
	 * test add default values to session
	 */
	public function testAddSetRead()
	{
		$session = $this->getMockedStorage();
		$session->add(
			array(
				'firstData' => array(
					'firstValue' => 'hello',
					'secondValue' => 'world'
				),
				'secondData' => 1985,
				'thirdData' => 'this is a string'
			)
		);
		$addObject = new \stdClass();
		$addObject->randValue = 'absdef';
		$session->set('addedValue', $addObject);
		
		$this->assertTrue(is_numeric($session->get('secondData')));
		$this->assertEquals($session->get('thirdData'), 'this is a string');
		$this->assertCount(2, $session->get('firstData'));
		
		$getClass = $session->get('addedValue');
		$this->assertInstanceOf('\stdClass', $getClass);
		$this->assertEquals('absdef', $getClass->randValue);
		
		$this->assertCount(4, $session->get());
		
		$session->clear();
		$this->assertEmpty($session->get('secondData'));
		
		$this->assertCount(0, $session->get());
	}
	/**
	 * try to call not mocked session start
	 *
	 * @expectedException RuntimeException
	 */
	public function testSessionCreate()
	{
		$session = new SessionStorage($this->getHandler());
	}
}
