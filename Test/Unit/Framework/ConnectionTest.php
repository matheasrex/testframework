<?

namespace Test\Unit\Framework;

use Framework\Connection;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test Configurator class
 */ 
class ConnectionTest extends TestCase
{
	/**
	 * @var \Framework\Connection $connection Connection resource
	 */
	protected $connection;
	
	/**
	 * Test Connect
	 */
	public function testConnect()
	{
		$this->assertNull($this->connection->connect());
		$this->assertNull($this->connection->connect(1));
	}
	/**
	 * Test Fail connection
	 *
	 * @expectedException PDOException
	 */
	public function testWrongConnect()
	{
		$connection = new Connection('', '', '', '');
		$connection->connect();
	}
	/**
	 * Test Fetch without query
	 */
	public function testFetchWithouthQuery()
	{
		$this->assertCount(0, $this->connection->allRecord());
	}
	/**
	 * Try to make a wrong query
	 *
	 * @expectedException RuntimeException
	 */
	public function testWrongQuery()
	{
		$this->connection->query("
			SELECT
				userbase_id,
				userbase_login,
				userbase_right
			FROM
				nonegzisting_table
		");
	}
	/**
	 * Test Query
	 */
	public function testQuery()
	{
		$this->connection->query("
			SELECT
				userbase_id,
				userbase_login,
				userbase_right
			FROM
				userbase
		");
		$this->assertCount(3, $this->connection->nextRecord());
		$this->assertTrue(count($this->connection->allRecord()) >= 2);
		$this->assertCount(0, $this->connection->allRecord());
	}

	/**
	 * setUp database configuration
	 */
	protected function setUp()
	{
		parent::setUp();
		$config = new \Framework\Configuration($this->configPath);
		$connectionData = $config->get('database.connection.data');
		$this->connection = new Connection(
			$connectionData['db'], 
			$connectionData['user'], 
			$connectionData['pwd'], 
			$connectionData['host']
		);
	}
	
}
