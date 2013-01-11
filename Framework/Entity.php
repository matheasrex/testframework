<?

namespace Framework;

/**
 * entity parent class
 */
class Entity
{
	/**
	 * getter function
	 *
	 * @param string $property The property to get
	 *
	 * @return mixed string|int
	 */
	public function __get($property)
	{
		if (property_exists($this, $property)) {
			return $this->$property;
		}
		
		throw new \InvalidArgumentException('Unable to set '.$property.' is not an egzisting property of '.get_class($this).' class');
	}
	/**
	 * setter function
	 *
	 * @param string     $property The property to set
	 * @param string|int $value    The value for the property to be set
	 *
	 * @return bool
	 */
	public function __set($property, $value)
	{
		if (property_exists($this, $property)) {
			$this->$property = $value;
			
			return true;
		}
		
		throw new \InvalidArgumentException('Unable to set '.$property.' is not an egzisting property of '.get_class($this).' class');
	}
	/**
	 * caller function
	 *
	 * @param string     $function The called function
	 * @param string|int $value    The value for the property to be set
	 *
	 * @return bool
	 */
	public function __call($function, $value = '')
	{
		if (substr($function, 0, 3) == 'get') {
			return $this->__get(lcfirst(substr($function, 3)));
		}
		if (substr($function, 0, 3) == 'set') {
			return $this->__set(lcfirst(substr($function, 3)), $value[0]);
		}
		
		throw new \InvalidArgumentException('Unable to call '.$function.' is not an egzisting function of '.get_class($this).' class');
	}
}
