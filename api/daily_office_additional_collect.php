<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$ac = getAdditionalCollect($data->wr, $data->wp, $data->dow);

$this->qsOutput = json_encode($ac);

function getAdditionalCollect($which_rite, $which_prayer, $do_dow)
{
    if ($which_prayer == "morning_prayer") {
        if ($which_rite == "rite_i") {
            if ($do_dow == "0") {
                $returnValue = array("name"=>"A Collect for Sundays"
                , "text" => "O God, who makest us glad with the weekly remembrance of
                the glorious resurrection of thy Son our Lord:  Grant us this
                day such blessing through our worship of thee, that the days
                to come may be spent in thy favor; through Jesus
                Christ our Lord.  =Amen.=");
            } elseif ($do_dow == "5") {
                  $returnValue = array("name"=>"A Collect for Fridays"
                  , "text" => "Almighty God, whose most dear Son went not up to joy but
                first he suffered pain, and entered not into glory before he
                was crucified:  Mercifully grant that we, walking in the way
                of the cross, may find it none other than the way of life and
                peace; through Jesus Christ your Son our Lord.  =Amen.=");
            } elseif ($do_dow == "6") {
                  $returnValue = array("name"=>"A Collect for Saturdays"
                  , "text" => "Almighty God, who after the creation of the world didst rest
                from all thy works and sanctify a day of rest for all thy
                creatures:  Grant that we, putting away all earthly anxieties,
                may be duly prepared for the service of thy sanctuary, and
                that our rest here upon earth may be a preparation for the
                eternal rest promised to thy people in heaven; through Jesus
                Christ our Lord.  =Amen.=");
            } else {
                $which = rand(0, 3);
                $collect[0] = array("name"=>"A Collect for the Renewal of Life"
                  , "text" => "O God, the King eternal, who dividest the day from the
                  night and turnest the shadow of death into the morning:
                  Drive far from us all wrong desires, incline our hearts to keep
                  thy law, and guide our feet into the way of peace; that,
                  having done thy will with cheerfulness while it was day, we
                  may, when night cometh, rejoice to give thee thanks;
                  through Jesus Christ our Lord.  =Amen.=");
                $collect[1] = array("name"=>"A Collect for Peace"
                  , "text" => "O God, who art the author of peace and lover of concord, in
                  knowledge of whom standeth our eternal life, whose service
                  is perfect freedom:  Defend us, thy humble servants, in all
                  assaults of our enemies; that we, surely trusting in thy
                  defense, may not fear the power of any adversaries; through
                  the might of Jesus Christ our Lord.  =Amen.=");
                $collect[2] = array("name"=>"A Collect for Grace"
                  , "text" => "O Lord, our heavenly Father, almighty and everlasting God,
                  who hast safely brought us to the beginning of this day:
                  Defend us in the same with thy mighty power; and grant that
                  this day we fall into no sin, neither run into any kind of
                  danger; but that we, being ordered by thy governance, may
                  do always what is righteous in thy sight; through Jesus Christ
                  our Lord.  =Amen.=");
                $collect[3] = array("name"=>"A Collect for Guidance"
                  , "text" => "O heavenly Father, in whom we live and move and have our
                  being:  We humbly pray thee so to guide and govern us by thy
                  Holy Spirit, that in all the cares and occupations of our life
                  we may not forget thee, but may remember that we are ever
                  walking in thy sight; through Jesus Christ our Lord.  =Amen.=");
                  $returnValue = $collect[$which];
            }
        } elseif ($which_rite == "rite_ii") {
            if ($do_dow == "0") {
                $returnValue = array("name"=>"A Collect for Sundays"
                , "text" => "O God, you make us glad with the weekly remembrance of
                the glorious resurrection of your Son our Lord:  Give us this
                day such blessing through our worship of you, that the week
                to come may be spent in your favor; through Jesus Christ our
                Lord.  =Amen.=");
            } elseif ($do_dow == "5") {
                $returnValue = array("name"=>"A Collect for Fridays"
                , "text" => "Almighty God, whose most dear Son went not up to joy but
                first he suffered pain, and entered not into glory before he
                was crucified:  Mercifully grant that we, walking in the way
                of the cross, may find it none other than the way of life and
                peace; through Jesus Christ your Son our Lord.  =Amen.=");
            } elseif ($do_dow == "6") {
                $returnValue = array("name"=>"A Collect for Saturdays"
                , "text" => "Almighty God, who after the creation of the world rested
                from all your works and sanctified a day of rest for all your
                creatures:  Grant that we, putting away all earthly anxieties,
                may be duly prepared for the service of your sanctuary, and
                that our rest here upon earth may be a preparation for the
                eternal rest promised to your people in heaven; through Jesus
                Christ our Lord.  =Amen.=");
            } else {
                $which = rand(0, 3);
                $collect[0] = array("name"=>"A Collect for the Renewal of Life"
                , "text" => "O God, the King eternal, whose light divides the day from the
                night and turns the shadow of death into the morning:  Drive
                far from us all wrong desires, incline our hearts to keep your
                law, and guide our feet into the way of peace; that, having
                done your will with cheerfulness during the day, we may,
                when night comes, rejoice to give you thanks; through Jesus
                Christ our Lord.  =Amen.=");
                $collect[1] = array("name"=>"A Collect for Peace"
                , "text" => "O God, the author of peace and lover of concord, to
                know you is eternal life and to serve you is perfect freedom:
                Defend us, your humble servants, in all assaults of our enemies;
                that we, surely trusting in your defense, may not fear the power of
                any adversaries; through the might of Jesus Christ our Lord.  =Amen.=");
                $collect[2] = array("name"=>"A Collect for Grace"
                  , "text" => "Lord God, almighty and everlasting Father, you have
                brought us in safety to this new day:  Preserve us with your
                mighty power, that we may not fall into sin, nor be overcome
                by adversity; and in all we do, direct us to the fulfilling of
                your purpose; through Jesus Christ our Lord.  =Amen.=");
                $collect[3] = array("name"=>"A Collect for Guidance"
                , "text" => "Heavenly Father, in you we live and move and have our
                being:  We humbly pray you so to guide and govern us by
                your Holy Spirit, that in all the cares and occupations of our
                life we may not forget you, but may remember that we are
                ever walking in your sight; through Jesus Christ our Lord.  =Amen.=");
                $returnValue = $collect[$which];
            }
        }
    } elseif ($which_prayer == "evening_prayer") {
        if ($which_rite == "rite_i") {
            if ($do_dow == "0") {
                $returnValue = array("name"=>"A Collect for Sundays"
                , "text" => "Lord God, whose Son our Savior Jesus Christ triumphed
                over the powers of death and prepared for us our place in the new
                Jerusalem: Grant that we, who have this day given thanks for his
                resurrection, may praise thee in that City of which he is the light;
                and where he liveth and reigneth for ever and ever.  =Amen.=");
            } elseif ($do_dow == "5") {
                $returnValue = array("name"=>"A Collect for Fridays"
                , "text" => "O Lord Jesus Christ, who by thy death didst take away the
                sting of death: Grant unto us thy servants so to follow in faith
                where thou hast led the way, that we may at length fall asleep
                peacefully in thee, and awake up after thy likeness; for thy
                tender mercies' sake.  =Amen.=");
            } elseif ($do_dow == "6") {
                $returnValue = array("name"=>"A Collect for Saturdays"
                , "text" => "O God, the source of eternal light; Shed forth thine
                unending day upon us who watch for thee, that our lips may praise
                thee, our lives may bless thee, and our worship on the morrow may
                give thee glory; through Jesus Christ our Lord.  =Amen.=");
            } else {
                $which = rand(0, 3);
                $collect[0] = array("name"=>"A Collect for Aid against Perils"
                , "text" => "Lighten our darkenss, we beseech thee, O Lord; and by
                thy great mercy defend us from all perils and dangers of this night;
                for the love of thy only Son, our Savior Jesus Christ.  =Amen.=");
                $collect[1] = array("name"=>"A Collect for Peace"
                , "text" => "O God, from whom all holy desires, all good counsels, and
                all just works do proceed: Give unto thy servants that peace which
                the world cannot give, that our hearts may be set to obey thy
                commandments, and also that by thee, we, being defended from the
                fear of all enemies, may pass our time in rest and quietness; through
                the merits of Jesus Christ our Savior.  =Amen.=");
                $collect[2] = array("name"=>"A Collect for Protection"
                , "text" => "O God, who art the life of all who live, the light of
                the faithful, the strength of those who labor, and the repose of
                the dead: We thank thee for the timely blessings of the day, and
                humbly beseech thy merciful protection all the night. Bring us, we
                pray thee, in safety to the morning hours; through him who died for
                us and rose again, thy Son our Savior Jesus Christ.  =Amen.=");
                $collect[3] = array("name"=>"A Collect for the Presence of Christ"
                , "text" => "Lord Jesus, stay with us, for evening is at hand and
                the day is past; be our companion in the way, kindle our hearts, and
                awaken hope, that we may know thee as thou art revealed in Scripture
                and the breaking of bread. Grant this for the sake of thy love.  =Amen.=");
                $returnValue = $collect[$which];
            }
        } elseif ($which_rite == "rite_ii") {
            if ($do_dow == "0") {
                $returnValue = array("name"=>"A Collect for Sundays"
                , "text" => "Lord God, whose Son our Savior Jesus Christ triumphed
                over the powers of death and prepared for us our place in the new
                Jerusalem: Grant that we, who have this day given thanks for his
                resurrection, may praise you in that City of which he is the light;
                and where he lives and reigns for ever and ever.  =Amen.=");
            } elseif ($do_dow == "5") {
                $returnValue = array("name"=>"A Collect for Fridays"
                , "text" => "O Lord Jesus Christ, by your death you took away the
                sting of death: Grant to us your servants so to follow in faith
                where you have led the way, that we may at length fall asleep
                peacefully in you, and awake up in your likeness; for your
                tender mercies' sake.  =Amen.=");
            } elseif ($do_dow == "6") {
                $returnValue = array("name"=>"A Collect for Saturdays"
                , "text" => "O God, the source of eternal light; Shed forth your
                unending day upon us who watch for you, that our lips may praise
                you, our lives may bless you, and our worship on the morrow may
                give you glory; through Jesus Christ our Lord.  =Amen.=");
            } else {
                $which = rand(0, 3);
                $collect[0] = array("name"=>"A Collect for Aid against Perils"
                , "text" => "Be our light in the darkness, O Lord, and in your
                great mercy defend us from all perils and dangers of this night;
                for the love of your only Son, our Savior Jesus Christ.  =Amen.=");
                $collect[1] = array("name"=>"A Collect for Peace"
                , "text" => "Most holy God, the source of all good desires, all
                all right judgments, and all just works: Give to us, your servants,
                that peace which the world cannot give, so that our minds may be
                fixed on the doing of your will, and that we, being delivered from
                the fear of all enemies, may live in peace and quietness; through
                the mercies of Christ Jesus our Savior.  =Amen.=");
                $collect[2] = array("name"=>"A Collect for Protection"
                , "text" => "O God, the life of all who live, the light of
                the faithful, the strength of those who labor, and the repose of
                the dead: We thank you for the blessings of the day that is past,
                and humbly as for your protection through the coming night. Bring
                us in safety to the morning hours; through him who died and rose
                again for us, your Son our Savior Jesus Christ.  =Amen.=");
                $collect[3] = array("name"=>"A Collect for the Presence of Christ"
                , "text" => "Lord Jesus, stay with us, for evening is at hand and
                the day is past; be our companion in the way, kindle our hearts, and
                awaken hope, that we may know you as you are revealed in Scripture
                and the breaking of bread. Grant this for the sake of your love.
                 =Amen.=");
                $returnValue = $collect[$which];
            }
        }
    }
    return $returnValue;
}
