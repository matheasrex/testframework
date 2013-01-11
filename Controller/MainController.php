<?

namespace Controller;

use Framework\Controller;

/**
 * main controller
 * handling page1 and 2 in this case
 */
class MainController extends Controller
{
	/**
	 * function to handle main page
	 *
	 * @return array
	 */
	public function indexAction()
	{
		return array();
	}
	/**
	 * function to handle page1 action
	 *
	 * @return array
	 */
	public function firstAction()
	{
		$this->login(\Config\AuthenticationLevel::LEVEL_1);
		
		return array();
	}
	/**
	 * function to handle page2 action
	 *
	 * @return array
	 */
	public function secondAction()
	{
		$this->login(\Config\AuthenticationLevel::LEVEL_2);
		
		return array();
	}
	/**
	 * render 404 page
	 *
	 * @return array
	 */
	public function notFoundAction()
	{
		return array(
			'returnCode' => 404,
		);
	}
	/**
	 * protected function to check whether 
	 * user is logged in or not
	 * if this function would be needed in other 
	 * controllers, it could be moved to Controller class
	 * or to a middle class
	 *
	 * @param int $authenticationLevel level of authentication
	 *
	 * @return bool or redirect
	 */
	protected function login($authenticationLevel)
	{
		if ($this->request->session->get('user_data')) {
			$this->authenticate($authenticationLevel);
			
			return true;
		}
		
		$this->redirect('/login/?returnto='.urlencode($this->request->server->get('REQUEST_URI')));
	}
	/**
	 * protected function to authenticate wether
	 * user ha right to see the certain page or not
	 *
	 * @param int $authenticationLevel Level of authentication
	 *
	 * @return bool or redirect
	 */
	protected function authenticate($authenticationLevel)
	{
		$userData = $this->request->session->get('user_data');
		if ($userData->right & $authenticationLevel) {
			return true;
		}
		
		$this->redirect('/unauthorized/');
	}
}
