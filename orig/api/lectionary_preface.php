<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->version)) ? $data->version : null;
$personalizedPsalms = (isset($data->BCPPsalms)) ? false : true;
// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();

// Since the ID of the Sunday may not match the preface used, we
// need to run it through an array (i.e. 3E needs the Preface of Epiphany)
// $prefaceIDs = array(
//     "1A"=>"A",
//     "2A"=>"A",
//     "3A"=>"A",
//     "4A"=>"A",
//     "CM"=>"I",
//     "CMII"=>"I",
//     "CMIII"=>"I",
//     "1C"=>"I",
//     "2C"=>"I",
//     "HN"=>"I",
//     "E"=>"E",
//     "1E"=>"E",
//     "2E"=>"E",
//     "3E"=>"E",
//     "4E"=>"E",
//     "5E"=>"E",
//     "6E"=>"E",
//     "7E"=>"E",
//     "8E"=>"E",
//     "LE"=>"E",
//     "AW"=>"L",
//     "1L"=>"L",
//     "2L"=>"L",
//     "3L"=>"L",
//     "4L"=>"L",
//     "5L"=>"L",
//     "PS"=>"HW",
//     "PS-1"=>"HW",
//     "PS-2"=>"HW",
//     "PS-3"=>"HW",
//     "PS-4"=>"HW",
//     "PS-5"=>"HW",
//     "PS-6"=>"HW",
//     "R"=>"R",
//     "R-1"=>"R",
//     "R-2"=>"R",
//     "R-3"=>"R",
//     "R-4"=>"R",
//     "R-5"=>"R",
//     "R-6"=>"R",
//     "2R"=>"R",
//     "3R"=>"R",
//     "4R"=>"R",
//     "5R"=>"R",
//     "6R"=>"R",
//     "AD"=>"AD",
//     "7R"=>"R",
//     "P"=>"P",
//     "T"=>"T",
//     "1"=>"LD",
//     "2"=>"LD",
//     "3"=>"LD",
//     "4"=>"LD",
//     "6"=>"LD",
//     "5"=>"LD",
//     "7"=>"LD",
//     "8"=>"LD",
//     "9"=>"LD",
//     "10"=>"LD",
//     "11"=>"LD",
//     "12"=>"LD",
//     "13"=>"LD",
//     "14"=>"LD",
//     "15"=>"LD",
//     "16"=>"LD",
//     "17"=>"LD",
//     "18"=>"LD",
//     "19"=>"LD",
//     "20"=>"LD",
//     "22"=>"LD",
//     "21"=>"LD",
//     "23"=>"LD",
//     "24"=>"LD",
//     "26"=>"LD",
//     "27"=>"LD",
//     "28"=>"LD",
//     "29"=>"LD"
// );

//$id = $prefaceIDs[$data->id];
$readings = array();
// Get the Readings based on Lectionary
$preface = new \BCP\Preface($db, $data->id, $data->wp, $data->lang);

$this->qsOutput = json_encode($preface);
