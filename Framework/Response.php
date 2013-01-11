<?

namespace Framework;

use Framework\Templating;

/**
 * class to handle response
 * this class could be more difficult 
 * but in this case only template rendering is enabled
 */
class Response
{
	/**
	 * @var array $statusCodes list of status codes
	 */
	protected $statusCodes = array(
		'100' => 'Continue',
		'200' => 'OK',
		'201' => 'Created',
		'202' => 'Accepted',
		'203' => 'Non-Authoritative Information',
		'204' => 'No Content',
		'205' => 'Reset Content',
		'206' => 'Partial Content',
		'300' => 'Multiple Choices',
		'301' => 'Moved Permanently',
		'302' => 'Found',
		'303' => 'See Other',
		'304' => 'Not Modified',
		'305' => 'Use Proxy',
		'307' => 'Temporary Redirect',
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'405' => 'Method Not Allowed',
		'406' => 'Not Acceptable',
		'409' => 'Conflict',
		'410' => 'Gone',
		'411' => 'Length Required',
		'412' => 'Precondition Failed',
		'413' => 'Request Entity Too Large',
		'414' => 'Request-URI Too Long',
		'415' => 'Unsupported Media Type',
		'416' => 'Requested Range Not Satisfiable',
		'417' => 'Expectation Failed',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
		'503' => 'Service Unavailable'
	);
	/**
	 * @var array $responseData Data to render
	 */
	protected $responseData = array();
	/**
	 * @var \Framework\Request $request Request object
	 */
	protected $request;
	
	/**
	 * global contructor
	 *
	 * @param \Framework\Request $request      Request object
	 * @param array              $responseData Data to render
	 */
	public function __construct(\Framework\Request $request, $responseData)
	{
		$this->request = $request;
		$this->responseData = $responseData;
	}
	/**
	 * send output
	 */
	public function send()
	{
		$this->setStatusCode();
		$template = $this->responseData['template'].'.tpl';
		unset($this->responseData['template']);
		$this->collectAdditionalTemplateVariables();
		$tpl = new \Framework\Templating($this->request->configuration);
		$tpl->assign($this->responseData);
		$tpl->page($template);
	}
	/**
	 * set proper status response code
	 *
	 * @return void;
	 */
	protected function setStatusCode()
	{
		if (!isset($this->responseData['returnCode'])) {
			return;
		}
		$code = $this->responseData['returnCode'];
		$code .= ' ' . $this->statusCodes[strval($code)];
		header($this->request->server->get('SERVER_PROTOCOL').$code);
		
		unset($this->responseData['returnCode']);
	}
	/**
	 * collect additional template variables depending on config
	 */
	protected function collectAdditionalTemplateVariables()
	{
		$toAssign = $this->request->configuration->get('response.default.assignable');
		foreach ($toAssign as $assignKey => $data) {
			if ($data['from'] == 'session') {
				if ($objData = $this->request->session->get($data['key'], $data['default'])) {
					$objKey = $data['obj_key'];
					$this->responseData[$assignKey] = $objData->$objKey;
				}
			} else {
				// nothing else is supported yet;
				continue;
			}
		}
	}
}
