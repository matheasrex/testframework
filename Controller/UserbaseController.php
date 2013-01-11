<?

namespace Controller;

use Framework\Controller;

/**
 * userbase
 * handling login and unauthorized pages
 */
class UserbaseController extends Controller
{
	/**
	 * function to handle login
	 *
	 * @return array
	 */
	public function loginAction()
	{
		if ($this->request->getMethod() !== 'POST') {
			if ($this->request->session->get('user_data')) {
				$this->redirect('/');
			}
			
			return array();
		}
		$captcha = new \Framework\Captcha($this->request);
		if (!$captcha->validate()) {
			return array(
				'returnCode' => 401,
				'error' => $this->translate('captchaFailed'),
			);
		}	
		$userData = $this->entityManager->find(
			'userbase',
			array(
				'login' => $this->request->request->get('login', ''),
				'password' => $this->request->request->get('password', ''),
			)
		);
		if ($userData) {
			$this->request->session->set('user_data', $userData);
			
			$this->redirect($this->request->query->get('returnto', '/'));
		}
		
		return array(
			'returnCode' => 401,
			'error' => $this->translate('failedAuthorization'),
		);
	}
	/**
	 * unauthorized page (if user is logged in but has no rigth for a page)
	 *
	 * @return array
	 */
	public function restrictedAction()
	{
		return array();
	}
	/**
	 * unauthorized page (if user is logged in but has no rigth for a page)
	 *
	 * @return array
	 */
	public function logoutAction()
	{
		$this->request->session->clear();
		
		return array();
	}
}
