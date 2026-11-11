<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->v)) ? $data->v : null;
$useBCPPsalms = (isset($data->BCPPsalms) && ($data->BCPPsalms == true) ) ? true : false;
// $useBCPPsalms = false;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();

// TODO: get the readings and the prayers and the collects based on the
// JSON payload passed to the system:
// {"year":"C","id":"24","wl":"BCP","wp":"C"}
// Year, ID, Lectionary Preference, and Type of Prayer
$readings = array();
// Get the Readings based on Lectionary
$lectionary_table = ($data->wl == "RCL") ? "RCL_sunday_lectionary":"sunday_lectionary";
$lectionary_args = array($data->year, $data->id, $lectionary_table);
$lectionary = new \BCP\LectionaryReadings($db, $lectionary_args);

// LR returns an array of OT readings, even if only one element
// LR also returns whether a reading is an && or OR reading
//
// Old Testament Reading(s)
foreach ($lectionary->ot_list as $key => $ot_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    // The ot_type is used in the html template during loop
    $p->parseReadings($ot_reading);
    $readings['ot'][$key] = $p;
    $readings['ot_type'] = $lectionary->ot_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED TO NEXT READING
    $p = null;
    unset($p);
}
// New Testament Reaading(s)
foreach ($lectionary->nt_list as $key => $nt_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    $p->parseReadings($nt_reading);
    $readings['nt'][$key] = $p;
    $readings['nt_type'] = $lectionary->nt_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// Gospel Reading(s)
foreach ($lectionary->gospel_list as $key => $gospel_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    $p->parseReadings($gospel_reading);
    $readings['gospel'][$key]= $p;
    $readings['gospel_type']= $lectionary->gospel_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// Psalm(s)
foreach ($lectionary->psalm as $key => $psalm) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    $p->parseReadings($psalm, true);
    $readings['psalm'][$key]= $p;
    $readings['psalm_type']= $lectionary->psalm_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}

//$this->qsOutput = json_encode($readings);
$this->qsOutput = json_encode($readings);
