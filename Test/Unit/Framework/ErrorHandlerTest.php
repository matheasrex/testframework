<?php

namespace Test\Unit\Framework;

use Framework\ErrorHandler;

/**
 * class for testing allegroup
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * test varExport function
	 *
	 * @access public
	 */
	public function testVarExport()
	{
		$this->assertTrue(ErrorHandler::varExport(2) === '2');
		$this->assertTrue(ErrorHandler::varExport('2') === "'2'");
		$this->assertTrue(ErrorHandler::varExport(null) === "NULL");
		$this->assertTrue(ErrorHandler::varExport(array()) === "array()");
		$this->assertTrue(
			str_replace(
				array(
					"\n",
					"\r",
					"\t",
					" ",
				),
				'',
				ErrorHandler::varExport(
					array(
						1,
						2,
						3,
					)
				)
			) === "array([0]=>1,[1]=>2,[2]=>3,)"
		);
		$this->assertTrue(
			str_replace(
				array(
					"\n",
					"\r",
					"\t",
					" ",
				),
				'',
				ErrorHandler::varExport(
					array(
						5 => 1,
						2,
						3,
					)
				)
			) === "array([5]=>1,[6]=>2,[7]=>3,)"
		);
		$this->assertTrue(
			str_replace(
				array(
					"\n",
					"\r",
					"\t",
					" ",
				),
				'',
				ErrorHandler::varExport(
					array(
						'a' => 1,
						2,
						3,
					)
				)
			) === "array(['a']=>1,[0]=>2,[1]=>3,)"
		);
	}

	/**
	 * test varDump function
	 *
	 * @access public
	 */
	public function testVarDump()
	{
		$this->assertTrue(ErrorHandler::varDump(2) === "int(2)\n");
		$this->assertTrue(ErrorHandler::varDump('2') === "string(1) \"2\"\n");
		$this->assertTrue(ErrorHandler::varDump(null) === "NULL\n");
		$this->assertTrue(ErrorHandler::varDump(array()) === "array(0) {}\n");
		$this->assertTrue(
			str_replace(
				array(
					"\n",
					"\r",
					"\t",
					" ",
				),
				'',
				ErrorHandler::varDump(
					array(
						1,
						2,
						3,
					)
				)
			) === "array(3){[0]=>int(1)[1]=>int(2)[2]=>int(3)}"
		);
		$this->assertTrue(
			str_replace(
				array(
					"\n",
					"\r",
					"\t",
					" ",
				),
				'',
				ErrorHandler::varDump(
					array(
						5 => 1,
						2,
						3,
					)
				)
			) === "array(3){[5]=>int(1)[6]=>int(2)[7]=>int(3)}"
		);
		$this->assertTrue(
			str_replace(
				array(
					"\n",
					"\r",
					"\t",
					" ",
				),
				'',
				ErrorHandler::varDump(
					array(
						'a' => 1,
						2,
						3,
					)
				)
			) === "array(3){[a]=>int(1)[0]=>int(2)[1]=>int(3)}"
		);
	}

	/**
	 * test logError function
	 *
	 * @access public
	 */
	public function testLogError()
	{
		$this->assertFalse(ErrorHandler::handleFatal());
	}
	/**
	 * test logError function
	 *
	 * @access public
	 */
	public function testFileGetContent()
	{
		$this->assertEmpty(ErrorHandler::fileGetContent('test'));
		$thisfile = ErrorHandler::fileGetContent(__FILE__);
		$this->assertTrue(strlen($thisfile) > 100);
	}
	/**
	 * testHandle Error
	 *
	 * @runInSeparateProcess
	 */
	public function testHandleError()
	{
		ob_start();
		set_exit_overload(function() { return false; });
		ErrorHandler::handleError(32, 'This is a unit test generated test error', 'file', 'line');
		unset_exit_overload();
		$data = ob_get_contents();
		ob_end_clean();
		$this->assertContains('Hiba', $data);
	}
	/**
	 * test handleException
	 */
	public function testHandleException()
	{
		ob_start();
		set_exit_overload(function() { return false; });
		ErrorHandler::handleException(new \Exception('This is a unit test error'));
		unset_exit_overload();
		$data = ob_get_contents();
		$this->assertNull(ErrorHandler::handleFatal());
		ob_end_clean();
		$this->assertContains('Hiba', $data);
	}
	/**
	 * test handleFatal
	 */
	public function testHandleFatal()
	{
		ob_start();
		try {
			throw new \InvalidArgumentException('This is a unit test thrown exception');
		} catch (\InvalidArgumentException $e) {
			ErrorHandler::handleFatal();
		}
		$data = ob_get_contents();
		ob_end_clean();
		$this->assertContains('Hiba', $data);
	}
}
