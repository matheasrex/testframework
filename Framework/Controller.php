<?

namespace Framework;

/**
 * Controller to store common 
 * controller needed functions
 */
class Controller
{
	/**
	 * @var \Framework\Request $request Request object
	 */
	protected $request;
	/**
	 * @var \Framework\Translator $translator Translator object
	 */
	protected $translator;
	/**
	 * @var \Framework\EntityManager $entityManager EntityManager object
	 */
	protected $entityManager;
	
	/**
	 * global contructor
	 *
	 * @param \Framework\Request $request Request object
	 */
	public function __construct(\Framework\Request &$request = null)
	{
		$this->request = &$request;
		if (empty($request)) {
			return;
		}
		$connectionData = $this->request->configuration->get('database.connection.data');
		$connection = new Connection(
			$connectionData['db'], 
			$connectionData['user'], 
			$connectionData['pwd'], 
			$connectionData['host']
		);
		$this->entityManager = new EntityManager($connection);
	}
	/**
	 * function to redirect to another location
	 *
	 * @param string $location  Location
	 * @param bool   $permanent Permanent redirect default false
	 */
	public function redirect($location, $permanent = false)
	{
		if (headers_sent()) {
			throw new \RuntimeException('Allready sent headers');
		}
		header('location:'.$location, $permanent);
		exit;
	}
	/**
	 * function to redirect to another location
	 *
	 * @param string $label Translator label to be translated
	 * @param string $type  Translator type - determines the input file
	 */
	public function translate($label, $type = 'common')
	{
		if (!$this->translator) {
			$this->translator = new Translator(
				$this->request->configuration->get('translator.path')
			);
		}
		return $this->translator->translate($label, $type);
	}
}
