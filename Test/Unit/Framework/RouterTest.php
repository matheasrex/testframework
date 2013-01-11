<?

namespace Test\Unit\Framework;

use Framework\Router;
use Test\Unit\RequestHelper;

require_once(dirname(__DIR__).'/RequestHelper.php');

/**
 * class to test Router class
 */ 
class RouterTest extends RequestHelper
{
	/**
	 * test construct
	 */
	public function testConstruct()
	{
		$request = $this->getMockedRequest();
		$router = new Router($request);
		$this->assertInstanceOf('\Framework\Router', $router);
	}
	/**
	 * test handle nonegzisting route
	 *
	 * @expectedException RuntimeException
	 */
	public function testHandleNonegzistingRoute()
	{
		$request = $this->getMockedRequest();
		$request->server->set('REQUEST_URI', '/test/invalid');
		$router = new Router($request);
		$router->handle();
	}
	/**
	 * test handle main page
	 *
	 * @expectedException RuntimeException
	 */
	public function testHandleEgzistingClassNondefinedFunction()
	{
		$request = $this->getMockedRequest();
		$request->server->set('REQUEST_URI', '/main/invalid-route/');
		$router = new Router($request);
		$router->handle();
	}
	/**
	 * test handle main page
	 */
	public function testHandleMainpageRoute()
	{
		$request = $this->getMockedRequest();
		$request->server->set('REQUEST_URI', '/');
		$router = new Router($request);
		$this->assertInstanceOf('\Framework\Response', $router->handle());
	}
}
