<?

namespace Framework;

/**
 * global anywhere usable Error Handler function
 */
class ErrorHandler
{
	/**
	 * @var const developer error level
	 */
	const ERROR_LEVEL_DEVELOPER = 'D';
	
	/**
	 * @var const error error level
	 */
	const ERROR_LEVEL_ERROR = 'E';
	
	/**
	 * @var const exception error level
	 */
	const ERROR_LEVEL_EXCEPTION = 'X';
	
	/**
	 * @var const fatal error level
	 */
	const ERROR_LEVEL_FATAL = 'F';
	
	/**
	 * @var const nesting level threshold for function varExport
	 */
	const NESTING_LEVEL_THRESHOLD = 100;
	
	/**
	 * @var const url of system down message
	 */
	const SYSTEM_DOWN_URL = '/../Static/img/error/down.html';
	
	/**
	 * @var error type names
	 *
	 * @access protected
	 */
	protected static $errorTypes = array(
		E_ERROR => 'E_ERROR',
		E_WARNING => 'E_WARNING',
		E_PARSE => 'E_PARSE',
		E_NOTICE => 'E_NOTICE',
		E_CORE_ERROR => 'E_CORE_ERROR',
		E_CORE_WARNING => 'E_CORE_WARNING',
		E_COMPILE_ERROR => 'E_COMPILE_ERROR',
		E_COMPILE_WARNING => 'E_COMPILE_WARNING',
		E_USER_ERROR => 'E_USER_ERROR',
		E_USER_WARNING => 'E_USER_WARNING',
		E_USER_NOTICE => 'E_USER_NOTICE',
		E_STRICT => 'E_STRICT',
		E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
		E_DEPRECATED => 'E_DEPRECATED',
		E_USER_DEPRECATED => 'E_USER_DEPRECATED',
	);
	
	/**
	 * @var int current level of nesting in function varExport
	 *
	 * @access protected
	 */
	protected static $nestingLevel = 0;
	
	/**
	 * handle php errors
	 *
	 * @param int    $errorType type of error
	 * @param string $errorText text of error
	 * @param string $errorFile file where the error occured
	 * @param int    $errorLine line in file where the error occured
	 *
	 * @access public
	 */
	public static function handleError($errorType, $errorText, $errorFile, $errorLine)
	{
		if (
			(ini_get('error_reporting') & $errorType) &&
			(ini_get('error_reporting') != 0)
		) {
			$message = "Error: ".self::varExport(
				array(
					'type' => $errorType." (".self::parseErrorType($errorType).")",
					'message' => trim($errorText),
					'file' => $errorFile,
					'line' => $errorLine,
				)
			);
			
			self::createError(
				$message,
				self::ERROR_LEVEL_ERROR,
				self::getErrorKey(
					$errorFile,
					$errorLine,
					$errorText
				)
			);
			
			exit(1);
		}
	}
	
	/**
	 * handle php exceptions, which reached the kernel
	 *
	 * @param Exception $exception exception
	 *
	 * @access public
	 */
	public static function handleException($exception)
	{
		$message = "Exception # \\".get_class($exception).": ".self::varExport(
			array(
				'message' => trim($exception->getMessage()),
				'code' => $exception->getCode(),
				'file' => $exception->getFile(),
				'line' => $exception->getLine(),
				'trace' => $exception->getTraceAsString(),
			)
		);
		
		$message = self::removePasswordFromException($message);

		self::createError(
			$message,
			self::ERROR_LEVEL_EXCEPTION,
			self::getErrorKey(
				$exception->getFile(),
				$exception->getLine(),
				$exception->getMessage()
			)
		);
		
		exit(1);
	}
	
	/**
	 * handle php fatal errors, if can
	 *
	 * @return bool|void
	 *
	 * @access public
	 */
	public static function handleFatal()
	{
		$error = error_get_last();
		if (!$error) {
			return false;
		}
		ini_set('memory_limit', (ini_get('memory_limit') + 25).'M');
		
		$message = "Fatal: ".self::varExport($error);
		
		self::createError(
			$message,
			self::ERROR_LEVEL_FATAL,
			self::getErrorKey(
				$error['file'],
				$error['line'],
				$error['message']
			)
		);
	}
	
	/**
	 * export variable based on it's data type
	 *
	 * @param mixed $var   variable to export
	 * @param int   $level tab level for readability
	 *
	 * @return string string representation of variable
	 *
	 * @access public
	 */
	public static function varExport($var, $level = 0)
	{
		$tabLevelPrevious = "";
		$tabLevel = "";
		if ($level > 0) {
			$tabLevelPrevious = str_repeat("\t", $level - 1);
			$tabLevel = $tabLevelPrevious . "\t";
		}
		$retval = null;
		if (is_null($var)) {
			$retval .= 'NULL';
		}
		switch (gettype($var)){
			case "array":
				reset($var);
				if (empty($var)) {
					$retval .= "array()";
				} else {
					$retval .= "array(\n";
					foreach ($var as $key => $val) {
						$retval .= $tabLevel."\t[".self::varExport($key)."] => ".self::varExport($val, $level + 1).", \n";
					}
					$retval .= $tabLevel.")";
				}
			break;
			case "string":
				$retval .= "'".$var."'";
			break;
			case "object":
				$retval .= "object # ";
				$objectReflection = new \ReflectionClass(get_class($var));
				if ($objectReflection->hasMethod('__toString')) {
					$objectString = (string)$var;
				} else {
					$objectString = self::objectToString($var);
				}
				$retval .= str_replace("\n", "\n".$tabLevel, $objectString);
			break;
			case "boolean":
				$retval .= ($var) ? "true" : "false";
			break;
			default:
				$retval .= $var;
			break;
		}
		self::$nestingLevel--;
		
		return $retval;
	}
	
