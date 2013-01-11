<?

namespace Controller;

use Framework\Controller;

/**
 * controller to create and show capcha
 */
class CaptchaController extends Controller
{
	/**
	 * showAction to show capcha
	 */
	public function showAction()
	{
		$captcha = new \Framework\Captcha($this->request);
		$image = $captcha->draw();
		$captcha->output($image);
	}
}
