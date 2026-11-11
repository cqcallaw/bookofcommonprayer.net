<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->version)) ? $data->version : null;
$personalizedPsalms = (isset($data->BCPPsalms)) ? false : true;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();
if (isset($data->dow) && ($data->dow != "")) {
    $dow = $data->dow;
    if (isset($data->dow) && ($data->dow == "null")) {
       $dow = date('w');
    }
} else {
    $dow = date('w');
}
$c = new \BCP\CanticlesByPrayerType($db, $data->wp, $data->wr, $dow, $data->season, $data->lang);

$this->qsOutput = json_encode($c);