	/**
	 * dump variable based on it's data type
	 *
	 * @param mixed $var   variable to export
	 * @param int   $depth tabl level for readability
	 *
	 * @return string string representation of variable
	 *
	 * @access public
	 */
	public static function varDump($var, $depth = 8)
	{
		$iniParams = array(
			'html_errors' => 0,
			'xdebug.var_display_max_children' => 512,
			'xdebug.var_display_max_data' => 32765,
			'xdebug.var_display_max_depth' => $depth,
		);
		foreach ($iniParams as $iniKey => $iniParam) {
			$iniParams[$iniKey] = ini_set($iniKey, $iniParam);
		}
		ob_start();
		var_dump($var);
		$retval = ob_get_contents();
		ob_end_clean();
		foreach ($iniParams as $iniKey => $iniParam) {
			$iniParams[$iniKey] = ini_set($iniKey, $iniParam);
		}
		
		$retval = str_replace(array("=>\n", "  "), array("=>", "\t"), $retval);
		
		$retval = preg_replace("/(=>)(\\t*)(.*)/i", "$1 $3", $retval);
		$retval = preg_replace("/(\\t*)(')(.*)(')( =>)/i", "$1[$3]$5", $retval);
		$retval = preg_replace("/({\n)(\\t*)(})/m", "{}", $retval);
		
		return $retval;
	}
	
	/**
	 * export object based on it's reflection data and properties
	 *
	 * @param object $object           object used for the reflection
	 * @param array  $objectProperties properties of the object
	 *
	 * @return string string representation of object
	 *
	 * @access public
	 */
	public static function objectToString($object, $objectProperties = null)
	{
		if (is_null($objectProperties)) {
			$objectProperties = get_object_vars($object);
		}
		$objectClass = get_class($object);
		$objectReflection = new \ReflectionClass($objectClass);
		
		$retval = "\\".$objectClass;
		if ($parentClass = $objectReflection->getParentClass()) {
			$retval .= ' extends \\'.$parentClass->name;
		}
		$retval .= "(\n";
		
		$objectValues = array();
		$constants = $objectReflection->getConstants();
		if (!empty($constants)) {
			$objectValues['CONSTANTS'] = $constants;
		}
		$propertyGroups = array(
			'PUBLIC' => \ReflectionProperty::IS_PUBLIC,
			'PROTECTED' => \ReflectionProperty::IS_PROTECTED,
			'PRIVATE' => \ReflectionProperty::IS_PRIVATE,
		);
		$objectPropertyNames = array_keys($objectProperties);
		foreach ($propertyGroups as $key => $filter) {
			$properties = $objectReflection->getProperties($filter);
			if (!empty($properties)) {
				$objectValues['PROPERTIES'][$key] = array();
				foreach ($properties as $property) {
					$propertyKey = $property->name;
					if ($property->class != $objectClass) {
						$propertyKey .= ' (from \\'.$property->class.')';
					}
				}
			}
		}
		
		$retval .= "\t".self::varExport($objectValues, 1)."\n";
		
		$retval .= ")\n";
		
		return $retval;
	}
	
	/**
	 * Function to get file contents with predefined time limit
	 *
	 * @param string  $remoteFile Filename or url
	 * @param integer $timeout    Timeout in seconds - default 3
	 *
	 * @return string The content of remote file
	 *
	 * @access public
	 */
	public static function fileGetContent($remoteFile, $timeout = 3)
	{
		$socketOptions = array(
			'http' => array(
				'timeout' => $timeout,
			),
		);
		$streamContext = stream_context_create($socketOptions);
		if ($fileContent = @file_get_contents($remoteFile, false, $streamContext)) {
			return $fileContent;
		}
		
		return '';
	}
	
	/**
	 * create error log file
	 *
	 * @param mixed  $message   message to write into file
	 * @param string $type      type of error (E: error, X: exception, F: fatal, U: user raised)
	 * @param string $key       key given to user to identitfy error message
	 * @param bool   $showError if true and in live environment, the user will see an error message
	 *
	 * @return string filename of logged error
	 * 
	 * @access protected
	 */
	protected static function createError($message, $type = self::ERROR_LEVEL_DEVELOPER, $key = '')
	{
		$filePath = self::getErrorLogPath($message, $type, $key);
		$message = self::getErrorMessage($message);
		file_put_contents($filePath, $message);
		self::showErrorMessage($key);
		self::sendMail($message);
		
		return $filePath;
	}
	
