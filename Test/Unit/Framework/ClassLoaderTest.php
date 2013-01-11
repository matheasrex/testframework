<?

namespace Test\Unit\Framework;

use Framework\ClassLoader;

/**
 * class to test translator
 */ 
class ClassLoaderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * positive test: find a file by classname
	 */
	public function testFindClass()
	{
		$classLoader = new ClassLoader();
		$this->assertEquals(
			$classLoader->findFile('Framework\Translator'),
			dirname(dirname(dirname(__DIR__))).'/Framework/Translator.php'
		);
	}
	/**
	 * positive test: try to load Translator class
	 */
	public function testLoadClass()
	{
		$classLoader = new ClassLoader();
		$this->assertTrue(
			$classLoader->loadClass('Framework\Translator')
		);
	}
	/**
	 * positive test: try to register load class
	 */
	public function testRegister()
	{
		$classLoader = new ClassLoader();
		$classLoader->register();
		$testClass = new \Framework\Translator('');
		$this->assertNotEmpty($testClass);
	}
	/**
	 * negative test: find a nonexisting file by classname
	 */
	public function testFindNonexistingClass()
	{
		$classLoader = new ClassLoader();
		$this->assertFalse(
			$classLoader->findFile('Framework\NonEgzistingTranslator')
		);
	}
	/**
	 * negative test: try to load nonegzigting class
	 */
	public function testLoadNonegzistingClass()
	{
		$classLoader = new ClassLoader();
		$this->assertFalse(
			$classLoader->loadClass('Framework\NonEgzistingTranslator')
		);
	}
}
