<?php 

/**
 * @package     Booking.Base
 * @subpackage  Library
 *
 * @copyright  (C) 2025 Todor Todoranov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Booking\Entity;

class Visitor
{
	protected $login = '';
	protected $email = '';
	protected $telephone = '';
	protected $fullname = '';
	protected $birthdate = '';
	protected $password = '';
	protected $role = 0;

	public function __construct($login, $email, $password = null, $fullname = null) {

		$this->login = $login;
		$this->email = $email;
		$this->password = $password;
		$this->fullname = $fullname;		
	}

	public function login(): string
	{
		return $this->login;
	}

	public function email(): string
	{
		return $this->email;
	}

	public function password(): string
	{
		return $this->password;
	}

	public function fullname(): string
	{
		return $this->fullname;
	}	

	public function telephone(): string
	{
		return $this->telephone;
	}	

	public function birthdate(): string
	{
		return $this->birthdate;
	}		

	public function role()
	{
		return $this->role;
	}		

	public function setFullname($value)
	{
		$this->fullname = $value;
	}	

	public function setTelephone($value)
	{
		$this->telephone = $value;
	}	

	public function setBirthdate($value)
	{
		$this->birthdate = $value;
	}		

	public function setRole($value)
	{
		$this->role = $value;
	}		

	public function isAdministrator()
	{
		return $this->role === 1;
	}

	public function undefined()
	{
		return $this->role === 0;
	}

	public function hashPassword(): string
	{
		$password = $this->password ? $this->password : '';
		return password_hash($password, PASSWORD_DEFAULT);
	}

	public function verifyPassword($hashed_password): bool
	{
		$password = $this->password ? $this->password : '';
		return password_verify($password, $hashed_password);
	}
}