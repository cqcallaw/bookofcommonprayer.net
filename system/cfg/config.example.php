<?php
//////////////////////////////////////////////
// QUICKSHELL CONFIGURATION FILE
// Copy to config.php. The real config.php is not tracked.
// Docker mounts dev/config.php over it.
//////////////////////////////////////////////
define("ADMIN_EMAIL","bcpmike@bookofcommonprayer.net");
define("APPLICATION", "");
define("SERVER","//www.bookofcommonprayer.net/");
define("SITE_NAME","Online Book of Common Prayer");
define("DEFAULT_CONTROLLER","welcome");
define("FILE_EXTENSION",".php");
define("VIEW_EXTENSION",".phtml");
define("DEFAULT_THEME","bcp/");
define("THEME_JS","js/");
define("THEME_CSS","css/");
define("THEME_IMAGES","images/");
define("DB_HOST","localhost");
define("DB_USER","n773365_bocpdb");
define("DB_PASS","change-me");
define("DB_NAME","n773365_bocpdb");
define("DB_DRIVER","mysql");
define("ERROR_SET",E_ALL);
define("PUBLIC_DIR","public/");
define("IMG_DIR",PUBLIC_DIR."images/");
define("FILES_DIR",PUBLIC_DIR."files/");
define("THEME_DIR",PUBLIC_DIR."themes/");
define("JS_DIR",PUBLIC_DIR."js/");
define("VIEWS",PUBLIC_DIR."views/");
define("APIS","api/");
define("CONTROLLERS","controllers/");
define("VENDOR","vendor/");
define("SYSTEM_DIR","system/");
define("CLASS_DIR",SYSTEM_DIR."classes/");
