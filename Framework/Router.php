<?

namespace Framework;

/**
 * class for determining routing
 */
class Router
{
	/**
	 * @var object $request Request object
	 */
	protected $request;
	/**
	 * @var array $urlMap Default url map to routing
	 */
	protected $urlMap;
	
	/**
	 * global constructor
	 *
	 * @param \Framework\Request $request Request obj
	 */
	public function __construct(\Framework\Request $request)
	{
		$this->request = $request;
		$this->getUrlMap();
	}
	/**
	 * function to find out the proper 
	 * routed function and call it
	 *
	 * @return \Framework\Response response object
	 */
	public function handle()
	{
		$toCall = $this->getRoute();
		if (!($normalized = $this->normalize($toCall))) {
			return $this->redirect('/404');
		}
		extract($normalized);
		$class = new $className($this->request);
		$result = $class->$function();
		$result['template'] = strtolower($toCall['className']).'/'.strtolower($toCall['function']);
		
		return new \Framework\Response($this->request, $result);
	}
	/**
	 * function to find out proper route
	 * firstly in url map
	 * fallback: controller/Function style
	 *
	 * @return array Elements: className, functionName
	 */
	protected function getRoute()
	{
		$path = $this->getPath();
		if (isset($this->urlMap[$path])) {
			return $this->urlMap[$path];
		}
		
		if ($path[0] == '/') {
			$path = substr($path, 1);
		}
		$controller = $function = '';
		if (strstr($path, '/')) {
			list($controller, $function) = explode('/', $path);
		}
		
		return array(
			'className' => $controller,
			'function' => $function,
		);
	}
	/**
	 * normalize callable function and controler names
	 * and check wether they exist or not
	 * 
	 * @param array $input Classname, FunctionName 
	 *
	 * @return array
	 */
	protected function normalize($input)
	{
		$className = 'Controller\\'.ucfirst($input['className']).'Controller';
		$classLoader = new \Framework\ClassLoader();
		if (!$classLoader->findFile($className)) {
			return array();
		}
		$testClass = new $className($this->request);
		$functionName = $input['function'].'Action';
		if (!method_exists($testClass, $functionName)) {
			return array();
		}
		
		return array(
			'className' => $className,
			'function' => $functionName,
		);
	}
	/**
	 * get the current route path of request
	 *
	 * @return string
	 */
	protected function getPath()
	{
		$path = preg_replace(
			'/\?.*$/', 
			'', 
			$this->request->server->get('REQUEST_URI')
		);
		if ($path === '/') {
			return $path;
		}

		if ($path[strlen($path) - 1] == '/') {
			$path = substr($path, 0, -1);
		}
		
		if ($root = $this->getRoot()) {
			$path = str_replace($root, '', $path);
		}
		
		return $path;
	}
	/**
	 * get root
	 *
	 * @return string Root
	 */
	protected function getRoot()
	{
		$dir = str_replace(
			$this->request->server->get('DOCUMENT_ROOT'), 
			'', 
			$this->request->server->get('SCRIPT_FILENAME')
		);
		
		return ($dir == '.' ? '' : $dir . '/');
	}
	/**
	 * get url map from config path
	 *
	 * @link {$urlMap}
	 */
	protected function getUrlMap()
	{
		$this->urlMap = include($this->request->configuration->get('routing.map'));
	}
	/**
	 * redirect function
	 *
	 * @param string $target    Location
	 * @param bool   $permanent Permanent redirect default false
	 */
	protected function redirect($target, $permanent = false)
	{
		$controller = new \Framework\Controller();
		$controller->redirect($target, $permanent);
	}
}
