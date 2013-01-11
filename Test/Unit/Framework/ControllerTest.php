<?

namespace Test\Unit\Framework;

use Framework\Controller;
use Test\Unit\RequestHelper;

require_once(dirname(__DIR__).'/RequestHelper.php');

/**
 * class to test Controller class
 */ 
class ControllerTest extends RequestHelper
{
	/**
	 * constructor test
	 */
	public function testConstruct()
	{
		$request = $this->getMockedRequest();
		$this->assertInstanceOf('\Framework\Controller', new Controller($request));
	}
	/**
	 * try to redirect
	 *
	 * @expectedException RuntimeException
	 */
	public function testRedirect()
	{
		$request = $this->getMockedRequest();
		$controller = new Controller($request);
		$controller->redirect('foo');
	}
	/**
	 * test translator function of controller
	 */
	public function testTranslator()
	{
		$request = $this->getMockedRequest();
		$controller = new Controller($request);
		$this->assertEquals('#foo#', $controller->translate('foo'));
	}
}
