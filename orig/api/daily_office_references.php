<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$type = (isset($data->type)) ? $data->type : null;
// $tz = (isset($data->tz)) ? $data->tz : null;
$year = (isset($data->year)) ? $data->year : date("Y");

$db = new \QuickShell\DML\Database();

// 1. Get the CC object and look at the sundays.
$cc = new \BCP\ChurchCalendar('daily_office', $year);
$do = new \BCP\DailyOffice($cc);
// 2. Do what we do in the lectionary by date link, and order the sundays
$flipped_array = array_flip($cc->sundays);
foreach ($flipped_array as $id => $date) {
    $date = str_replace("-", " ", $date);
    $d = new DateTime($date);
    $flipped_array[$id] = $d->format('l, F d, Y');
}

$sunday_details = array(
"1A" => array("date" => $flipped_array["1A"],"id" => "1A"),
"2A" => array("date" => $flipped_array["2A"],"id" => "2A"),
"3A" => array("date" => $flipped_array["3A"],"id" => "3A"),
"4A" => array("date" => $flipped_array["4A"],"id" => "4A"),
"CM" => array("date" => $flipped_array["CM"],"id" => "CM"),
"1C" => array("date" => isset($flipped_array["1C"]) ? $flipped_array["1C"] : null,"id" => "1C"),
"2C" => array("date" => isset($flipped_array["2C"]) ? $flipped_array["2C"] : null,"id" => "2C"),
"HN" => array("date" => $flipped_array["HN"],"id" => "HN"),
"E" => array("date" => $flipped_array["E"],"id" => "E"),
"1E" => array("date" => $flipped_array["1E"],"id" => "1E"),
"2E" => array("date" => $flipped_array["2E"],"id" => "2E"),
"3E" => array("date" => $flipped_array["3E"],"id" => "3E"),
"4E" => array("date" => $flipped_array["4E"],"id" => "4E"),
"5E" => array("date" => $flipped_array["5E"],"id" => "5E"),
"6E" => array("date" => isset($flipped_array["6E"]) ? $flipped_array["6E"] : null,"id" => "6E"),
"7E" => array("date" => isset($flipped_array["7E"]) ? $flipped_array["7E"] : null,"id" => "7E"),
"8E" => array("date" => isset($flipped_array["8E"]) ? $flipped_array["8E"] : null,"id" => "8E"),
"LE" => array("date" => $flipped_array["LE"],"id" => "LE"),
"1L" => array("date" => $flipped_array["1L"],"id" => "1L"),
"2L" => array("date" => $flipped_array["2L"],"id" => "2L"),
"3L" => array("date" => $flipped_array["3L"],"id" => "3L"),
"4L" => array("date" => $flipped_array["4L"],"id" => "4L"),
"5L" => array("date" => $flipped_array["5L"],"id" => "5L"),
"PS" => array("date" => $flipped_array["PS"],"id" => "PS"),
"R" => array("date" => $flipped_array["R"],"id" => "R"),
"2R" => array("date" => $flipped_array["2R"],"id" => "2R"),
"3R" => array("date" => $flipped_array["3R"],"id" => "3R"),
"4R" => array("date" => $flipped_array["4R"],"id" => "4R"),
"5R" => array("date" => $flipped_array["5R"],"id" => "5R"),
"6R" => array("date" => $flipped_array["6R"],"id" => "6R"),
"7R" => array("date" => $flipped_array["7R"],"id" => "7R"),
"P" => array("date" => $flipped_array["P"],"id" => "P"),
"T" => array("date" => $flipped_array["T"],"id" => "T"),
"1" => array("date" => isset($flipped_array["1"]) ? $flipped_array["1"] : null,"id" => "1"),
"2" => array("date" => isset($flipped_array["2"]) ? $flipped_array["2"] : null,"id" => "2"),
"3" => array("date" => isset($flipped_array["3"]) ? $flipped_array["3"] : null,"id" => "3"),
"4" => array("date" => isset($flipped_array["4"]) ? $flipped_array["4"] : null,"id" => "4"),
"6" => array("date" => isset($flipped_array["5"]) ? $flipped_array["5"] : null,"id" => "5"),
"5" => array("date" => $flipped_array["6"],"id" => "6"),
"7" => array("date" => $flipped_array["7"],"id" => "7"),
"8" => array("date" => $flipped_array["8"],"id" => "8"),
"9" => array("date" => $flipped_array["9"],"id" => "9"),
"10" => array("date" => $flipped_array["10"],"id" => "10"),
"11" => array("date" => $flipped_array["11"],"id" => "11"),
"12" => array("date" => $flipped_array["12"],"id" => "12"),
"13" => array("date" => $flipped_array["13"],"id" => "13"),
"14" => array("date" => $flipped_array["14"],"id" => "14"),
"15" => array("date" => $flipped_array["15"],"id" => "15"),
"16" => array("date" => $flipped_array["16"],"id" => "16"),
"17" => array("date" => $flipped_array["17"],"id" => "17"),
"18" => array("date" => $flipped_array["18"],"id" => "18"),
"19" => array("date" => $flipped_array["19"],"id" => "19"),
"20" => array("date" => $flipped_array["20"],"id" => "20"),
"22" => array("date" => $flipped_array["21"],"id" => "21"),
"21" => array("date" => $flipped_array["22"],"id" => "22"),
"23" => array("date" => $flipped_array["23"],"id" => "23"),
"24" => array("date" => $flipped_array["24"],"id" => "24"),
"25" => array("date" => $flipped_array["25"],"id" => "25"),
"26" => array("date" => $flipped_array["26"],"id" => "26"),
"27" => array("date" => $flipped_array["27"],"id" => "27"),
"28" => array("date" => $flipped_array["28"],"id" => "28"),
"29" => array("date" => $flipped_array["29"],"id" => "29")
);
// 3. For each sunday, we need the ID, so that we can get the weekly references
//    for each day of the week within that list.
$cc->orderedSundays = array();
foreach ($sunday_details as $s) {
  // Get the $do->do_yearNo; and pass it to the references class
  // Also pass the ID from the $s - either as $s['id'] or do a $id->$s
    $do_id = $s['id'];
  // if there is no date for the existing ID, then we skip this...
    if ($s['date'] != null) {
        $do_ref = new \BCP\DailyOfficeReferences(
            $db,
            $do->do_yearNo,
            $do_id,
            array($cc->pentecost_proper, $cc->trinity_proper)
        );
        $s['do_week'] = $do_ref->resultSet;
        array_push($cc->orderedSundays, $s);
    }
}

// $out = array("CC" => $cc,"DOREF" => $doref);
$out = array("weeks" => $cc->orderedSundays,"DO" => $do);
$this->qsOutput = json_encode($out);
