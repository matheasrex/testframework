<?

namespace Test\Unit\Framework;

use Framework\EntityManager;
use Framework\Connection;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test Configurator class
 */ 
class EntityManagerTest extends TestCase
{
	/**
	 * constructor test
	 */
	public function testConstruct()
	{
		$connection = new Connection('', '', '', '');
		$entityManager = new EntityManager($connection);
		
		$this->assertInstanceOf('\Framework\EntityManager', $entityManager);
	}
	/**
	 * find test
	 */
	public function testFind()
	{
		$config = new \Framework\Configuration($this->configPath);
		$connectionData = $config->get('database.connection.data');
		$connection = new Connection(
			$connectionData['db'], 
			$connectionData['user'], 
			$connectionData['pwd'], 
			$connectionData['host']
		);
		$entityManager = new EntityManager($connection);
		
		$userbaseEntity = $entityManager->find('userbase', array('id' => 1));
		$this->assertInstanceOf('\Entity\Userbase', $userbaseEntity);
		$this->assertEquals(1, $userbaseEntity->id);
		
		$this->assertFalse($entityManager->find('userbase', array('id' => -1)));
		$this->assertFalse($entityManager->find('userbase', array('password' => '')));
	}
	/**
	 * find test
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidFind()
	{
		$config = new \Framework\Configuration($this->configPath);
		$connectionData = $config->get('database.connection.data');
		$connection = new Connection(
			$connectionData['db'], 
			$connectionData['user'], 
			$connectionData['pwd'], 
			$connectionData['host']
		);
		$entityManager = new EntityManager($connection);
		
		$entityManager->find('invalid', array('id' => -1));
	}
}
