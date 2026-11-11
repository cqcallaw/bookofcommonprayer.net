<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->version)) ? $data->version : null;
$personalizedPsalms = (isset($data->BCPPsalms)) ? $data->BCPPsalms : null;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();
// CHECK THE DATABASE FOR THE ID FROM THE URL ID
$hd = new \BCP\LectionaryHolyDayReadings($db, $data->id, $data->wl);

$readings['hd_name'] = $hd->hd_name;
$hd_liturgical_color_list = array(
  "1-18"=>"red",
  "1-25"=>"red",
  "2-2"=>"white",
  "2-24"=>"red",
  "3-25"=>"white",
  "4-25"=>"red",
  "5-1"=>"red",
  "5-31"=>"white",
  "6-11"=>"red",
  "6-24"=>"red",
  "6-29"=>"red",
  "7-4"=>"red",
  "7-22"=>"red",
  "7-25"=>"red",
  "8-6"=>"white",
  "8-15"=>"blue",
  "8-24"=>"red",
  "9-14"=>"red",
  "9-21"=>"red",
  "9-29"=>"red",
  "10-18"=>"red",
  "10-23"=>"red",
  "10-28"=>"red",
  "11-1"=>"white",
  "THK"=>"blue",
  "11-30"=>"red",
  "12-21"=>"red",
  "12-26"=>"red",
  "12-27"=>"red",
  "12-28"=>"red"
);
$readings['hd_liturgical_color'] = $hd_liturgical_color_list[$data->id];
// Old Testament Reading(s)
foreach ($hd->ot_list as $key => $ot_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $personalizedVersion);
    // The ot_type is used in the html template during loop
    $p->parseReadings(trim($ot_reading));
    if (!empty($hd->ot_joins[$key])) {
        $p->join = $hd->ot_joins[$key];
    }
    $readings['ot'][$key] = $p;
    $readings['ot_type'] = $hd->ot_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED TO NEXT READING
    $p = null;
    unset($p);
}
// New Testament Reaading(s)
foreach ($hd->nt_list as $key => $nt_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $personalizedVersion);
    $p->parseReadings($nt_reading);
    if (!empty($hd->nt_joins[$key])) {
        $p->join = $hd->nt_joins[$key];
    }
    $readings['nt'][$key] = $p;
    $readings['nt_type'] = $hd->nt_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// Gospel Reading(s)
foreach ($hd->gospel_list as $key => $gospel_reading) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $personalizedVersion);
    $p->parseReadings($gospel_reading);
    if (!empty($hd->gospel_joins[$key])) {
        $p->join = $hd->gospel_joins[$key];
    }
    $readings['gospel'][$key]= $p;
    $readings['gospel_type']= $hd->gospel_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}
// Psalm(s)
foreach ($hd->psalm as $key => $psalm) {
    // SET UP PARSERS
    $p = new \BCP\Parser($db, $personalizedPsalms, $personalizedVersion);
    $p->parseReadings($psalm, true);
    if (!empty($hd->psalm_joins[$key])) {
        $p->join = $hd->psalm_joins[$key];
    }
    $readings['psalm'][$key]= $p;
    $readings['psalm_type']= $hd->psalm_type;
    // DESTROY PARSER SO DATA NOT TRANSFERRED
    $p = null;
    unset($p);
}

$this->qsOutput = json_encode($readings);
