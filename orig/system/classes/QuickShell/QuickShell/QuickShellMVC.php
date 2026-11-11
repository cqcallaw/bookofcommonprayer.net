<?php
namespace QuickShell\QuickShell;

use Exception;
use DirectoryIterator;
/**
 * Class QuickShell - The main object that runs the MVC framework
 * @author Mike Seiler http://www.michaelseiler.net
 * @version	1.0
 * 
 * @param string $qsController The first element of the URL; controller
 * @param string $qsMethod The second element; method
 * @param array $qsArgs The remaining elements; array of items
 * @param string $qsContent HTML text string
 * @param string $qsPageTitle Page Title String
 * @param string $qsHeader Filename of header
 * @param string $qsFooter Filename of footer
 * @param string $qsTheme Directory where a theme's (dif't than default) header & footer information can be found
 * @param array $qsViewVars Variables stored for view
 */
class QuickShellMVC
{
	// CONSTANTS CAN BE FOUND IN THE CONFIG FILE
	public $qsController	= null;
	public $qsMethod		= null;
	public $qsArgs			= null;
	public $qsView			= null;
	public $qsContent		= null;
	public $qsPageTitle		= null;
	public $qsHeader		= null;
	public $qsFooter		= null;
	public $qsTheme			= DEFAULT_THEME;
    public $qsViewVars		= array();
	
	/**
	 * Constructor for the QuickShell Class.
	 * @param object $mvc	Either a ROUTE object or a REQUEST object so we can build the necessary elements
	 * if the parameter is null, we manually set all object attributes via buildDefault 
	 */
	public function __construct($mvc) 
	{
		if($mvc instanceof \QuickShell\QuickShell\Route) {
			self::buildVFR($mvc->matchedRoute);
		} elseif($mvc instanceof \QuickShell\QuickShell\Request) {
			self::buildUFO($mvc);
		} else {
			self::buildDefault();
		}
	}

	/**
	 * __get magic method
	 * @param string $key
	 */
	
	public function __get($key)
	{
		return $this->qsViewVars[$key];
	}
	
