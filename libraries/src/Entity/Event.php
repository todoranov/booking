<?php 

/**
 * @package     Booking.Base
 * @subpackage  Library
 *
 * @copyright  (C) 2025 Todor Todoranov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Booking\Entity;

use Booking\Entity\Participant;

class Event
{										// elements

	protected $cities = []; 			// [ id => int, name => string ]

	protected $venueId = [];			// [ id => int, name => string ]

	protected $genres = []; 			// [ id => int, name => string ]

	protected $participants = []		// Participant

	protected $name = '';

	protected $description = '';

	protected $price = 0.0;

	public function __construct($name, $price, $description)
	{
		$this->name = $name;
		$this->database = $description;
		$this->price = $price;
	}

	public function addCity($value): bool
	{
		if(array_key_exists('id', $value) && $value['id'] === 0)
		{
			$this->cities[] = $value;
			return true;
		}		
		return false;
	}

	public function addVenue($value): bool
	{
		
	}

	public function addGenre($value): bool
	{
		
	}

	public function addParticipant(?Participant $value): bool
	{
		
	}

	public function setCity(int $id, $value): bool
	{

	}

	public function setVenue(int $id, $value): bool
	{
		
	}

	public function setGenre(int $id, $value): bool
	{
		
	}

	public function addParticipant(int $id, ?Participant $value): bool
	{
		
	}	

	public function cities()
	{
		return $this->cities;
	}

	public function venueId()
	{
		return $this->venueId;
	}

	public function genres()
	{
		return $this->genres;
	}

	public function participants()
	{
		return $this->participants;
	}

	public function name()
	{
		return $this->name;
	}

	public function description()
	{
		return $this->description;
	}

	public function price()
	{
		return $this->price;
	}

}
