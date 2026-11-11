<?php
namespace QuickShell\QuickShell;

use Exception;
/**
 * Request Class - Processes an HTTP request to determine the type, and to filter any elements passed in the URL
 * 
 * @author michaelseiler
 * @param boolean $api_call Boolean flag to indicate if a route belongs to an api
 * @param string $req_method HTTP Request Method taken from the $_SERVER superglobal
 * @param array $clean_req Array of elements that have been processed through the clean() method
 */
class Request
{
	public $apiCall			= null;
	public $reqMethod		= null;
	public $acceptType		= null;
	public $cleanReq		= null;
	/**
	 * Initializes the attributes
	 */
	public function __construct($http_method,$accept_type = null, $url_string = null)
	{
		$this->reqMethod = strtolower($http_method);
		$this->acceptType = strtolower($accept_type);
		self::extractElements($url_string);
	}
	/**
	 *  extracts URL elements from the request string
	 *  @param string GLOBAL $_REQUEST - the request
	 */
	public function extractElements($url_string = null)
	{
		$url_str = isset($url_string) ? $url_string : null;
		$elements = isset($url_str) ? explode("/",$url_str) : null;
		$clean_elements = array();
		if(!empty($elements)) {
			foreach($elements as $index=>$element) {
				try {
					if($element == "api") {
						$this->apiCall = true;
					} elseif($element == "") {
						continue;
					} else {
						$clean_elements[] = self::clean($element);
					}
				} catch (Exception $e) {
					echo "Error! Element [".$elements[$index]."] of \"$url_str\" is malformed! ".$e->getMessage();
					die();
				}
			}
		}
		$this->cleanReq = $clean_elements;
	}
	
	/**
	 *  clean function - checks that all elements passed in the URL are AlphaNumeric or _ (underscore) or 
	 *  - (hyphen) since we expect basic SEO type URL parameters in all requests 
	 *  @param string element String passed in the URL 
	 */
	public function clean($element)
	{	
		$pattern = "/(*UTF8)^[a-zA-Z0-9.\pL_-]+(\/)*$/u";
		preg_match($pattern, $element, $matches);
		if(!isset($matches[0])) {
			throw new Exception("Elements must be alphanumeric (_ and - and . are allowed).");
		} else {
			return $element;
		}
	}
}