	/**
	 * send email of error to a certain address
	 *
	 * @param string $message error message
	 */
	protected static function sendMail($message)
	{
		$configuration = new \Framework\Configuration(CONFIG_FILE);
		$mail = new \Framework\Mailer($configuration->get('mailer.config'));
		$mail->init($configuration->get('mail.debug.address'), 'error');
		$mail->assign('errorData', $message);
		$mail->send();
	}
	
	/**
	 * determine path and filename of error log file
	 *
	 * @param mixed  $message message to write into file
	 * @param string $type    type of error (E: error, X: exception, F: fatal, U: user raised)
	 * @param string $key     key given to user to identitfy error message
	 *
	 * @return string Error log file path
	 *
	 * @access protected
	 */
	protected static function getErrorLogPath($message, $type, $key)
	{
		return ERROR_LOG_PATH.date('Y_m_d_H_i_s').'_'.$key.'_'.$type.'_'.md5(microtime()).'.log';
	}
	
	/**
	 * create full error message from the original message
	 *
	 * @param string $originalMessage Original message string
	 *
	 * @return string Error message
	 *
	 * @access protected
	 */
	protected static function getErrorMessage($originalMessage)
	{
		$errorInfo = array(
			'SESSION' => (isset($_SESSION) ? $_SESSION : null),
			'GET' => (isset($_GET) ? $_GET : null),
			'POST' => (isset($_POST) ? $_POST : null),
			'COOKIE' => (isset($_COOKIE) ? $_COOKIE : null),
			'FILES' => (isset($_FILES) ? $_FILES : null),
			'ENV' => (isset($_ENV) ? $_ENV : null),
			'SERVER' => (isset($_SERVER) ? $_SERVER : null),
			'cwd' => getcwd(),
			'pid' => getmypid(),
			'error_reporting_level' => ini_get('error_reporting'),
		);
		
		$message = "Date:\t".date('Y-m-d H:i:s')."\n".$originalMessage;
		
		if (function_exists('debug_backtrace')) {
			$errorInfo['stack'] = array_slice(debug_backtrace(), 0, 100);
		}
		
		$message .= "\n------------------------------\n";
		$message .= self::varExport($errorInfo);
		
		return $message;
	}
	
	/**
	 * Generate system down html url - supposed to be in static.$site/error/down.html
	 *
	 * @return string
	 *
	 * @access protected
	 */
	protected static function generateSystemDownUrl()
	{
		return dirname(__FILE__).self::SYSTEM_DOWN_URL;
	}
	
	/**
	 * show error page to user
	 *
	 * @param string $errorKey key of error for error log determination
	 *
	 * @access protected
	 */
	protected static function showErrorMessage($errorKey)
	{
		print str_replace(
			array(
				'<!-- {#',
				'#} -->',
				'{DATE}',
				'{REF}',
			),
			array(
				'',
				'',
				date('Y-m-d H:i:s'),
				$errorKey,
			),
			self::fileGetContent(
				self::generateSystemDownUrl()
			)
		);
	}

	/**
	 * generate error key
	 *
	 * @param string $file    file where the error occured
	 * @param int    $line    line in file where the error occured
	 * @param string $message message of error
	 *
	 * @return string error key
	 *
	 * @access protected
	 */
	protected static function getErrorKey($file, $line, $message)
	{
		return self::generateErrorKey($file.':'.$line.':'.$message);
	}
	
	/**
	 * generate error key
	 *
	 * @param string $message base of key
	 *
	 * @return string error key
	 *
	 * @access protected
	 */
	protected static function generateErrorKey($message)
	{
		return strtoupper(substr(md5($message), 0, 8));
	}
	
	/**
	 * mask db passwords in exception error logs
	 *
	 * @param string $text exception string representation
	 *
	 * @return string masked exception text
	 *
	 * @access protected
	 */
	protected static function removePasswordFromException($text)
	{
		$replaceData = array(
			array(
				'patterns' => array(
					"/(.*?)(PDO->__construct\(')(.*?)(', ')(.*?)(', ')(.*?)(')(.*?)/i",
				),
				'replacement' => '$1$2$3$4$5${6}PASSWORD$8$9',
			),
		);
		
		foreach ($replaceData as $replace) {
			$text = preg_replace(
				$replace['patterns'],
				$replace['replacement'],
				$text
			);
		}
		
		return $text;
	}
	
	/**
	 * parse error type names from type code
	 *
	 * @param int $type error type code
	 *
	 * @return string erorr type names
	 *
	 * @access protected
	 */
	protected static function parseErrorType($type)
	{
		$parsedType = array();
		foreach (self::$errorTypes as $errorType => $errorName) {
			if ($type & $errorType) {
				$parsedType[] = $errorName;
			}
		}
		
		return implode(',', $parsedType);
	}
}
