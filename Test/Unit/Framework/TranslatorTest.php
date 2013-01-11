<?

namespace Test\Unit\Framework;

use Framework\Translator;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test translator
 */ 
class TranslatorTest extends TestCase
{
	/**
	 * @var \Framework\Translator $translator Translator object
	 */
	protected $translator;
	/**
	 * @var string $translatePath Translate path
	 */
	protected $translatePath;
	
	/**
	 * setUp function
	 */
	protected function setUp()
	{
		parent::setUp();
		$config = new \Framework\Configuration(CONFIG_FILE);
		$this->translatePath = $config->get('translator.path');
		$this->translator = new Translator($this->translatePath);
	}
	
	/**
	 * positive test: check the translation of failedAuthorization
	 */
	public function testTranslate()
	{
		$translator = new Translator($this->translatePath);
		$this->assertGreaterThan(
			15,
			strlen($translator->translate('failedAuthorization', 'common'))
		);
	}
	/**
     * positive test: try to translate a nonexisting key
	 * output is #label#
     */
	public function testNotrans()
	{
		$this->assertEquals('#testNoTrans#', $this->translator->translate('testNoTrans', 'common'));
	}
	/**
     * @expectedException PHPUnit_Framework_Error
     */
	public function testNonexistingFile()
	{
		$this->translator->translate('test', 'nonexisting.test');
	}
	/**
     * @expectedException PHPUnit_Framework_Error
     */
	public function testNonexistingPath()
	{
		$translator = new Translator('Resource/Notranslation/');
		$translator->translate('test', 'common');
	}
	
}
