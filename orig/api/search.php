<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));
// ANGULAR WILL SEND A NULL AS THE VERSION IF THE COOKIE IS NOT SET
// WE USE THAT HERE TO SET THE VERSION
$personalizedVersion = (isset($data->v)) ? $data->v : null;
$useBCPPsalms = (isset($data->b) && ($data->b == true) ) ? true : false;
// RETRIEVE PASSAGE, WHICH MUST EXIST
$passage = (isset($data->p)) ? $data->p : null;

if (!isset($passage)) {
    $this->qsOutput = json_encode(array("message"=>"No passage sent!"));
} else {
  // Clean passage before sending to the parser
    if (strstr($passage, ";")
      || strstr($passage, "'")
      || strstr($passage, "=")) {
        $this->qsOutput = json_encode(array("message"=>"The passage contains some illegal characters!"));
    } else {
        // // SET UP THE DATA CONNECTION
        $db = new \QuickShell\DML\Database();
        $p = new \BCP\Parser($db, $useBCPPsalms, $personalizedVersion);
        if (strstr($passage, "Psalm")) {
            $passage = trim(str_replace("Psalm", "", $passage));
            $p->parseReadings($passage, true);
        } else {
            $p->parseReadings($passage);
        }
        // $this->qsOutput = $p;
        $readings['search'][0] = $p;
        $readings['search_type'] = 'search';
        $out['search_readings'] = $readings;
        $this->qsOutput = json_encode($out);
    }
}
