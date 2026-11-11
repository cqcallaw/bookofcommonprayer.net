<?php
// Get the JSON payload from the Angular script
//$data = json_decode(file_get_contents('php://input'));

$cc = getComplinePrayer();

$this->qsOutput = json_encode($cc);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getComplinePrayer()
{
    $it = rand(0, 1);
    $pray[0] = array("Keep watch, dear Lord, with those who work, or watch, or
    weep this night, and give your angels charge over those who
    sleep.  Tend the sick, Lord Christ; give rest to the weary, bless
    the dying, soothe the suffering, pity the afflicted, shield the
    joyous; and all for your love's sake.  =Amen.=");
    $pray[1] = array("O God, your unfailing providence sustains the world we live
    in and the life we live:  Watch over those, both night and day,
    who work while others sleep, and grant that we may never
    forget that our common life depends upon each other's toil;
    through Jesus Christ our Lord.  =Amen.=");
    $returnValue = $pray[$it];
    return $returnValue;
}
