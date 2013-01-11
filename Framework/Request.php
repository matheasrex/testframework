<?

namespace Framework;

use Framework\RequestItem;
use Framework\Configuration;
use Framework\Session\SessionStorage;

/**
 * function to handle request
 * contains:
 ** session
 ** query (represents $_GET)
 ** request (represents $_POST)
 ** cookie (represents $_COOKIE)
 ** server (represents $_SERVER)
 ** session (represents $_SESSION)
 */
class Request
{
	/**
	 * @var Framework\RequestItem() Object to store get parameters
	 * @access public
	 */
	public $query;
	/**
	 * @var Framework\RequestItem() Object to store post parameters
	 * @access public
	 */
	public $request;
	/**
	 * @var Framework\RequestItem() Object to store cookie parameters
	 * @access public
	 */
	public $cookie;
	/**
	 * @var Framework\RequestItem() Object to store server parameters
	 * @access public
	 */
	public $server;
	/**
	 * @var Framework\Session\SessionStorage() Object to store session parameters
	 * @access public
	 */
	public $session;
	/**
	 * @var Framework\Configuration Object to store config params
	 * @access public
	 */
	public $configuration;
	
	/**
	 * global constructor
	 *
	 * @param \Framework\Configuration $configClass config class
	 */
	public function __construct($configClass)
	{
		$this->query = new RequestItem($_GET);
		$this->request = new RequestItem($_POST);
		$this->cookie = new RequestItem($_COOKIE);
		$this->server = new RequestItem($_SERVER);
		$this->configuration = &$configClass;
		$this->createSession();
	}
	/**
	 * create session
	 */
	protected function createSession()
	{
		if ($handlerClass = $this->configuration->get('session.handler.class')) {
			$handler = new $handlerClass(
				$this->configuration->get('memcache.params'),
				$this->configuration->get('session.handler.options')
			);
		} else {
			throw new \InvalidArgumentException('Framework expects session.handler.class config for memcache session');
		}
		$this->session = $this->createStorage($handler);
	}
	/**
	 * create session storage instance
	 *
	 * @param \Framework\Session\SessionHandler $handler Session handler instance
	 */
	protected function createStorage($handler)
	{
		return new SessionStorage($handler);
	}
	
	/**
	 * get proper method
	 *
	 * @return string The method type of request
	 */
	public function getMethod()
	{
		$method = $this->server->get('REQUEST_METHOD', 'GET');
		$override = $this->server->get('HTTP_X_HTTP_METHOD_OVERRIDE', '');
		if ($method == 'POST' && strtoupper($override) == 'PUT') {
			$method = 'PUT';
		} elseif ($method == 'POST' && strtoupper($override) == 'DELETE') {
			$method = 'DELETE';
		}
		
		return $method;
	}	
}
