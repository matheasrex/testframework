<?

namespace Test\Controller\Framework;

use Framework\Request;
use Framework\Configuration;
use Controller\MainController;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test modifications of header
 */ 
class HeaderModificationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * nonlogged redirect supposed
	 *
	 * @runInSeparateProcess
	 */
	public function testFirstControllerNotlogged()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$mainController = new MainController($request);
		set_exit_overload(function() { return false; });
		$mainController->firstAction();
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertContains('location:/login/?returnto='.urlencode($request->server->get('REQUEST_URI')), $headerList);
	}
	/**
	 * nonauthorized redirect supposed
	 *
	 * @runInSeparateProcess
	 */
	public function testFirstControllerNonauthorized()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 0;
		$request->session->set('user_data', $userData);
		$mainController = new MainController($request);
		set_exit_overload(function() { return false; });
		$mainController->firstAction();
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertContains('location:/unauthorized/', $headerList);
	}
	/**
	 * nonlogged redirect supposed
	 *
	 * @runInSeparateProcess
	 */
	public function testSecondController()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 3;
		$request->session->set('user_data', $userData);
		$mainController = new MainController($request);
		set_exit_overload(function() { return false; });
		$return = $mainController->secondAction();
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertCount(0, $return);
	}
	/**
	 * Captcha test
	 *
	 * @runInSeparateProcess
	 */
	public function testCaptchaController()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$captchaController = new \Controller\CaptchaController($request);
		set_exit_overload(function() { return false; });
		$captchaController->showAction();
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertContains('Content-type: image/png', $headerList);
		$this->assertTrue(strlen($request->session->get('capcha_code')) > 0);
	}
	/**
	 * try to call loginAction with valid captcha
	 *
	 * @runInSeparateProcess
	 */
	public function testLoginPost()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		
		$captchaController = new \Controller\CaptchaController($request);
		set_exit_overload(function() { return false; });
		$captchaController->showAction();
		unset_exit_overload();
		
		$request->server->set('REQUEST_METHOD', 'POST');
		$request->request->set('capcha_code', $request->session->get('capcha_code'));
		$this->assertTrue(strlen($request->request->get('capcha_code')) > 2);
		$request->request->set('login', 'testuser1');
		$request->request->set('password', 'test1user');
		$userbaseController = new \Controller\UserbaseController($request);
		set_exit_overload(function() { return false; });
		$result = $userbaseController->loginAction();
		unset_exit_overload();
		$this->assertCount(2, $result);
		$this->assertEquals(401, $result['returnCode']);
	}
	/**
	 * try to call loginAction is user is logged
	 *
	 * @runInSeparateProcess
	 */
	public function testLoginGetLogged()
	{
		$request = new Request(new Configuration(CONFIG_FILE));
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 3;
		$request->session->set('user_data', $userData);
		$userbaseController = new \Controller\UserbaseController($request);
		set_exit_overload(function() { return false; });
		$result = $userbaseController->loginAction();
		unset_exit_overload();
		$headerList = xdebug_get_headers();
		$this->assertCount(0, $result);
	}
	/**
	 * test testOutput function
	 *
	 * @expectedException RuntimeException
	 */
	public function testTestFirstControllerNotlogged()
	{
		$this->testFirstControllerNotlogged();
	}
}
