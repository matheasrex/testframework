<?

namespace Framework;

/**
 * class to render templates
 */
class Templating
{
	/**
	 * @var string $templateDir Directory where templates are stored
	 */
	protected $templateDir;
	/**
	 * @var \Framework\Configuration $configuration Config class
	 */
	protected $configuration;
	/**
	 * @var array $templateParams List of template parameters
	 */
	protected $templateParams;
	
	/**
	 * global constructor
	 * 
	 * @param \Framework\Configuration $configuration Config class to receive defaults
	 */
	public function __construct(\Framework\Configuration $configuration)
	{
		$this->configuration = $configuration;
		$this->templateDir = $this->configuration->get('templating.template_dir');
		$this->assign('templateDir', $this->templateDir);
		$this->assign('config', $this->configuration);
		$this->assign('tpl', $this);
	}
	/**
	 * assign variable to the template
	 *
	 * @param string $name  Name of parameter
	 * @param mixed  $value Value of parameter
	 */
	public function assign($name, $value = '')
	{
		if (is_array($name)) {
			foreach ($name as $k => $v) {
				$this->templateParams[$k] = $v;
			}
		} else {
			$this->templateParams[$name] = $value;
		}
	}
	/**
	 * show one template
	 *
	 * @param string $page Template file name
	 */
	public function display($page)
	{
		foreach ($this->templateParams as $key => $oneparam) {
			${$key} = $oneparam;
		}
		include($this->templateDir.$page);
	}
	/**
	 * fetch one template
	 * @param string $tplname Template file name
	 *
	 * @return string Fetched template
	 */
	public function fetch($tplname)
	{
		foreach ($this->templateParams as $key => $value) {
			${$key} = $value;
		}
		ob_start();
		include($this->templateDir.$tplname);
		$res = ob_get_contents();
		ob_end_clean();
		
		return $res;
	}
	/**
	 * page function to show header, footer and $templatefile
	 * 
	 * @param string $templateFile Template file name
	 *
	 * @return void
	 */
	public function page($templateFile)
	{
		if (!($layoutFile = $this->configuration->get('templating.layout'))) {
			return $this->display($templateFile);
		}
		header('Content-Type: text/html; charset=utf-8');
		
		$this->assign('activeTemplate', $templateFile);
		$this->display($layoutFile);
	}
}
