<?php
// Get the JSON payload from the Angular script
//$data = json_decode(file_get_contents('php://input'));

$cc = getComplineCollect();

$this->qsOutput = json_encode($cc);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getComplineCollect()
{
    $which = rand(0, 3);
    $coll[0] = array("Be our light in the darkness, O Lord, and in your great mercy
    defend us from all perils and dangers of this night; for the love of your
    only Son, our Savior Jesus Christ.  =Amen.=");
    $coll[1] = array("Be present, O merciful God, and protect us through the hours
    of this night, so that we who are wearied by the changes and
    chances of this life may rest in your eternal changelessness;
    through Jesus Christ our Lord.  =Amen.=");
    $coll[2] = array("Look down, O Lord, from your heavenly throne, and
    illumine this night with your celestial brightness; that by
    night as by day your people may glorify your holy Name;
    through Jesus Christ our Lord.  =Amen.=");
    $coll[3] = array("Visit this place, O Lord, and drive far from it all snare of the
    enemy; let your holy angels dwell with us to preserve us in
    peace; and let your blessing be upon us always; through Jesus
    Christ our Lord.  =Amen.=");
    $returnValue = $coll[$which];
    return $returnValue;
}
