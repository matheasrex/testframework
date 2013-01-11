<?

namespace Test\Unit\Framework;

use Framework\Request;
use Test\Unit\RequestHelper;

require_once(dirname(__DIR__).'/RequestHelper.php');

/**
 * class to test Request class
 */ 
class RequestTest extends RequestHelper
{
	/**
	 * probably session start will fail by default.
	 *
	 * @expectedException RuntimeException
	 */
	public function testConstruct()
	{
		new Request(new \Framework\Configuration($this->configPath));
	}
	/**
	 * lets mock to success test
	 */
	public function testConstructMock()
	{
		$request = $this->getMockedRequest();
		
		$this->assertInstanceOf('\Framework\Request', $request);
	}
	/**
	 * if config is missconfugured, invalidArgumentException is expected
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testConstructWrongConfig()
	{
		$config = new \Framework\Configuration($this->configPath);
		$config->set('session.handler.class', '');
		$this->assertInstanceOf('\Framework\Request', new Request($config));
	}
	/**
	 * test of getMethod function
	 */
	public function testGetMethod()
	{
		$request = $this->getMockedRequest();
		
		$this->assertEquals('GET', $request->getMethod());
		
		$request->server->set('REQUEST_METHOD', 'POST');
		$request->server->set('HTTP_X_HTTP_METHOD_OVERRIDE', 'PUT');
		$this->assertEquals('PUT', $request->getMethod());
		
		$request->server->set('HTTP_X_HTTP_METHOD_OVERRIDE', 'DELETE');
		$this->assertEquals('DELETE', $request->getMethod());
		
		$request->server->set('HTTP_X_HTTP_METHOD_OVERRIDE', 'INVALID');
		$this->assertEquals('POST', $request->getMethod());
	}
	/**
	 * test of session part of request
	 */
	public function testSetSession()
	{
		$request = $this->getMockedRequest();
		
		$request->session->set('testData', 'szevasz');
		$this->assertEquals('szevasz', $request->session->get('testData'));
	}
}
