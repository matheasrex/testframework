<?

namespace Test\Unit\Controller;

use Controller\UserbaseController;
use Test\Unit\RequestHelper;

require_once(dirname(__DIR__).'/RequestHelper.php');

/**
 * class to test captcha
 */ 
class UserbaseControllerTest extends RequestHelper
{
	/**
	 * try to call redirectedAction
	 */
	public function testRestricted()
	{
		$request = $this->getMockedRequest();
		$userbaseController = new UserbaseController($request);
		$this->assertCount(0, $userbaseController->restrictedAction());
	}
	/**
	 * try to call loginAction
	 */
	public function testLogin()
	{
		$request = $this->getMockedRequest();
		$userbaseController = new UserbaseController($request);
		$this->assertCount(0, $userbaseController->loginAction());
	}
	/**
	 * try to call loginAction with post method
	 */
	public function testLoginPost()
	{
		$request = $this->getMockedRequest();
		$request->server->set('REQUEST_METHOD', 'POST');
		$userbaseController = new UserbaseController($request);
		$result = $userbaseController->loginAction();
		$this->assertCount(2, $result);
		$this->assertEquals(401, $result['returnCode']);
	}
	/**
	 * try to call logoutAction
	 */
	public function testLogout()
	{
		$request = $this->getMockedRequest();
		$userbaseController = new UserbaseController($request);
		$this->assertCount(0, $userbaseController->logoutAction());
	}
}
