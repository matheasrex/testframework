<?

namespace Framework;

/**
 * class to send mail
 */
class Mailer
{
	/**
	 * @var string $to receiver of email
	 */
	protected $to;
	/**
	 * @var string $senderMail sender email address and name
	 */
	protected $senderMail;
	/**
	 * @var string $mail sender email address
	 */
	protected $mail;
	/**
	 * @var string $replyTo reply to email address and name
	 */
	protected $replyTo;
	/**
	 * @var string $subject email subject
	 */
	protected $subject = 'Hi';
	/**
	 * @var string $body email body
	 */
	protected $body;
	/**
	 * @var string $tpl Name of tpl to be used
	 */
	protected $tpl;
	/**
	 * @var string $headers email headers
	 */
	protected $headers;
	/**
	 * @var string $fetchParams template variable names
	 */
	protected $fetchParams = array();
	/**
	 * @var string $templatePath Path of template
	 */
	protected $templatePath;
	/**
	 * @var string $charset Character set
	 */
	protected $charset;
	
	/**
	 * global constructor
	 *
	 * @param array $config Config list
	 */
	public function __construct($config)
	{
		foreach ($config as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
    }
	/**
	 * mail sending initilalization
	 *
	 * @param string $to  Receiver
	 * @param string $tpl Template file to send mail
	 */
	public function init($to, $tpl)
	{
		$this->to = $to;
		$this->tpl = $this->templatePath.$tpl.'.tpl';
		$this->headers = '';
    }
	/**
	 * function for sending email
	 *
	 * @return bool
	 */
	public function send()
	{
		$str = $this->fetch($this->tpl);
		$tmp = explode("\n", $str);
		if (strstr($tmp[0], 'Subject:')) {
			$this->subject = trim(str_replace('Subject:', '', $tmp[0]));
			unset($tmp[0]);	
		}
		$body = implode("\n", $tmp);
		$this->body = $body;

		$sender_mail = trim(str_replace(array('<', '>') , '', $this->mail));
		$this->addHeader("MIME-Version: 1.0");
		$this->addHeader('Content-Transfer-Encoding: 8bit');
		$this->addHeader('Content-type: text/html; charset="'.$this->charset.'"');
		$this->addHeader('Return-Path: '.$sender_mail);
		$this->addHeader('From: '.$this->senderMail);
		$this->addHeader('Reply-To: '.$this->replyTo);
		$this->addHeader("X-Sender: ".$this->mail);
		$this->addHeader("X-Mailer: PHP");
		$this->addHeader("X-Priority: 2");
		
		ini_set('sendmail_from', $sender_mail);
		return mail(
			$this->to,
			$this->subject,
			$this->body,
			$this->headers, 
			"-oi -f ".$sender_mail
		);
	}
	/**
	 * function for assigning values to the template
	 *
	 * @param string $name  Variable name
	 * @param mixed  $value Value of variable
	 */
	public function assign($name, $value) {
		$this->fetchParams[$name] = $value;
	}
	/**
	 * function for adding header
	 *
	 * @param string $header Heaser string
	 */
	protected function addHeader($header)
	{
        $this->headers .= $header."\r\n";
    }
	/**
	 * function for fetching the template
	 *
	 * @param string $tplname Name of template
	 */
	protected function fetch($tplname)
	{
		foreach($this->fetchParams as $key=>$value) {
			${$key} = $value;
		}
		ob_start();
		include($tplname);
		$res = ob_get_contents();
		ob_end_clean();
		return $res;
	}
}
?>