<?

namespace Framework;

/**
 * class to load configuration
 */
class Configuration
{
	/**
	 * @var $congig loaded params
	 */
	protected $config;
	
	/**
	 * global constructor
	 *
	 *@param string $configurationPath Path of config file
	 */
	public function __construct($configurationPath)
	{
		$this->config = include($configurationPath);
	}
	
	/**
	 * get parameter
	 *
	 * @param string $key Requested config key
	 *
	 * @return mixed
	 */
	public function get($key)
	{
		if (isset($this->config[$key])) {
			return $this->config[$key];
		}
		
		return '';
	}
	/**
	 * set an item value
	 *
	 * @param string  $key    The key item
	 * @param mixed  $value The value of item
	 *
	 * @return void
	 */
	public function set($key, $value)
	{
		$this->config[$key] = $value;
	}
}
