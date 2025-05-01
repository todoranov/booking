<?php 

/**
 * @package     Booking.Installation
 * @subpackage  Application
 *
 * @copyright  (C) 2025 Todor Todoranov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Booking\Installation;

use Booking\Database\GetMySQLDb;

class CreateSuperUser
{
	protected $error = '';
	protected $db = null;

	public function __construct(?GetMySQLDb $db = null)
	{
		$this->db = $db;
	}	

	public function create(?array $data): bool
	{
		$result = false;
		if ($this->db && $this->db->connect()) {
			if ($data['admin_user'] && $data['admin_password'] && ($data['admin_email'] || $data['admin_username'])) {
				$data['admin_password'] = password_hash($data['admin_password'], PASSWORD_DEFAULT);
				$keys = ['admin_user', 'admin_password', 'admin_email', '', 'admin_username'];
				$query = "INSERT INTO `users` (`login`, `password`, `email`, `telephone`, `full_name`, `role_id`) ".
						 "VALUES (".$this->toString($data, $keys).", 1);";
				$result = $this->db->query($query);
				if (is_bool($result))
				{
					if (!$result)
						$this->error = $this->db->error();
				}
				$this->db->close();
			}

		}
		return $result;
	}

	private function isValid($value): bool
	{
		return isset($value) && !empty($value);
	}	

	private function toString($data, $keys): string
	{
		$result = '';
	    foreach ($keys as $key) {
	        $result .= (empty($result) ? "'" : "', '").(empty($key) ? "" : $data[$key]);
	    }
	    return $result .= "'";
	}
}