	/**
	 * __set magic method which assigns all variables
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		$this->qsViewVars[$key] = $value;
	}
	/**
	 * processController method - simple include of the controller file
	 */
	private function processController()
	{
		include(CONTROLLERS.$this->qsController.FILE_EXTENSION);
	}
	/** 
	 * buildDefault Method - included so that we can add additional HTTP Method adjustments in the future
	 * but to display we need a basic header, footer, and controller
	 */
	private function buildDefault()
	{
		self::setHF("header.php",true);
		self::setHF("footer.php");
		$this->qsController = "default";
	}
	/**
	 * buildVFR method - Builds the necessary elements for the MVC based on items collected from the defined routes. 
	 * e.g. if you have elements in a named route called name & id /welcome/:name/:id you'll end up with MVC object
	 * properties you can call with $this->name & $this->id from inside the controller
	 * @param object $matched_route StdClass object derived from JSON object in routes.php
	 */
	private function buildVFR($matched_route) 
	{
		$this->qsController = isset($matched_route->controller) ? $matched_route->controller : DEFAULT_CONTROLLER;
		$this->qsView = isset($matched_route->view) ? $matched_route->view : $this->qsController;
		$this->qsMethod = isset($matched_route->action) ? $matched_route->action : null;
		foreach($matched_route as $index=>$match_value) {
			if( ($index == "controller") || ($index == "view") || ($index == "action") ){
				continue;
			} else {
				$this->{$index} = $match_value;
			}
		}
		self::setTitle();
		self::processController();
	}
	/**
	 * buildUFO method - Builds the necessary elements for the MVC based on items collected from an unidentified URL
	 * @param
	 */
	private function buildUFO(\QuickShell\QuickShell\Request $request)
	{
		if(!empty($request->cleanReq)) {
			$numArgs = count($request->cleanReq);
			$this->qsArgs = Array();
			for($i = 0; $i < $numArgs; $i++) {
				if($i == 0) {
					$this->qsController = $request->cleanReq[$i];
				} elseif($i == 1) {
					$this->qsMethod = $request->cleanReq[$i];
				} else {
					$this->qsArgs[] = $request->cleanReq[$i];
				}
			}
			// IF THE CONTROLLER DOESN'T EXIST, SEE IF THERE IS A VIEW, AND USE THE DEFAULT CONTROLLER
			if(!file_exists(CONTROLLERS.$this->qsController.FILE_EXTENSION)) {
				if(file_exists(VIEWS.$this->qsController.VIEW_EXTENSION)) {
					$this->qsView = $this->qsController;
					$this->qsController = "default";
				} else {
					$this->qsController = "default";
					$this->qsView = "error";
				}
			}
		} else {
			$this->qsController = DEFAULT_CONTROLLER;
			$this->qsView = $this->qsController;
			$this->qsArgs = array();
		}
		self::setTitle();
		self::processController();
	}
	/**
	 * setHF method - Sets the header/footer to any file you specify (e.g. 'header.php', 
	 * 'subHeader.php', 'adminHeader.php', 'subSubFooter.php' etc)
	 * 
	 * @param string pageSection Filename of the file you wish to use as a header
	 * @param boolean isHeader Flag to indicate whether it's a header or footer
	 */
	public function setHF($fileName, $isHeader = null)
	{
		if(isset($isHeader)) { 
			$this->qsHeader = THEME_DIR.$this->qsTheme.$fileName;
		} else {
			$this->qsFooter = THEME_DIR.$this->qsTheme.$fileName;
		}
	}
	/**
	 * setTitle method - takes the processed UFO/IFO elements and sets the pageTitle Elements
	 */
	public function setTitle()
	{
		if($this->qsController == "default") {
			$this->qsPageTitle = ucfirst(str_replace("_"," ",$this->qsView));
		} else {
			$this->qsPageTitle = ucfirst(str_replace("_"," ",$this->qsController));
		}
	}
	/**
	 * processView method - processes a view, with a flag for whether to look in the externals folder.
	 * external as true means that it's a simple file to add in some bits of code: same name as the 
	 * directory (e.g. nav_bar\nav_bar.php)
	 * 
	 * all external namespaced modules/classes in the externals/plugins directory are handled by the autoloader
	 * if a class/snippet is not namespaced, require the class in the controller
	 * 
	 * @param string $filename	The name of the file to process
	 * @param boolean $external	Whether it is in the plugins directory, or in the views directory
	 * @return string|boolean	Results vary; either string flush or false
	 */
	private function processView($filename,$external = false) 
	{
		if ($external != FALSE) {
			$loc = VENDOR."$filename".DIRECTORY_SEPARATOR."$filename";
			$filename = $loc.FILE_EXTENSION;
		}
		if (file_exists($filename)) {
			extract($this->qsViewVars);
			ob_start();
			include $filename;
			return ob_get_clean();
		}
		return false;
	}
	
	/**
	 * render method - concats the content to be sent to the browser
	 * 
	 */
	public function render()
	{
		if($this->qsView == "error") {
			header("HTTP/1.0 404 Page Not Found");
		} elseif($this->qsView == "forbidden") {
			header("HTTP/1.0 403 Not Authorized");
		}
		if(isset($this->qsView)) {
			$this->qsContent = self::processView(VIEWS.$this->qsView.VIEW_EXTENSION);
		} else {
			$this->qsContent = self::processView(VIEWS.$this->qsController.VIEW_EXTENSION);
		}
		echo self::processView($this->qsHeader).$this->qsContent.self::processView($this->qsFooter);
	}
	
	/**
	 * getPath method - create the path base for header files; assumes all files are theme 
	 * specific unless calling vendor JS
	 * 
	 * @param string $filename The name of the file to add in the html header
	 * @param string $type The type of file; e.g. 'css' or 'prettyJs'
	 * @param int $isVendor Indicator of whether the file is one of the Vendor specific JS files
	 */
	public function getPath($filename,$type,$isVendor = null) 
	{
		$isVendor = (isset($isVendor)) ? $isVendor : 0;
		$path = SERVER;
		switch ($isVendor) {
			case 0:
				$path .= THEME_DIR.$this->qsTheme;
				break;
			case 1:
				$path .= JS_DIR."vendor/";
			break;
			default:
			$path .= THEME_DIR.$this->qsTheme;
			break;
		}
		switch ($type) {
			case "prettyJs":
				if($isVendor == 0) {
					$path .= THEME_JS;
				}
			break;
			case "css":
				$path .= THEME_CSS;
			break;
			case "img":
				$path .= THEME_IMAGES;
			break;
		}
		$path .= $filename;
		echo $path;
	}
}