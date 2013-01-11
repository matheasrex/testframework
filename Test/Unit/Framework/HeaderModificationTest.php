<?

namespace Test\Unit\Framework;

use Framework\Request;
use Framework\Configuration;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test modifications of header
 */ 
class HeaderModificationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * lets try to output captcha image
	 *
	 * @runInSeparateProcess
	 */
	public function testOutput()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$captcha = new \Framework\Captcha($request);
		$image = $captcha->draw();
		set_exit_overload(function() { return false; });
		$result = $captcha->output($image);
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertContains('Content-type: image/png', $headerList);
	}
	/**
	 * lets try to redirect page
	 *
	 * @runInSeparateProcess
	 */
	public function testRedirect()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$controller = new \Framework\Controller($request);
		set_exit_overload(function() { return false; });
		$result = $controller->redirect('foo');
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertContains('location:foo', $headerList);
	}
	/**
	 * lets try call a nonegzisting route
	 *
	 * @runInSeparateProcess
	 */
	public function testFailRoute()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$request->server->set('REQUEST_URI', '/test/invalid');
		$router = new \Framework\Router($request);
		set_exit_overload(function() { return false; });
		$router->handle();
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertContains('location:/404', $headerList);
	}
	/**
	 * lets try to show a page
	 *
	 * @runInSeparateProcess
	 */
	public function testShowPage()
	{
		$config = new Configuration(CONFIG_FILE);
		$tpl = new \Framework\Templating($config);
		$tpl->page('main/index.tpl');
		$headerList = xdebug_get_headers();
		$this->assertContains('Content-Type: text/html; charset=utf-8', $headerList);
	}
	/**
	 * lets try to render a response
	 *
	 * @runInSeparateProcess
	 */
	public function testResponseMainPage()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$request->server->set('REQUEST_URI', '/');
		$response = new \Framework\Response($request, array('template' => 'main/index'));
		$response->send();
		$headerList = xdebug_get_headers();
		$this->assertContains('Content-Type: text/html; charset=utf-8', $headerList);
	}
	/**
	 * lets try to render a 404 response
	 *
	 * @runInSeparateProcess
	 */
	public function testResponseNotFoundPage()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$request->server->set('REQUEST_URI', '/404');
		$response = new \Framework\Response(
			$request, 
			array(
				'template' => 'main/index',
				'returnCode' => 404,
			)
		);
		$response->send();
		$headerList = xdebug_get_headers();
		$this->assertContains('404 Not Found', $headerList);
	}
	/**
	 * lets try to render mainPage with missconfigured config
	 *
	 * @runInSeparateProcess
	 */
	public function testResponseReconfiguredMainPage()
	{
		$config = new Configuration(CONFIG_FILE);
		$toAssign = $config->get('response.default.assignable');
		$toAssign['test'] = $toAssign['userData'];
		$toAssign['test']['from'] = 'request';
		$config->set('response.default.assignable', $toAssign);
		$request = new Request($config);
		$userData = new \stdClass();
		$userData->login = 'TestUser';
		$request->session->set('user_data', $userData);
		
		$request->server->set('REQUEST_URI', '/');
		$response = new \Framework\Response(
			$request, 
			array(
				'template' => 'main/index',
			)
		);
		$response->send();
		$headerList = xdebug_get_headers();
		$this->assertContains('Content-Type: text/html; charset=utf-8', $headerList);
	}
	/**
	 * test testOutput function
	 *
	 * @expectedException RuntimeException
	 */
	public function testTestOutput()
	{
		$this->testOutput();
	}
}
