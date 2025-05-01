<?php 

/**
 * @package     Booking.Installation
 * @subpackage  Application
 *
 * @copyright  (C) 2025 Todor Todoranov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Booking\Database;

class GetMySQLDb
{
	private $host = '';
	private $user = '';
	private $password = '';
	private $database = '';

	private $connected = false;

	private $db = null;

	public function __construct($host, $user, $password, $database)
	{
	    if (self::isValid($host) && self::isValid($user) && self::isValid($database)) {

	    	$this->host = $host;
	    	$this->user = $user;
	    	$this->password = $password;
	    	$this->database = $database;
	    	$this->connected = false;

	    	\mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	    	$this->db = new \mysqli();
	    	$this->db->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
		}
	}

	public function connect(): bool
	{
		if ($this->db && !$this->connected)
		{
			$this->connected = $this->db->real_connect($this->host, $this->user, $this->password, $this->database);
		}
		return $this->connected;
	}

	public function query(string $query, ?array $params = null): \mysqli_result|bool
	{
		if ($this->connect())
		{
			return $this->db->execute_query($query, $params);
		}
		return false;
	}

	public function close(): bool 
	{
		if ($this->db && !$this->connected)
		{
			$this->connected =  !$this->db->close();
			return !$this->connected;
		}
		return true;
	}

	public function error(): string
	{
		return $this->db ? $this->db->error : "Неинициализирана променлива за база данни.";
	}

	private static function isValid($value): bool
	{
		return isset($value) && !empty($value);
	}

	public function getInfo() 
	{
		return __CLASS__;
	}

}
