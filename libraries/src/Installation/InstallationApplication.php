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
use Booking\Installation\CreateTables;

//use \BookingConfig;

class InstallationApplication
{
	private $error = '';
	private $db = null;

	public function __construct(?string $host, ?string $user, ?string $password, ?string $database)
	{
		$this->db = new GetMySQLDb($host, $user, $password, $database);		
		$this->error = $this->db->error();
	}

	public function execute(?string $filename): bool
	{
		if ($this->db && isset($filename) && !empty($filename))
		{
			$query_filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
			if (file_exists($query_filename))
			{
				//echo "CreeateTables whih query file name: ".$query_filename;
				$processor = new CreateTables($this->db);
				$query = file_get_contents($query_filename);
				if ($processor->create($query))
					$processor->close();
				$this->error = $processor->error();
				unset($processor);

			}
		}
		return false;
	}

	public function error(): string
	{
		return $this->error;
	}	

	public function getDB()
	{
		return $this->db;
	}	

	public function getInfo() 
	{
		return __CLASS__;
	}

	public static function saveConfigFile($config, ?string $filename)
	{
		if (!\file_exists($filename) || \is_writable($filename))
		{
			$content = "<?php\nclass " . \get_class($config) . "\n{\n";

			$object_vars = \get_object_vars($config);

			foreach ($object_vars as $name => $value) {
    			$content .= "\tpublic ".'$'."{$name} = ";
    			if (is_string($value))
    				$content .= "'{$value}';\n";
    			else
    				$content .= "{$value};\n";
			}

			$content .= "}\n";

			$myfile = fopen($filename, "w");
			fwrite($myfile, $content);
			fclose($myfile);
		}
		return;
	}
}
