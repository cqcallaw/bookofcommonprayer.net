<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->version)) ? $data->version : null;
$personalizedPsalms = (isset($data->BCPPsalms)) ? false : true;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();
$cod = new \BCP\CollectOfTheDay($db, $data->wr, $data->id, $data->dow, $data->lang, $data->wc);

$this->qsOutput = json_encode($cod);
