<?php
namespace QuickShell\DML;

use PDO;

/**
 * Database connection class utilizing PHP::PDO
 * All of these elements except for the dbh are configured in the Config file
 *
 * @author michaelseiler
 * @param string	dbHost	The hostname of the database
 * @param string	dbName	The name of the database you wish to call from for this project
 * @param string	dbUsername	The name of the user authorized for the above schema
 * @param string	dbPassword	The user's password on above schema
 * @param string	dbDriver	The proper driver to use (i.e. mysql, psql, etc.)
 * @param object	dbh	The data resource object id returned by PDO
 */
class Database
{
	// VALUES PASSED ARE DELCARED
	// CONSTANTS IN THE CONFIG FILE
	private $dbHost				= DB_HOST;
	public $dbName				= DB_NAME;
	private $dbUsername		= DB_USER;
	private $dbPassword		= DB_PASS;
	private $dbDriver			= DB_DRIVER;
	public $dbh						= null; // resource id
	/**
	 * Creates the database object for use in code
	 * @return resource dbh The database resource returned to the calling page
	 */
	public function __construct()
	{
		/* PDO::ATTR_EMULATE_PREPARES Enables or disables emulation of prepared statements.
		 * Some drivers do not support native prepared statements or have limited support for them.
		 * Use this setting to force PDO to either always emulate prepared statements (if TRUE),
		 * or to try to use native prepared statements (if FALSE). It will always fall back to
		 * emulating the prepared statement if the driver cannot successfully prepare the current query.
		 * http://www.php.net/manual/en/pdo.setattribute.php
		 *
		 * If you plan on using 'false' for the prepares, then you will need to provide
		 * a unique parameter marker for each name value (:example)
		 * http://php.net/manual/en/pdo.prepare.php
		 */
		$conx_string = DB_DRIVER.":host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		try {
			$this->dbh = new PDO($conx_string, $this->dbUsername, $this->dbPassword,
				array(PDO::ATTR_EMULATE_PREPARES => false
				, PDO::ATTR_ERRMODE=>true
				, PDO::ERRMODE_EXCEPTION=>true));
		} catch ( PDOException $e) {
			throw new PDOException($e->getMessage());
		}
		return $this->dbh;
	}
}
