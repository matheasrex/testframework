<?

namespace Test\Unit\Framework;

use Framework\RequestItem;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test RequestItem class
 */ 
class RequestItemTest extends TestCase
{
	/**
	 * positive test: check if RequestItem is loadable
	 */
	public function testLoadClass()
	{
		$this->assertInstanceOf('\Framework\RequestItem', new RequestItem(array()));
	}
	/**
	 * tests: 
	 ** create object with defaults, 
	 ** set new data
	 ** get egisting and non egzisting defaultset and other data
	 */
	public function testValue()
	{
		$requestItem = new RequestItem(
			array(
				'testData' => '<b>test</b>',
				'testArray' => array(
					'1',
					'12'
				)
			)
		);
		$requestItem->set('newData', new \stdClass);
		$this->assertEquals($requestItem->get('testData'), 'test');
		$this->assertCount(2, $requestItem->get('testArray'));
		$this->assertNotEquals($requestItem->get('testData', '', true), 'test');
		$this->assertEmpty($requestItem->get('test.nonegzisting.data'));
		$this->assertInstanceOf('\stdClass', $requestItem->get('newData'));
	}
}
