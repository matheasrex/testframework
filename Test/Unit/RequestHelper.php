<?

namespace Test\Unit;

use Framework\Session\SessionHandler;
use Test\Unit\TestCase;

require_once(__DIR__.'/SessionHandlerHelper.php');

/**
 * Helper class to test SessionHandler related classes
 */ 
class RequestHelper extends SessionHandlerHelper
{
	/**
	 * get mocked request
	 *
	 * @return \Framewok\Request
	 */
	protected function getMockedRequest()
	{
		$storage = $this->getMockedStorage();
		
		$request = $this->getMock(
			'\Framework\Request', 
			array('createStorage'),
			array('configClass' => new \Framework\Configuration($this->configPath))
		);
		
		$request->expects($this->any())
			->method('createStorage')
			->will($this->returnValue($storage));
		
		// This is only needed because mocked constructor methods are not called somehow
		$request->__construct(new \Framework\Configuration($this->configPath));
		
		return $request;
	}
}