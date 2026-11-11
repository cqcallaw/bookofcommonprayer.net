<?php
// Get the JSON payload from the Angular script
// Don't need it now, but will when I put the o_auth security in
//$data = json_decode(file_get_contents('php://input'));

$c = getConclusion();

$this->qsOutput = json_encode($c);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getConclusion()
{
    $which = rand(0, 2);
    $conclude[0] = array("text"=>"The grace of our Lord Jesus Christ, and the
    love of God, and the fellowship of the Holy Ghost, be with us all evermore.
    =Amen.=","reference"=>"2 Corinthians 13:14");
    $conclude[1] = array("text"=>"May the God of hope fill us with all joy and peace in
    believing through the power of the Holy Spirit. =Amen.=","reference"=>"Romans 15:13");
    $conclude[2] = array("text"=>"Glory to God whose power, working in us, can do infinitely
    more than we can ask or imagine:  Glory to him from
    generation to generation in the Church, and in Christ Jesus
    for ever and ever. =Amen.=","reference"=>"Ephesians 3:20,21");
    return $conclude[$which];
}
