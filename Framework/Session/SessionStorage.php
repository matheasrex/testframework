<?

namespace Framework\Session;

/**
 * Class for session storage
 */
class SessionStorage
{
	/**
	 * @var array $sessionData Data stored in session
	 */
	protected $sessionData = array();

	/**
	 * Global Constructor.
	 *
	 * @param object $handler Session handler.
	 */
	public function __construct($handler = null)
	{
		register_shutdown_function('session_write_close');
		
		if (!$handler) {
			throw new \InvalidArgumentException('No session handler defined');
		}

		$this->setSaveHandler($handler);
		
		$this->start();
		$this->loadSession();
	}

	/**
	 * session start()
	 */
	protected function start()
	{
		try {
			session_start();
		} catch (\Exception $e) {
			throw new \RuntimeException('Failed to start the session');
		}
	}

	/**
	 * clear session
	 *
	 * @return bool
	 */
	public function clear()
	{
		$_SESSION = array();
		$this->loadSession();
		
		return true;
	}
	/**
	 * Register save handler for session
	 *
	 * @param object $saveHandler Session handler object
	 */
	public function setSaveHandler($saveHandler)
	{
		session_set_save_handler(
			array($saveHandler, 'open'),
			array($saveHandler, 'close'),
			array($saveHandler, 'read'),
			array($saveHandler, 'write'),
			array($saveHandler, 'destroy'),
			array($saveHandler, 'gc')
		);
	}
	/**
	 * get one session element
	 *
	 * @param string $key The key of needed value
	 *
	 * @return mixed
	 */
	public function get($key = '')
	{
		if (!$key) {
			return $this->sessionData;
		}
		if (isset($this->sessionData[$key])) {
			return $this->sessionData[$key];
		}
		
		return null;
	}
	/**
	 * set a session item value
	 *
	 * @param string $key   The key item
	 * @param mixed  $value The value of item
	 *
	 * @return void
	 */
	public function set($key, $value)
	{
		$this->sessionData[$key] = $value;
	}
	/**
	 * add a set of values
	 *
	 * @param array $data Data to be added to session
	 *
	 * @return void
	 */
	public function add($data)
	{
		foreach ($data as $key => $value) {
			$this->set($key, $value);
		}
	}
	/**
	 * Load session data to sessionData variable
	 * @link {$loadSession}
	 */
	protected function loadSession()
	{		
		$this->sessionData = &$_SESSION;
	}
}
