<?php
namespace QuickShell\QuickShell;

use Exception;
use stdClass;
/**
 * Route Class - Processes a URL passed via the front controller and the Request class
 * 
 * @author michaelseiler
 */
class Route
{
	public $isApi				= null;
	public $route	 			= null;
	public $matchedRoute	 	= null;
	public $matchFound	 		= null;
	public $reqMethod			= null;
	public $reqTypeMatched		= null;
	public $reqTypeExpected		= null;
	public $httpAccept			= null;
	public $acceptMatched		= null;
	public $acceptExpected		= null;	
	public $errorType			= null;
	/**
	 * Initializes the attributes
	 */
	public function __construct(\QuickShell\QuickShell\Request $request)
	{
		$this->route = $request->cleanReq;
		$this->reqMethod = $request->reqMethod;
		$this->httpAccept = $request->acceptType;
		$this->isApi = $request->apiCall;
		$this->matchedRoute = new stdClass();
		self::matchRoute();
	}
	/**
	 * function matchRoute() - matches the route to the published routes in /cfg/routes.php
	 * 
	 * @param string $route String element pulled from Request object clean_req attribute
	 * @return array $mvc Array of controllers, actions, arguments
	 */
	private function matchRoute()
	{
		$whichFile = isset($this->isApi) ? "apis" : "routes";
		$routes_file = SYSTEM_DIR."cfg".DIRECTORY_SEPARATOR.$whichFile.FILE_EXTENSION;
		if(file_exists($routes_file)) {
			include($routes_file);
			$this->mapped = isset($this->isApi) ? json_decode($apis) : json_decode($routes);
			$static = $this->mapped->routes->static;
			self::matchStatic($static);
			if(!isset($this->matchFound)) {
				$named = $this->mapped->routes->named;
				self::matchNamed($named);
			}
			if(isset($this->matchFound)) {
				isset($this->isApi) ? self::compareRoutesAPI($this->matchedRoute) : self::compareRoutes($this->matchedRoute);
			}
		} else {
			$this->matchFound = false;
			$this->reqTypeMatched = false;
			$this->acceptMatched = false;
			/* No error here - Allows for UFO build in QuickShellMVC Class */
		}
	}
	/**
	 * function matchStatic() - matches url string element against JSON object index (string)
	 * @param array $mapped_routes - array of JSON object elements that match the HTTP Request Method 
	 */
	private function matchStatic($mapped_routes)
	{
		$static_route = implode("/",$this->route);
		foreach($mapped_routes as $index=>$mapped_route) {
			if($index == $static_route) {
				$this->matchedRoute = $mapped_route;
				$this->matchFound = true;
			}
		}
	}
	/**
	 * function matchNamed() - matches url string element against variable named routes, and compares length
	 * of the current url string against the named routes in the case of a separate REST API URL with additional
	 * url parameters necessary for mapping functions 
	 * @param array $mapped_routes - array of JSON object elements that match the HTTP Request Method
	 */
	private function matchNamed($mapped_routes) 
	{
		$cur_route_count = count($this->route);
		foreach($mapped_routes as $index=>$mapped_route) {
			$elements = explode("/",$index);
			$el_count = count($elements);
			if($cur_route_count != $el_count) {
				continue;
			} else {
				foreach($elements as $idx=>$element) {
					if( ($this->route[$idx] != $element) && (strpos($element, ":") === false ) ) {
						continue 2;
					} elseif( ($this->route[$idx] != $element) && (strpos($element, ":") !== false ) ) {
						$pattern = "/(*UTF8)^\:{1}[a-zA-Z0-9\pL_-]+(\/)*$/u";
						preg_match($pattern, $element, $matches);
						if(isset($matches[0])) {
							$this->matchedRoute = $mapped_route;
							$named_element = str_replace(":","",$matches[0]);
							$this->matchedRoute->$named_element = $this->route[$idx];
							$this->matchFound = true;
						}
					} elseif($this->route[$idx] == $element) {
						continue;
					}
				}
			}
		}
	}
	/**
	 * function compareRoutesAPI() - Compares http accept type, request method to current types and methods
	 */
	private function compareRoutesAPI()
	{
		$http_method = isset($this->matchedRoute->http_method) ? $this->matchedRoute->http_method : "get";
		$route_accepts = isset($this->matchedRoute->accept) ? $this->matchedRoute->accept : null;
		if((strstr($this->httpAccept,$route_accepts)) && ($this->reqMethod == $http_method)) {
			$this->matchFound = true;
			$this->reqTypeMatched = true;
			$this->acceptMatched = true;
		} elseif(($this->httpAccept != $route_accepts) && ($this->reqMethod == $http_method)) {
			$this->matchFound = true;
			$this->reqTypeMatched = true;
			$this->acceptMatched = false;
			$this->acceptExpected = $route_accepts;
			$this->errorType = array("406");
		} elseif(($this->httpAccept == $route_accepts) && ($this->reqMethod != $http_method)) {
			$this->matchFound = true;
			$this->reqTypeMatched = false;
			$this->reqTypeExpected = $http_method;
			$this->acceptMatched = true;
			$this->errorType = array("405");
		} else {
			$this->matchFound = true;
			$this->reqTypeMatched = false;
			$this->reqTypeExpected = $http_method;
			$this->acceptMatched = false;
			$this->acceptExpected = $route_accepts;
			$this->errorType = array("405","406");
		}
	}
	/**
	 * function compareRoutes() - Compares expected request method to current http method
	 */
	private function compareRoutes($mapped_routes)
	{
		$http_method = isset($this->matchedRoute->http_method) ? $this->matchedRoute->http_method : "get";
		if($this->reqMethod == $http_method) {
			$this->matchFound = true;
			$this->reqTypeMatched = true;
		} else {
			$this->matchFound = true;
			$this->reqTypeMatched = false;
			$this->reqTypeExpected = $http_method;
			$this->errorType = array("405");
		}
	}
}