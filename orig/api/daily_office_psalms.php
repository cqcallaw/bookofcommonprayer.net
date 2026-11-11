<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

// // SET UP THE DATA CONNECTION
$db = new \QuickShell\DML\Database();
// SET UP THE DATA TO RETRIEVE A POSSIBLE HOLY DAY
$type = ($data->type == 'daytime') ? 'daytime' : 'chapter';

// CHECK THE DATABASE FOR THE ID WE'VE RETRIEVED
$hd = new \BCP\DailyOfficePsalms($db, $data, $type);

$this->qsOutput = json_encode($hd->result);
