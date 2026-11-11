<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$pm = getPrayerForMission($data->wp, $data->wr);

$this->qsOutput = json_encode($pm);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getPrayerForMission($which_prayer, $which_rite)
{
    if ($which_prayer == "morning_prayer") {
        if ($which_rite == "rite_i") {
            $which = rand(0, 2);
            $mission[0] = array(
            "Almighty and everlasting God, by whose Spirit the whole
            body of thy faithful people is governed and sanctified:
            Receive our supplications and prayers which we offer before
            thee for all members of thy holy Church, that in their
            vocation and ministry they may truly and godly serve thee;
            through our Lord and Savior Jesus Christ.  =Amen.=");
            $mission[1] = array(
            "O God, who hast made of one blood all the peoples of the
            earth, and didst send thy blessed Son to preach peace to those
            who are far off and to those who are near:  Grant that people
            everywhere may seek after thee and find thee; bring the
            nations into thy fold; pour out thy Spirit upon all flesh; and
            hasten the coming of thy kingdom; through the same thy
            Son Jesus Christ our Lord.  =Amen.=");
            $mission[2] = array(
            "Lord Jesus Christ, who didst stretch out thine arms of love
            on the hard wood of the cross that everyone might come
            within the reach of thy saving embrace:  So clothe us in thy
            Spirit that we, reaching forth our hands in love, may bring
            those who do not know thee to the knowledge and love of
            thee; for the honor of thy Name.  =Amen.=");
            $returnValue = $mission[$which];
        } elseif ($which_rite == "rite_ii") {
            $which = rand(0, 2);
            $mission[0] =array(
            "Almighty and everlasting God, by whose Spirit the whole
            body of your faithful people is governed and sanctified:
            Receive our supplications and prayers which we offer before
            you for all members of your holy Church, that in their
            vocation and ministry they may truly and devoutly serve you;
            through our Lord and Savior Jesus Christ.  =Amen.=");
            $mission[1] = array(
            "O God, you have made of one blood all the peoples of the
            earth, and sent your blessed Son to preach peace to those
            who are far off and to those who are near:  Grant that people
            everywhere may seek after you and find you; bring the
            nations into your fold; pour out your Spirit upon all flesh,
            and hasten the coming of your kingdom; through Jesus
            Christ our Lord.  =Amen.=");
            $mission[2] = array(
            "Lord Jesus Christ, you stretched out your arms of love on
            the hard wood of the cross that everyone might come within
            the reach of your saving embrace:  So clothe us in your Spirit
            that we, reaching forth our hands in love, may bring those
            who do not know you to the knowledge and love of you; for
            the honor of your Name.  =Amen.=");
            $returnValue = $mission[$which];
        }
    } elseif ($which_prayer == "evening_prayer") {
        if ($which_rite == "rite_i") {
            $which =  rand(0, 2);
            $mission[0] = array("O God and Father of all, whom the whole heavens adore:
            Let the whole earth also worship thee, all nations obey thee,
            all tongues confess and bless thee, and men and women every-
            where love thee and serve thee in peace; through Jesus Christ
            our Lord. =Amen.=");
            $mission[1] = array("Keep watch, dear Lord, with those who work, or watch, or
            weep this night, and give thine angels charge over those who
            sleep. Tend the sick, Lord Christ; give rest to the weary, bless
            the dying, soothe the suffering, pity the afflicted, shield the
            joyous; and all for thy love's sake. =Amen.=");
            $mission[2] = array("O God, who dost manifest in thy servants the signs of thy
            presence: Send forth upon us the Spirit of love, that in
            companionship with one another thine abounding grace may
            increase among us; through Jesus Christ our Lord. =Amen.=");
        } elseif ($which_rite == "rite_ii") {
            $which =  rand(0, 2);
            $mission[0] = array("O God and Father of all, whom the whole heavens adore:
            Let the whole earth also worship you, all nations obey you,
            all tongues confess and bless you, and men and women
            everywhere love you and serve you in peace; through Jesus
            Christ our Lord. =Amen.=");
            $mission[1] = array("Keep watch, dear Lord, with those who work, or watch, or
            weep this night, and give your angels charge over those who
            sleep.  Tend the sick, Lord Christ; give rest to the weary, bless
            the dying, soothe the suffering, pity the afflicted, shield the
            joyous; and all for your love's sake. =Amen.=");
            $mission[2] = array("O God, you manifest in your servants the signs of your
            presence:  Send forth upon us the spirit of love, that in
            companionship with one another your abounding grace may
            increase among us; through Jesus Christ our Lord. =Amen.=");
            $returnValue = $mission[$which];
        }
        $returnValue = $mission[$which];
    }
    return $returnValue;
}
