<?php 

/**
 * @package     Booking.Site
 * @subpackage  Application
 *
 * @copyright  (C) 2025 Todor Todoranov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Booking\Site;

use Booking\Database\GetMySQLDb;
use Booking\Entity\Visitor;

class Site
{	
	private static $sitename = '';

	private static $db = null;

	private static $login = null;

	public function __construct()
	{

		// Pre-Load configuration. Don't remove the Output Buffering due to BOM issues, see JCode 26026
		ob_start();
		require_once BOOKING_CONFIGURATION . '/configuration.php';
		ob_end_clean();

		// System configuration.
		$config = new \BookingConfig();		
		self::$sitename = $config->sitename;
		self::$db = new GetMySQLDb($config->host, $config->user, $config->password, $config->dbname);
		self::$login = new Login(self::$db);
		unset($config);
	}

	public function userExists($login, $email, $password): bool
	{
		return self::$login->login(new Visitor($login, $email, $password));
	}

	public function loggedVisitor()
	{
		return self::$login->visitor();
	}
}
