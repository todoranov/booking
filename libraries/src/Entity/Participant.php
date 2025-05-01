<?php 

/**
 * @package     Booking.Base
 * @subpackage  Library
 *
 * @copyright  (C) 2025 Todor Todoranov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Booking\Entity;

class Participant
{
	protected $id = 0;

	protected $name = '';

	protected $positionId = 0;

	protected $positionName =  '';

	public function __construct($id, $name, $positionId, $positionName)
	{
		$this->id = $id;
		$this->name = $name;
		$this->positionId = $positionId;
		$this->positionName = $positionName;
	}

	public function id()
	{
		return $this->id;
	}

	public function name()
	{
		return $this->name;
	}

	public function positionId()
	{
		return $this->positionId;
	}

	public function id()
	{
		return $this->positionName;
	}
	
}