<?

namespace Entity;

use Framework\Entity;

/**
 * User entity
 */
class Userbase extends Entity
{
	/**
	 * @var int $id Id of the user
	 */
	protected $id;
	/**
	 * @var string $login Login of the user
	 */
	protected $login;
	/**
	 * @var string $password Password hash of the user
	 */
	protected $password;
	/**
	 * @var int $right Right bit of the user
	 */
	protected $right;
}
