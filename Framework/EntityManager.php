<?

namespace Framework;

/**
 * entity manager class
 */
class EntityManager
{
	/**
	 * @var const MD5_SALT salt key for md5
	 */
	const MD5_SALT = 'jkh87;7#gu%/.jelszolkulcs';
	/**
	 * @var \Framework\Connection $connection Connection class
	 */
	protected $connection;
	
	/**
	 * global contructor
	 *
	 * @param \Framework\Connection &$connection Connection resource
	 */
	public function __construct(\Framework\Connection &$connection)
	{
		$this->connection = &$connection;
	}
	
	/**
	 * find a record by criteria
	 * this could be more complex but for the pupose of the example it's enough
	 *
	 * @param string $tableName Name of desired entity
	 * @param array  $criteria  Criteria of select
	 *
	 * @return Entity Entity object
	 */
	public function find($tableName, $criteria = array())
	{
		$entityName = '\Entity\\'.ucfirst($tableName);
		if (class_exists($entityName, true)) {
			$entity = new $entityName;
		} else {
			throw new \InvalidArgumentException($tableName.' is not a real Entity name');
		}
		
		$where = array();
		$bind = array();
		foreach ($criteria as $key => $value) {
			if ($key == 'password' && strlen($value) < 32) {
				$value = md5(self::MD5_SALT.md5($value));
			}
			$where[] = $tableName.'_'.$key.' = :'.$key;
			$bind[$key] = $value;
		}
		$sql = "
			SELECT
				*
			FROM
				".$tableName."
			WHERE
				".implode(' AND ', $where);
		$this->connection->query($sql, $bind);
		if ($record = $this->connection->nextRecord()) {
			foreach($record as $dbName => $dbValue) {
				$objKey = str_replace($tableName.'_', '', $dbName);
				$entity->$objKey = $dbValue;
			}
			
			return $entity;
		}
		
		return false;
	}
}