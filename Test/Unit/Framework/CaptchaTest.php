<?

namespace Test\Unit\Framework;

use Framework\Captcha;
use Test\Unit\RequestHelper;

require_once(dirname(__DIR__).'/RequestHelper.php');

/**
 * class to test captcha
 */ 
class CaptchaTest extends RequestHelper
{
	/**
	 * positive test: try to create class
	 */
	public function testConstruct()
	{
		$request = $this->getMockedRequest();
		$this->assertInstanceOf('\Framework\Captcha', new Captcha($request));
	}
	/**
	 * Try to draw with nonegzisting font file
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testDrawWrongFontFile()
	{
		$request = $this->getMockedRequest();
		$config = $request->configuration->get('captcha.config', array());
		$config['font'] = 'times_new_roman_nonegzisting.ttf';
		$request->configuration->set('captcha.config', $config);
		$captcha = new Captcha($request);
		$captcha->draw();
	}
	/**
	 * Try to draw with shadow
	 */
	public function testDrawWithShadow()
	{
		$request = $this->getMockedRequest();
		$captcha = new Captcha($request);
		$this->assertTrue(is_resource($captcha->draw()));
	}
	/**
	 * Try to draw withouth shadow
	 */
	public function testDraw()
	{
		$request = $this->getMockedRequest();
		$config = $request->configuration->get('captcha.config', array());
		$config['shadow'] = false;
		$request->configuration->set('captcha.config', $config);
		$captcha = new Captcha($request);
		$this->assertTrue(is_resource($captcha->draw()));
	}
	/**
	 * Try to draw
	 */
	public function testDrawSixDigitColor()
	{
		$request = $this->getMockedRequest();
		$config = $request->configuration->get('captcha.config', array());
		$config['color'] = '#000000';
		$request->configuration->set('captcha.config', $config);
		$captcha = new Captcha($request);
		$this->assertTrue(is_resource($captcha->draw()));
	}
	/**
	 * Try to draw
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testDrawInvalidColor()
	{
		$request = $this->getMockedRequest();
		$config = $request->configuration->get('captcha.config', array());
		$config['color'] = '#00000';
		$request->configuration->set('captcha.config', $config);
		$captcha = new Captcha($request);
		$captcha->draw();
	}
	/**
	 * Try validate captcha
	 */
	public function testValidate()
	{
		$request = $this->getMockedRequest();
		$captcha = new Captcha($request);
		$this->assertFalse($captcha->validate());
	}
	/**
	 * Try validate captcha
	 */
	public function testValidate2()
	{
		$request = $this->getMockedRequest();
		$request->request->set('capcha_code', 'szevasz');
		$captcha = new Captcha($request);
		$this->assertFalse($captcha->validate());
	}
	/**
	 * lets try to output
	 */
	public function testOutput()
	{
		$request = $this->getMockedRequest();
		$captcha = new Captcha($request);
		$image = $captcha->draw();
		try {
			$captcha->output($image);
		} catch (\Exception $e) {
			$this->assertTrue(strpos($e->getMessage(), 'Cannot modify header information') !== false);
		}
	}
}
