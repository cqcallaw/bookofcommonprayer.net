<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->v)) ? $data->v : null;
$useBCPPsalms = (isset($data->BCPPsalms) && ($data->BCPPsalms == true)) ? true : false;
// $useBCPPsalms = true;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();
// SET UP THE DATA TO RETRIEVE READING LISTS
$dow = (isset($data->dow) && ($data->dow != "")) ? $data->dow : null;
$do_data = array($data->year, $data->id, $dow);
$dor = new \BCP\DailyOfficeReadings($db, $do_data);

// is_mp is used when retrieving canticle numbers for morning Prayer
// based on day of week according to the table of suggested canticles
$is_mp = (strstr("morning_prayer", $data->wp)) ? 1 : 0;

$ot_readings = array();
// DOR returns an array of OT readings,  even if only one element
// DOR also returns whether a reading is an && or OR reading
//
// Old Testament Reading(s)
foreach ($dor->ot_list as $key => $ot_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    // The ot_type is used in the html template during loop
    $p->parseReadings($ot_reading);
    $readings['ot'][$key] = $p;
    $readings['ot_type'] = $dor->ot_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED TO NEXT READING
    $p = null;
    unset($p);
}
// New Testament Reaading(s)
foreach ($dor->nt_list as $key => $nt_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    $p->parseReadings($nt_reading);
    $readings['nt'][$key] = $p;
    $readings['nt_type'] = $dor->nt_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// Gospel Reading(s)
foreach ($dor->gospel_list as $key => $gospel_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    $p->parseReadings($gospel_reading);
    $readings['gospel'][$key]= $p;
    $readings['gospel_type']= $dor->gospel_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// Morning Psalms
foreach ($dor->m_psalms as $key => $psalm) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    $p->parseReadings($psalm, true);
    $readings['morning_psalms'][$key]= $p;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// Evening psalms
foreach ($dor->e_psalms as $key => $psalm) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
    $p->parseReadings($psalm, true);
    $readings['evening_psalms'][$key]= $p;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// TODO: need to get the BCPPsalms version in somehow
$out['do_readings'] = $readings;
$out['personalizedPsalms'] = $useBCPPsalms;
$this->qsOutput = json_encode($out);
