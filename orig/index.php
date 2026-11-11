<?php
session_start();
/*  Web Framework using the Model-View-Controller concept
    Copyright (C) 2013  Mike Seiler
    http://www.linkedin.com/in/cmichaelseiler/
    http://www.github.com/cmikeseiler

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details: <http://www.gnu.org/licenses/>
*/
// QUICKSHELL INDEX PAGE (FRONTCONTROLLER)
// FIRST, GET THE GENERAL CONFIGURATION ITEMS
try {
    include_once("system/cfg/config.php");
} catch (Exception $e) {
    die("Unable to load configuration data!  Unable to load site.");
}
try {
    include_once(CLASS_DIR."SplAutoload.php");
    include_once(VENDOR."autoload.php");
} catch (Exception $e) {
    die("The Autoloader itself has not loaded.  Unable to continue with site load.");
}
// CHANGE THE ERROR DISPLAY SETTING IN THE CONFIG FILE
ini_set("display_errors", ERROR_SET);
// SET INCLUDE PATH TO ACCEPT THE VENDOR DIRECTORY FOR AUTOLOADING
set_include_path(CLASS_DIR.PATH_SEPARATOR.VENDOR);
$loader = new SplClassLoader();
$loader->register();
// GET THE PAGE "ID" FROM THE APACHE REWRITE
$http_method = $_SERVER['REQUEST_METHOD'];
$accept_type = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
// IF APPLICATION IS IN A SUBDIRECTORY WE NEED TO REMOVE IT FROM URI
$url_str = str_replace(APPLICATION, "", $_SERVER['REQUEST_URI']);
// BUILD THE APP AND RUN IT
//$bugsnag = Bugsnag\Client::make("BUGSNAG_API_KEY");
//Bugsnag\Handler::register($bugsnag);
$qs = new \QuickShell\QuickShell\QuickShell($http_method, $accept_type, $url_str);
$qs->run();
