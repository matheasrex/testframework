<?

namespace Framework;

/**
 * class to handle each items of request such as get or post
 */
class RequestItem
{
	/**
	 * @var array $value The array representation of given data
	 */
	protected $value = array();
	
	/**
	 * global constructor
	 *
	 * @param array $item The actual request item
	 */
	public function __construct($item)
	{
		$this->value = $item;
	}
	/**
	 * get one item value (for the example puspose it was not needed to prepare it for
	 * multi dimensional arrays)
	 *
	 * @param string $key       The key of needed value
	 * @param string $default   The default value if key does not exist
	 * @param bool   $allowHtml Allowed html tags or not
	 *
	 * @return mixed
	 */
	public function get($key, $default = '', $allowHtml = false)
	{
		if (isset($this->value[$key])) {
			$ret = $this->value[$key];
			if ($allowHtml || !is_string($ret)) {
				return $ret;
			}
			
			return strip_tags($ret);
		}
		
		return $default;
	}
	/**
	 * set an item value
	 *
	 * @param string $key   The key item
	 * @param mixed  $value The value of item
	 *
	 * @return void
	 */
	public function set($key, $value)
	{
		$this->value[$key] = $value;
	}
}
