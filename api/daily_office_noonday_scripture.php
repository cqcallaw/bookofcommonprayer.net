<?php
// Get the JSON payload from the Angular script
//$data = json_decode(file_get_contents('php://input'));

$ns = getNoondayScripture();

$this->qsOutput = json_encode($ns);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getNoondayScripture()
{
    $which = rand(0, 2);
    $script[0] = array("text"=>"The love of God has been poured into our
    hearts through the Holy Spirit that has been given to us."
    ,"reference"=>"Romans 5:5");
    $script[1] = array("text"=>"If anyone is in Christ he is a new
    creation; the old has passed away, behold the new has come.
    All this is from God, who through Christ reconciled us to
    himself and gave us the ministry of reconciliation."
    ,"reference"=>"2 Corinthians 5:17-18");
    $script[2] = array("text"=>"From the rising of the sun to its
    setting my Name shall be great among the nations, and in every
    place incense shall be offered to my Name, and a pure offering;
    for my Name shall be great among the nations, says the Lord
    of Hosts."
    ,"reference"=>"Malachi 1:11");
    $returnValue = $script[$which];
    return $returnValue;
}
