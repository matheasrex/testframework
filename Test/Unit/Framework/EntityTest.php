<?

namespace Test\Unit\Framework;

use Framework\Entity;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test Configurator class
 */ 
class EntityTest extends TestCase
{
	/**
	 * Test Userbase Entity
	 */
	public function testUserbase()
	{
		$entity = new \Entity\Userbase();
		$entity->id = 12;
		$this->assertEquals(12, $entity->getId());
		$entity->setLogin('pistike');
		$this->assertEquals('pistike', $entity->login);
	}
	/**
	 * Negative test cases
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testUserbaseFailSet()
	{
		$entity = new \Entity\Userbase();
		$entity->invalid = 12;
	}
	/**
	 * Negative test cases
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testUserbaseFailGet()
	{
		$entity = new \Entity\Userbase();
		$test = $entity->invalid;
	}
	/**
	 * Negative test cases
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testUserbaseFailCall()
	{
		$entity = new \Entity\Userbase();
		$entity->callInvalid();
	}
}
