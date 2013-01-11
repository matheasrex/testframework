<?

namespace Framework\Session;

/**
 * Memcache session handler class
 */
class SessionHandler
{
	/**
	 * @var string $prefix Session prefix
	 */
	protected $prefix = '';
	/**
	 * @var string $sessionLifeTime Expire time of session
	 */
	protected $sessionLifeTime = 1800;
	/**
	 * @var obj $memcache Memecache driver
	 */
	protected $memcache;
	
	/**
	 * global contructor
	 *
	 * @param array|obj $memcacheParams Memecache driver or parameters to create driver
	 * @param array     $options        Settings of handler
	 */
	public function __construct($memcacheParams, $options = array())
	{
		foreach (array('sessionLifeTime', 'prefix') as $param) {
			if (isset($options[$param])) {
				$this->$param = $options[$param];
			}
		}
		$this->connectMemcache($memcacheParams);
	}
	/**
	 * Session open
	 *
	 * @param string $savePath    Path of session storage
	 * @param string $sessionName Name og session
	 *
	 * @return true
	 */
	public function open($savePath, $sessionName)
	{
		return true;
	}
	/**
	 * Session close
	 *
	 * @return true
	 */
	public function close()
	{
		return true;
	}
	/**
	 * Read data from session
	 * 
	 * @param string $sessionId Session id
	 *
	 * @return mixed
	 */
	public function read($sessionId)
	{
		return $this->memcache->get($this->prefix.$sessionId) ?: '';
	}
	/**
	 * Read data from session
	 * 
	 * @param string $sessionId Session id
	 * @param mixed  $data      Data to store in session
	 *
	 * @return bool
	 */
	public function write($sessionId, $data)
	{
		return $this->memcache->set(
			$this->prefix.$sessionId, 
			$data, 
			time() + $this->sessionLifeTime
		);
	}
	/**
	 * End session
	 * @param string $sessionId Session id
	 * 
	 * @return bool
	 */
	public function destroy($sessionId)
	{
		return $this->memcache->delete($this->prefix.$sessionId);
	}
	/**
	 * Unnead in case of memcache
	 *
	 * @param int $lifeTime Lifetime of session
	 *
	 * @return true
	 */
	public function gc($lifeTime)
	{
		return true;
	}
	/**
	 * Connect to Memcache 
	 *
	 * @param array|obj $memcacheParams MemcacheParameters
	 *
	 * @return \Memcached 
	 */
	protected function connectMemcache($memcacheParams)
	{
		if (
			is_object($memcacheParams)
			&& $memcacheParams instanceof \Memcached
		) {
			return $this->memcache = $memcacheParams;
		}
		$memcacheClass = $memcacheParams['className'];
		$memcache = new $memcacheClass(
			$memcacheParams['persistentId'] ?: '', 
			$memcacheParams['options'] ?: array()
		);
		$this->memcache = $memcache->getInstance();
	}
}
