<?

namespace Framework;

/**
 * class to autoload namespaced classes
 * usage:
 * $loader = new \Framework\ClassLoader();
 * $loader->register();
 */
class ClassLoader
{
	/**
	 * function to load class
	 * @param string $class Name of required class
	 *
	 * @return bool
	 */
	public function loadClass($class)
	{
		if ($fileName = $this->findFile($class)) {
			require_once($fileName);
			
			return true;
		}
		
		return false;
	}
	/**
	 * register the autoloader function
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'));
	}
	
	/**
	 * find the file by classname
	 * @param string $class Name of required class
	 *
	 * @return string
	 */
	public function findFile($class)
	{
		$lastSeparatorPosition = strrpos($class, '\\');
		$classRoute = str_replace('\\', '/', substr($class, 0, $lastSeparatorPosition)).'/'.substr($class, $lastSeparatorPosition + 1).'.php';
		if ($file = stream_resolve_include_path($classRoute)) {
			return $file;
		}
		
		return false;
	}
}
