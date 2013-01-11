<?

namespace Framework;

/**
 * class for connecting to memcached
 */
class MemcachedClient
{
	/**
	 * @var \Memcached $memcache Memcache object
	 */
	protected $memcache;
	/**
	 * global constructor
	 * @param string $persistentId Persistent connection ikdentification
	 * @param array  $options      Parameter settings for host and port
	 */
	public function __construct($persistentId = '', $options = array())
	{
		$this->memcache = new \Memcached($persistentId);
		$servers = $this->memcache->getServerList();
		if (!isset($options['host']) || !isset($options['port'])) {
			return;
		}
		if (is_array($servers)) {
			foreach ($servers as $server) {
				if ($server['host'] == $options['host'] && $server['port'] == $options['port']) {
					return true;
				}
			}
		} 
		$this->memcache->addServer($options['host'], $options['port']); 
	}
	/**
	 * get instance
	 *
	 * @return object
	 */
	public function getInstance()
	{
		return $this->memcache;
	}
}
