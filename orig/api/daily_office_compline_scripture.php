<?php
// Get the JSON payload from the Angular script
//$data = json_decode(file_get_contents('php://input'));

$cs = getComplineScripture();

$this->qsOutput = json_encode($cs);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getComplineScripture()
{
    $which = rand(0, 3);
    $cw[0] = array("text"=>"Lord, you are in the midst of us, and we are called by your
    Name:  Do not forsake us, O Lord our God."
    ,"reference"=>"Jeremiah 14:9,22");
    $cw[1] = array("text"=>"Come to me, all who labor and are heavy-laden, and I will
    give you rest.  Take my yoke upon you, and learn from me;
    for I am gentle and lowly in heart, and you will find rest for
    your souls.  For my yoke is easy, and my burden is light."
    ,"reference"=>"Matthew 11:28-30");
    $cw[2] = array("text"=>"May the God of peace, who brought again from the dead our
    Lord Jesus, and great shepherd of the sheep, by the blood of
    the eternal covenant, equip you with everything good that you
    may do his will, working in you that which is pleasing in his
    sight; through Jesus Christ, to whom be glory for ever and
    ever."
    ,"reference"=>"Hebrews 13:20-21");
    $cw[3] = array("text"=>"Be sober, be watchful.  Your adversary the devil prowls
    around like a roaring lion, seeking someone to devour.
    Resist him, firm in your faith."
    ,"reference"=>"1 Peter 5:8-9a");
    $returnValue = $cw[$which];
    return $returnValue;
}
