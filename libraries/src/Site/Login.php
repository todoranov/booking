<?php 

/**
 * @package     Booking.Site
 * @subpackage  Library
 *
 * @copyright  (C) 2025 Todor Todoranov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Booking\Site;

use Booking\Database\GetMySQLDb;
use Booking\Entity\Visitor;

class Login
{			
	protected $db = null;

	protected $visitor = null;

	protected $error = '';

	public function __construct(?GetMySQLDb $db = null)
	{
		$this->db = $db;
	}

	public function login(?Visitor $visitor): bool
	{
		if ($this->visitor)
		{
			$this->error = 'Има активен клиент в системата.';
			return false;
		}
	
		if ($this->exists($visitor, true))
		{
			$this->visitor = $visitor;
			return true;
		}

		unset($this->visitor);
		$this->visitor = null;

		return false;
	}

	public function logout(): bool
	{
		unset($this->visitor);
		$this->visitor = null;
	}

	public function visitor(): Visitor|null
	{
		return $this->visitor;
	}

	private function exists(?Visitor $visitor, $checkPassword = false): bool
	{
		if (!$this->db)
		{
			$this->error = 'Няма асоциирана БД.';
			return false;
		}

		$sql = "SELECT * FROM `users` WHERE `login` = '{$visitor->login()}' OR ".
								    		"`email` = '{$visitor->email()}';";

		$result = $this->db->query($sql);

		foreach ($result as $row) {
			$visitor->setFullname($row['full_name']);
			$visitor->setTelephone($row['telephone']);
			$visitor->setRole($row['role_id']);
			return $checkPassword ? $visitor->verifyPassword($row['password']) : true;
		}
	}
}
