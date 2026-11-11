<?php
$directory = VIEWS;
$excludes = array(DEFAULT_CONTROLLER,"forbidden","error","login","bad_method");
// ITERATE OVER THE DIRECTORY
$l = new DirectoryIterator($directory);
// SET UP THE UL/LI FOR THE CSS STYLING
$links = "<ul>";
// FORCE PRINT DEFAULT CONTROLLER, SO THERE IS AT LEAST ONE NAV ITEM
$niceLink = str_replace("_", " ", DEFAULT_CONTROLLER);
$niceLink = ucwords($niceLink);
$links .= "<li><a href=\"".SERVER.DEFAULT_CONTROLLER."\"";
if ($this->qsController == DEFAULT_CONTROLLER) {
    $links .= " class=\"active\"";
}
$links .= ">$niceLink</a></li>";
// LOOP THROUGH THE REST OF THE FILES THAT ARE NOT EXLUDED
foreach ($l as $file) {
    if ($directory == VIEWS) {
        $file = str_replace(VIEW_EXTENSION, "", $file);
    } else {
        $file = str_replace(FILE_EXTENSION, "", $file);
    }
    if (!$l->isDot()) {
        if (in_array("$file", $excludes)) {
            continue;
        } else {
            $niceLink = str_replace("_", " ", $file);
            $niceLink = ucwords($niceLink);
            $links .= "<li><a href=\"".SERVER."$file\"";
            if ($this->qsController == $file) {
                $links .= " class=\"active\"";
            }
            $links .= ">$niceLink</a></li>";
            if (isset($auth)) {
                if (isset($_SESSION["isAuthenticated"])) {
                    $links .= "<li><a href=\"".SERVER."logout\">Logout</a></li>";
                } else {
                    $links .= "<li><a href=\"".SERVER."login\">Log In</a></li>";
                }
            }
        }
    }
}
$links .= "</ul>";
echo $links;
