<?

namespace Test\Unit\Framework;

use Framework\MemcachedClient;
use Test\Unit\TestCase;

require_once(dirname(__DIR__).'/TestCase.php');

/**
 * class to test MemcachedClient class
 */ 
class MemcachedClientTest extends TestCase
{
	/**
	 * positive test: check if MemcachedClient is loadable
	 */
	public function testLoadClass()
	{
		$memcachedClient = new MemcachedClient('', array());
		$this->assertInstanceOf('\Framework\MemcachedClient', $memcachedClient);
		$this->assertInstanceOf('\Memcached', $memcachedClient->getInstance());
	}
	/**
	 * positive test: constructor testes
	 */
	public function testLoadRealClass()
	{
		$memcachedClient = new MemcachedClient('', array());
		$configuration = new \Framework\Configuration($this->configPath);
		$config = $configuration->get('memcache.params');
		$memcachedClient = new MemcachedClient(
			$config['persistentId'], 
			$config['options']
		);
		$this->assertInstanceOf('\Framework\MemcachedClient', $memcachedClient);
	}
	/**
	 * try to connect again, addserver function should not be called again
	 */
	public function testReconnect()
	{
		$configuration = new \Framework\Configuration($this->configPath);
		$config = $configuration->get('memcache.params');
		$this->memcache = new \Memcached($config['persistentId']);
		$servers = $this->memcache->getServerList();
		$this->assertCount(1, $servers);
		
		$memcachedClient = new MemcachedClient(
			$config['persistentId'], 
			$config['options']
		);
		$this->assertInstanceOf('\Framework\MemcachedClient', $memcachedClient);
		$this->assertCount(1, $servers);
	}
}
