<?

namespace Test\Unit\Framework;

use Framework\Templating;
use Framework\Configuration;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test Templating class
 */ 
class TemplatingTest extends TestCase
{
	/**
	 * constructor test
	 */
	public function testConstruct()
	{
		$config = new Configuration($this->configPath);
		$this->assertInstanceOf('\Framework\Templating', new Templating($config));
	}
	/**
	 * assign test
	 */
	public function testAssign()
	{
		$config = new Configuration($this->configPath);
		$tpl = new Templating($config);
		
		$this->assertNull($tpl->assign('variable', $tpl));
	}
	/**
	 * assign test array
	 */
	public function testAssignArray()
	{
		$config = new Configuration($this->configPath);
		$tpl = new Templating($config);
		
		$this->assertNull($tpl->assign(array('newVariable' => $config)));
	}
	/**
	 * display test
	 */
	public function testDisplay()
	{
		$config = new Configuration($this->configPath);
		$tpl = new Templating($config);
		ob_start();
		$tpl->display('main/index.tpl');
		$data = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(strlen($data) > 250);
	}
	/**
	 * fetch test
	 */
	public function testFetch()
	{
		$config = new Configuration($this->configPath);
		$tpl = new Templating($config);
		$data = $tpl->fetch('main/index.tpl');
		$this->assertTrue(strlen($data) > 250);
	}
	/**
	 * page test
	 */
	public function testPage()
	{
		$config = new Configuration($this->configPath);
		$tpl = new Templating($config);
		try {
			$tpl->page('main/index.tpl');
		} catch (\Exception $e) {
			$this->assertTrue(strpos($e->getMessage(), 'Cannot modify header information') !== false);
		}
	}
	/**
	 * page test fallback
	 */
	public function testPageFallback()
	{
		$config = new Configuration($this->configPath);
		$config->set('templating.layout', '');
		$tpl = new Templating($config);
		ob_start();
		$tpl->page('main/index.tpl');
		$data = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(strlen($data) > 250);
	}
}
