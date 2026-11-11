<?php
namespace QuickShell\QuickShell;

use Exception;
use DirectoryIterator;
use QuickShell\DML\Database;
/**
 * Class QuickShell - The main object that runs the MVC framework
 * @author Mike Seiler http://www.michaelseiler.net
 * @version	1.0
 *
 * @param obj	$db Database Object from PDO class
 * TODO: Should split this apart.  Take out the database object and use it only on pages that need it.
 */
class QuickShell
{
	public $qsApp			= null;
	public $errorMsg		= null;
	protected $db			= null;

	/**
	 * Constructor for the QuickShell Class - Creates a Request object to clean the URL parameters
	 * then takes the constructed elements of the Request and determines if it matches a Route,
	 * returning elements of those routes as object attributes if named routes.
	 * @param string $http_method Method parsed from $_SERVER['REQUEST_METHOD']
	 * @param string $http_accept Accept type passed via $_SERVER['HTTP_ACCEPT'] if present
	 * @param string $url_string String passed as 'id' element via Apache rewrite
	 */
	public function __construct($http_method,$http_accept = null, $url_string = null)
	{
		$request = new \QuickShell\QuickShell\Request($http_method,$http_accept,$url_string);
		$route = new \QuickShell\QuickShell\Route($request);
		if(isset($route->errorType)) {
			isset($route->isApi) ? self::sendError($route,true) : self::sendError($route,false);
			die();
		}
		// BUILD THE APPROPRIATE OBJECT HANDLER FOR THE CASES REQUESTED
		if($route->isApi == true) {
			if($route->matchFound == true) {
				$this->qsApp = new \QuickShell\QuickShell\QuickShellAPI($route);
			} else {
				$route->errorType = array("404");
				self::sendError($route,true);
			}
		} else {
			if($route->matchFound == true) {
				$this->qsApp = new \QuickShell\QuickShell\QuickShellMVC($route);
			} else {
				$this->qsApp = new \QuickShell\QuickShell\QuickShellMVC($request);
				/* No error output here, as the MVC can still determine an UFO request*/
			}
		}
	}

	/**
	 * function sendError - processes the error type determined in the Route class and sends it back in
	 * a format appropriate for the attempt.
	 *
	 * @param array errorType Type of error based on HTTP Methods
	 * @param boolean api Flag set to process an API response (headers only) or an MVC Response (web page)
	 */
	private function sendError(\QuickShell\QuickShell\Route $route, $api = null)
	{
		$error_type = $route->errorType;
		$method_expected = $route->reqTypeExpected;
		$accept_expected = $route->acceptExpected;
		include(SYSTEM_DIR."cfg".DIRECTORY_SEPARATOR."http_headers".FILE_EXTENSION);
		foreach($error_type as $error) {
			$this->errorMsg .= "Error Encountered:\n$error: $http_status_codes[$error]<br />";
		}
		if(isset($method_expected)) {
			$this->errorMsg .= "\nExpected Method: $method_expected<br />";
		}
		if(isset($accept_expected)) {
			$this->errorMsg .= "\nExpected Accept Type: $accept_expected<br />";
		}
		if(isset($route->isApi)) {
			header("HTTP/1.0 $error: $http_status_codes[$error]");
			echo $this->errorMsg;
			die();
		} else {
			/* No Accept type for MVC, so we know to send a method page _ vars are local to the bad_method view*/
			$this->qsApp = new \QuickShell\QuickShell\QuickShellMVC(null);
			$this->qsApp->qsView = "bad_method";
			$this->qsApp->required_method = $route->reqTypeExpected;
			$this->qsApp->req_method = $route->reqMethod;
			$this->qsApp->error_msg = $this->errorMsg;
			header("HTTP/1.0 $error: $http_status_codes[$error]");
			$this->qsApp->render();
		}
	}

	/**
	 * public function run - Calls the render function on the API or MVC object
	 */
	public function run()
	{
		$this->qsApp->render();
	}
}
