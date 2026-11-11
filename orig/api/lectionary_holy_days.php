<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->version)) ? $data->version : null;
$personalizedPsalms = (isset($data->BCPPsalms)) ? false : true;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();
// SET UP THE DATA TO RETRIEVE A POSSIBLE HOLY DAY
$today = $data->today;
// CHECK THE DATABASE FOR THE ID WE'VE RETRIEVED
$hd = new \BCP\LectionaryHolyDays($db, $today);
//$hd = new \BCP\DailyOfficeHolyDays($db,"6-11");

$this->qsOutput = json_encode($hd->result);
