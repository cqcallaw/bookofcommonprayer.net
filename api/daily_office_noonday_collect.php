<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$nc = getNoondayCollect($data->wr);

$this->qsOutput = json_encode($nc);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getNoondayCollect($which_rite)
{
    if ($which_rite == "rite_i") {
        $which = rand(0, 2);
        $coll[0] = array("Heavenly Father, send your Holy Spirit into our hearts, to
        direct and rule us according to your will, to comfort us in all
        our afflictions, to defend us from all error, and to lead us into
        all truth; through Jesus Christ our Lord.  =Amen.=");
        $coll[1] = array("Blessed Savior, at this hour you hung upon the cross,
        stretching out your loving arms:  Grant that all the peoples of
        the earth may look to you and be saved;  for your tender
        mercies' sake.  =Amen.=");
        $coll[2] = array("Almighty Savior, who at noonday called your servant Saint
        Paul to be an apostle to the Gentiles:  We pray you to illumine
        the world with the radiance of your glory, that all nations
        may come and worship you; for you live and reign for ever
        and ever.  =Amen.=");
        $coll[3] = array("Lord Jesus Christ, you said to your apostles, 'Peace I give to
        you; my peace I leave with you:' Regard not our sins,
        but the faith of your Church, and give to us the peace and
        unity of that heavenly city, where with the Father and the
        Holy Spirit you live and reign, now and for ever. =Amen.=");
        $returnValue = $coll[$which];
    } elseif ($which_rite == "rite_ii") {
        $which = rand(0, 2);
        $returnValue = $coll[$which];
    }
    return $returnValue;
}
