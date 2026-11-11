<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->version)) ? $data->version : null;
$personalizedPsalms = (isset($data->BCPPsalms)) ? false : true;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();
// CHECK THE DATABASE FOR THE ID FROM THE URL ID
$hd = new \BCP\DailyOfficeHolyDays($db, $data->hd_id);
foreach ($hd->result as $index => $hd_data) {
    if ($data->hd_time == $hd_data["hd_do_time"]) {
        $out["first"] = trim($hd_data["hd_do_first"]);
        $out["psalm"] = trim($hd_data["hd_do_psalm"]);
        $out["second"] = trim($hd_data["hd_do_second"]);
    }
}
$readings['psalm'][0] = $out['psalm'];
// This looks weird, but we need to be able to iterate over
// a parent item and the matching "passage" array in that parent
// in our Angular side of things, as we need object references
// for parentheses and brackets and such.
$p = new \BCP\Parser($db, $personalizedVersion);
$p->parseReadings($out["first"]);
$readings['first'][0] = $p;
unset($p);

$p2 = new \BCP\Parser($db, $personalizedVersion);
$p2->parseReadings($out["second"]);
$readings["second"][0] = $p2;
unset($p2);

// PSALMS
$psalm_array = explode(",", $out['psalm']);
foreach ($psalm_array as $key => $psalm) {
    // SET UP PARSERS
    $p3 = new \BCP\Parser($db, $personalizedVersion);
    $p3->parseReadings($psalm, true);
    $readings['psalm'][$key]= $p3;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p3 = null;
    unset($p3);
}

$this->qsOutput = json_encode($readings);
