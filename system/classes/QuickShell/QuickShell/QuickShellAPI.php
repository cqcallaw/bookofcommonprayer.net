<?php
namespace QuickShell\QuickShell;

use Exception;
use DirectoryIterator;
/**
 * Class QuickShellAPI - The API object that runs the MVC framework API Layer
 * @author Mike Seiler http://www.michaelseiler.net
 * @version	1.0
 * 
 * @param string $qsController The first element of the URL; controller
 * @param string $qsMethod The second element; method
 * @param array $qsArgs The remaining elements; array of items
 * @param array $qsVars Variables stored for view
 */
class QuickShellAPI
{
	// CONSTANTS CAN BE FOUND IN THE CONFIG FILE
	public $qsController	= null;
    public $qsVars			= array();
    public $qsOutput		= null;
	
	/**
	 * Constructor for the QuickShell Class.
	 * @param object $mvc	Either a ROUTE object or a REQUEST object so we can build the necessary elements
	 * if the parameter is null, we manually set all object attributes via buildDefault 
	 */
	public function __construct($api) 
	{
		foreach($api->matchedRoute as $idx=>$var) {
			if($idx == "controller") {
				$this->qsController = $api->matchedRoute->controller;
			} else {
				$this->qsVars[$idx] = $var;
			}
		}
		self::processController();
	}

	/**
	 * __get magic method
	 * @param string $key
	 */
	
	public function __get($key)
	{
		return $this->qsVars[$key];
	}
	
	/**
	 * __set magic method which assigns all variables
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		$this->qsVars[$key] = $value;
	}
	/**
	 * processController method - simple include of the controller file
	 */
	private function processController()
	{
		$file = APIS.$this->qsController.FILE_EXTENSION;
		if(file_exists($file)) {
			extract($this->qsVars);
			ob_start();
			include $file;
			ob_get_clean();
		}
	}
	
	/**
	 * public function render - process the data returned by the api call and spit it out
	 * 
	 */
	public function render()
	{
		echo $this->qsOutput;
	}
}