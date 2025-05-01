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

class CreateTables
{
	private $error = '';
	private $db = null;

	public function __construct(?GetMySQLDb $db)
	{
		$this->db = $db;
		$this->error = $db ? $db->error() : "";
	}

	public function close(): bool 
	{
		if ($this->db && !$this->db->close())
		{
			$this->error = $this->db->error();
			return empty($this->error);
		}
		return true;
	}

	public function create($query): bool
	{
		if ($this->db) {
			if ($this->db->connect())
			{
				$result = $this->populate($query);
				//$result = $this->db->query($query);
				if (is_bool($result))
				{
					if (!$result)
						$this->error = $this->db->error();
					return($result);
				}
			}
			else {
				$this->error = $this->db->error();
			}
		}
		else {
			$this->error = "Неинициализирана променлива за база данни.";
		}
		return false;
	}

	public function error(): string
	{
		return $this->error;
	}

	protected function populate($buffer)
	{
		$result = true;

        // Get an array of queries from the schema and process them.
        $queries = $this->splitQueries($buffer);

		//$this->db->begin_transaction();

        foreach ($queries as $query) {
            // Trim any whitespace.
            $query = trim($query);

            // If the query isn't empty and is not a MySQL comment, execute it.
            if (!empty($query) && ($query[0] != '#') && ($query[0] != '-')) {
                // Execute the query.
                // echo "Execute Query: ".$query;
                $this->db->query($query);
            }
        }

        //$this->db->commit();

        return $result;		
	}

	protected function splitQueries($query)
    {
        $buffer    = [];
        $queries   = [];
        $in_string = false;

        // Trim any whitespace.
        $query = trim($query);

        // Remove comment lines.
        $query = preg_replace("/\n\#[^\n]*/", '', "\n" . $query);

        // Remove PostgreSQL comment lines.
        $query = preg_replace("/\n\--[^\n]*/", '', "\n" . $query);

        // Find function.
        $funct = explode('CREATE OR REPLACE FUNCTION', $query);

        // Save sql before function and parse it.
        $query = $funct[0];

        // Parse the schema file to break up queries.
        for ($i = 0; $i < \strlen($query) - 1; $i++) {
            if ($query[$i] == ';' && !$in_string) {
                $queries[] = substr($query, 0, $i);
                $query     = substr($query, $i + 1);
                $i         = 0;
            }

            if ($in_string && ($query[$i] == $in_string) && $buffer[1] != "\\") {
                $in_string = false;
            } elseif (!$in_string && ($query[$i] == '"' || $query[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
                $in_string = $query[$i];
            }

            if (isset($buffer[1])) {
                $buffer[0] = $buffer[1];
            }

            $buffer[1] = $query[$i];
        }

        // If the is anything left over, add it to the queries.
        if (!empty($query)) {
            $queries[] = $query;
        }

        // Add function part as is.
        for ($f = 1, $fMax = \count($funct); $f < $fMax; $f++) {
            $queries[] = 'CREATE OR REPLACE FUNCTION ' . $funct[$f];
        }

        return $queries;
    }
}
