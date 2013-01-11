<?

namespace Framework;

/**
 * connection class
 * the default one should have twice as much function but
 * they are not needed here
 */
class Connection
{
	/**
	 * database query Record
	 * @var array
	 */
	public $record;
	/**
	 * @var string $database database name
	 */
	protected $database;
	/**
	 * @var string $user database user
	 */
	protected $user;
	/**
	 * @var string $password database password
	 */
	protected $password;
	/**
	 * @var string $host database host
	 */
	protected $host;
	/**
	 * @var string $port database port
	 */
	protected $port;
	/**
	 * @var object $connectionResource database connection resource
	 */
	protected $connectionResource;
	/**
	 * @var object $lastStatementResource last statement resource
	 */
	protected $lastStatementResource;
	/**
	 * @var integer $autoCommit auto commit
	 */
	protected $autoCommit = \PDO::ATTR_AUTOCOMMIT;
	/**
	 * @var integer $fetchType fetch mode
	 */
	protected $fetchType;
	
	/**
	 * global constructor - establishing db connection
	 * 
	 * @param string $dbName     Database name
	 * @param string $dbUser     Database user name
	 * @param string $dbPassword Database password
	 * @param string $dbHost     Database host
	 * @param string $dbPort     Database port
	 */
	public function __construct($dbName, $dbUser, $dbPassword, $dbHost, $dbPort = 3306)
	{
		$this->database = $dbName;
		$this->user = $dbUser;
		$this->password = $dbPassword;
		$this->host = $dbHost;
		$this->port = $dbPort;
		$this->fetchType = \PDO::FETCH_ASSOC;
	}
	/**
	 * connect function
	 *
	 * @param integer $forcereconnect Enforced reconnection
	 */
	public function connect($forcereconnect = 0)
	{
		if (!$this->connectionResource || $forcereconnect) {
			$this->connectionResource = new \PDO(
				'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->database,
				$this->user,
				$this->password,
				array(\PDO::ATTR_PERSISTENT => true)
			);
		}
	}
	/**
	 * query function
	 *
	 * @param string $query_string  The query
	 * @param array  $bindvariables Bind list
	 *
	 * @return obj lastStatementResource
	 */
	public function query($query_string, $bindvariables = array())
	{
		$this->connect();
		$this->lastStatementResource = @$this->connectionResource->prepare($query_string);
		
		if (!@$this->lastStatementResource->execute($bindvariables)) {
			return $this->error('failed to execute query:'.$query_string);
		}
		return $this->lastStatementResource;
	}
	/**
	 * nextRecord function - to retreive next row of record
	 *
	 * @param bool $statement Optional param of statement resource
	 *
	 * @return array
	 */
	public function nextRecord($statement = false)
	{
		if (!$statement) {
			$statement = $this->lastStatementResource;
		}
		
		$this->record = $statement->fetch($this->fetchType);
		
		return $this->record;
	}
	/**
	 * allRecord function - to retreive all record
	 *
	 * @param bool $statement Optional param of statement resource
	 *
	 * @return array
	 */
	public function allRecord($statement = false)
	{
		if (!$statement) {
			if ($this->lastStatementResource) {
				$statement = $this->lastStatementResource;
			} else {
				return array();
			}
		}
		
		$data = $this->lastStatementResource->fetchAll($this->fetchType);
		$this->lastStatementResource = $statement;
		
		return (array)$data;
	}
	/**
	 * private error function
	 *
	 * @param string $txt Error text
	 */
	protected function error($txt)
	{
		if ($this->lastStatementResource) {
			$debug_array = @$this->lastStatementResource->errorInfo();
			$debug_data = var_export($debug_array,1);
		}
		
		throw new \RuntimeException("DB: $txt\n$debug_data\n");
	}
}
