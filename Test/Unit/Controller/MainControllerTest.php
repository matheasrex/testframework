<?

namespace Test\Unit\Controller;

use Controller\MainController;
use Test\Unit\RequestHelper;

require_once(dirname(__DIR__).'/RequestHelper.php');

/**
 * class to test captcha
 */ 
class MainControllerTest extends RequestHelper
{
	/**
	 * try to call indexAction
	 */
	public function testIndex()
	{
		$request = $this->getMockedRequest();
		$mainController = new MainController($request);
		$this->assertCount(0, $mainController->indexAction());
	}
	/**
	 * try to call firstAction - supposed to redirect
	 *
	 * @expectedException RuntimeException
	 */
	public function testFirst()
	{
		$request = $this->getMockedRequest();
		$mainController = new MainController($request);
		$mainController->firstAction();
	}
	/**
	 * try to call secondAction - supposed to redirect
	 *
	 * @expectedException RuntimeException
	 */
	public function testSecond()
	{
		$request = $this->getMockedRequest();
		$mainController = new MainController($request);
		$mainController->secondAction();
	}
	/**
	 * try to call firstAction with mocked session data
	 *
	 * @expectedException RuntimeException
	 */
	public function testFirstLogged()
	{
		$request = $this->getMockedRequest();
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 0;
		$request->session->set('user_data', $userData);
		$mainController = new MainController($request);
		$mainController->firstAction();
	}
	/**
	 * try to call firstAction with mocked session data
	 *
	 * @expectedException RuntimeException
	 */
	public function testFirstLoggedSecondUser()
	{
		$request = $this->getMockedRequest();
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 2;
		$request->session->set('user_data', $userData);
		$mainController = new MainController($request);
		$mainController->firstAction();
	}
	/**
	 * try to call firstAction with mocked session data
	 */
	public function testFirstLoggedThirdUser()
	{
		$request = $this->getMockedRequest();
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 3;
		$request->session->set('user_data', $userData);
		$mainController = new MainController($request);
		$this->assertCount(0, $mainController->firstAction());
		$this->assertCount(0, $mainController->secondAction());
	}
	/**
	 * try to call firstAction with mocked session data 2
	 */
	public function testFirstLoggedAuthorized()
	{
		$request = $this->getMockedRequest();
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 1;
		$request->session->set('user_data', $userData);
		$mainController = new MainController($request);
		$this->assertCount(0, $mainController->firstAction());
	}
	/**
	 * try to call firstAction with mocked session data 2
	 */
	public function testSecondLoggedAuthorized()
	{
		$request = $this->getMockedRequest();
		$userData = new \stdClass();
		$userData->login = 'testUser';
		$userData->right = 2;
		$request->session->set('user_data', $userData);
		$mainController = new MainController($request);
		$this->assertCount(0, $mainController->secondAction());
	}
	/**
	 * try to call 404 page
	 */
	public function testNotFound()
	{
		$request = $this->getMockedRequest();
		$mainController = new MainController($request);
		$returnData = $mainController->notFoundAction();
		$this->assertCount(1, $returnData);
		$this->assertEquals(404, $returnData['returnCode']);
	}
}
