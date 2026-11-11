<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

if ($data->id == "LE" && $data->dow >= 3) {
    // force lent after Ash Wednesday
    $data->id = "1L";
}
$cw = getComfortableWords($data->wp, $data->wr, $data->id, $data->dow);

$this->qsOutput = json_encode($cw);

function getComfortableWords($which_prayer, $which_rite, $do_id, $dow)
{
  // if we are in the special holiday ids then we need to make it a
  // number (any number), so it will be treated like a "proper"
    $do_id = (
        ($do_id == 'STST') || ($do_id == 'STJE') || ($do_id == 'HI')
        || ($do_id == '12-29') || ($do_id == '12-30') || ($do_id == '12-31')
        || ($do_id == '1-2') || ($do_id == '1-3') || ($do_id == '1-4')
        || ($do_id == '1-5') || ($do_id == '1-7') || ($do_id == '1-8')
        || ($do_id == '1-9') || ($do_id == '1-10') || ($do_id == '1-11')
        || ($do_id == '1-12') || ($do_id == 'HN')
        )
        ? '12' : $do_id;
    if ($which_prayer == "morning_prayer") {
        if ($which_rite == "rite_i") {
      // COMFORTABLE WORDS
            if (($do_id == "1A") || ($do_id == "2A") || ($do_id == "3A") || ($do_id == "4A")) {
                $which = rand(0, 2);
                $a_cw[0] = array("cw"=>"Watch ye , for ye know not when the master of the house cometh, at even, or at
                    midnight, or at the cock-crowing, or in the morning; lest coming suddenly he find
                    you sleeping.","reference"=>"Mark 13:35, 36");
                $a_cw[1] = array("cw"=>"Prepare ye the way of the Lord, make straight in the desert a highway for our
                    God.","reference"=>"Isaiah 40:3");
                $a_cw[2] = array("cw"=>"The glory of the Lord shall be revealed, and all flesh shall see it
                    together.","reference"=>"Isaiah 40:5");
                $returnValue = $a_cw[$which];
            }
            if (($do_id == "CM") || ($do_id == "CMII") || ($do_id == "CMIII")) {
                $which = rand(0, 1);
                $cm_cw[0] = array("cw"=>"Behold, I bring you good tidings of a
                    great joy, which shall be to all people.
                    For unto you is born this day in the city of David a Savior,
                    which is Christ the
                    Lord.","reference"=>"Luke 2:10, 11");
                $cm_cw[1] = array("cw"=>"Behold, the tabernacle of God is with
                    men, and he will dwell with them, and they shall be his people,
                    and God himself shall be with them, and be their God."
                    ,"reference"=>"Revelation 21:3");
                $returnValue = $cm_cw[$which];
            }
            if (($do_id == "1E") || ($do_id == "2E") || ($do_id == "3E") || ($do_id == "4E") ||
              ($do_id == "5E") || ($do_id == "6E") || ($do_id == "7E") || ($do_id == "8E") ||
              ($do_id == "LE")) {
                $which = rand(0, 2);
                $e_cw[0] = array("cw"=>"The Gentiles shall come to thy light,
                and kings to the brightness of thy rising.",
                "reference"=>"Isaiah 60:3");
                $e_cw[1] = array("cw"=>"I will give thee for a light to the Gentiles, that thou mayest be my
                    salvation unto the end of the earth.","reference"=>"Isaiah 49:6b");
                $e_cw[2] = array("cw"=>"From the rising of the sun even unto the going down of the same my Name shall
                    be great among the Gentiles, and in every place incense shall be offered unto
                    my Name, and a pure offering: for my Name shall be great among the heathen,
                    saith the Lord of hosts.","reference"=>"Malachi 1:11");
                $returnValue = $e_cw[$which];
            }
            if (($do_id == "1L") || ($do_id == "2L") || ($do_id == "3L") ||
              ($do_id == "4L") || ($do_id == "5L")) {
                $which = rand(0, 4);
                $l_cw[0] = array("cw"=>"If we say we have no sin, we deceive ourselves, and the
            truth is not in us, but if we confess our sins, God is faithful
            and just to forgive us our sins, and to cleanse us from all
            unrighteousness.","reference"=>"1 John 1:8, 9");
                $l_cw[1] = array("cw"=>"Rend your heart, and not your garments, and turn unto the
            Lord your God; for he is gracious and merciful, slow to
            anger and of great kindness, and repenteth him of evil.","reference"=>"Joel 2:13");
                $l_cw[2] = array("cw"=>"I will arise and go to my father, and will say unto him,
            'Father, I have sinned against heaven, and before thee; and
            am no more worthy to be called thy son.'","reference"=>"Luke 15:18, 19");
                $l_cw[3] = array("cw"=>"To the Lord our God belong mercies and forgivenesses,
            though we have rebelled against him; neither have we obeyed
            the voice of the Lord our God, to walk in his laws which he
            set before us.","reference"=>"Daniel 9:9, 10");
                $l_cw[4] = array("cw"=>"Jesus said, 'Whosoever will come after me, let him deny
            himself, and take up his cross, and follow me.'","reference"=>"Mark 8:34");
                $returnValue = $l_cw[$which];
            }
            if ($do_id == "PS") {
                $which = rand(0, 1);
                $ps_cw[0] = array("cw"=>"All we like sheep have gone astray; we have turned every one
            to his own way; and the Lord hath laid on him the iniquity of
            us all.","reference"=>"Isaiah 53:6");
                $ps_cw[1] = array("cw"=>"Is it nothing to you, all ye that pass by?  Behold and see if
            there be any sorrow like unto my sorrow which is done unto
            me, wherewith the Lord hath afflicted me.","reference"=>"Lamentations 1:12");
                $returnValue = $ps_cw[$which];
            }
            if (($do_id == "R") || ($do_id == "2R") || ($do_id == "3R") || ($do_id == "4R") ||
            ($do_id == "5R") || ($do_id == "6R") || ($do_id == "7R") || ($do_id == "P") ||
            ($do_id == "AD")) {
                $which = rand(0, 5);
                $r_cw[0] = array("cw"=>"Alleluia! Christ is risen. The Lord is
                risen indeed. Alleluia!","reference"=>"");
                $r_cw[1] = array("cw"=>"This is the day which the Lord hath made; we will rejoice and
            be glad in it.","reference"=>"Psalm 118:24");
                $r_cw[2] = array("cw"=>"Thanks be to God, which giveth us the victory through our
            Lord Jesus Christ.","reference"=>"1 Corinthians 15:57");
                $r_cw[3] = array("cw"=>"If ye then be risen with Christ, seek the things which are
            above, where Christ sitteth on the right hand of God.","reference"=>"Colossians 3:1");
                $r_cw[4] = array("cw"=>"Christ is not entered into the holy places made with hands,
            which are the figures of the true; but into heaven itself, now
            to appear in the presence of God for us.","reference"=>"Hebrews 9:24");
                $r_cw[5] = array("cw"=>"Ye shall receive power after the Holy Ghost is come
            upon you; and ye shall be witness unto me both in
            Jerusalem, and in all Judaea, and in Samaria, and unto the
            uttermost part of the earth.","reference"=>"Acts 1:8");
                $returnValue = $r_cw[$which];
            }
            if ($do_id == "T") {
                $which = rand(0, 3);
                $t_cw[0] = array("cw"=>"Holy, holy, holy, Lord God Almighty, which was, and is, and is to come.","reference"=>"Revelation 4:8");
                $t_cw[1] = array("cw"=>"We give thanks unto the Father, which hath made us meet to
            be partakers of the inheritance of the saints in light.","reference"=>"Colossians 1:12");
                $t_cw[2] = array("cw"=>"Ye are no more strangers and foreigners, but fellow-citizens
            with the saints and of the household of God.","reference"=>"Ephesians 2:19");
                $t_cw[3] = array("cw"=>"Their sound is gone out into all lands, and their words into
            the ends of the world.","reference"=>"Psalm 19:4");
                $returnValue = $t_cw[$which];
            }
            $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only)
              // from start of line to end of subject
            $is_proper = preg_match($pattern, $do_id);
            if ($is_proper) {
                $which = rand(0, 6);
                $p_cw[0] = array("cw"=>"Grace be unto you, and peace, from God our Father, and
                from the Lord Jesus Christ.","reference"=>"Phillipians 1:2");
                $p_cw[1] = array("cw"=>"I was glad when they said unto me, 'We will go into the
                house of the Lord.'","reference"=>"Psalm 122:1");
                $p_cw[2] = array("cw"=>"Let the words of my mouth, and the meditation of my heart,
                be always acceptable in thy sight, O Lord, my strength and
                my redeemer.","reference"=>"Psalm 19:14");
                $p_cw[3] = array("cw"=>"O send out thy light and thy truth, that they may lead me,
                and bring me unto thy holy hill, and to thy dwelling.","reference"=>"Psalm 43:3");
                $p_cw[4] = array("cw"=>"The Lord is in his holy temple; let all the earth keep silence
                before him.","reference"=>"Habakkuk 2:20");
                $p_cw[5] = array("cw"=>"The hour cometh, and now is, when the true worshipers
                shall worship the Father in spirit and in truth, for the Father
                seeketh such to worship him.","reference"=>"John 4:23");
                $p_cw[6] = array("cw"=>"Thus saith the high and lofty One who inhabiteth eternity,
                whose name is Holy, 'I dwell in the high and holy place, with
                him also that is of a contrite and humble spirit, to revive the
                spirit of the humble, and to revive the heart of the contrite
                ones.'","reference"=>"Isaiah 57:15");
                $returnValue = $p_cw[$which];
            }
            // End of Rite I
        } elseif ($which_rite == "rite_ii") {
            if (($do_id == "1A") || ($do_id == "2A") || ($do_id == "3A") ||
                ($do_id == "4A")) {
                    $which = rand(0, 2);
                    $a_cw[0] = array("cw"=>"Watch, for you do not know when the master of the house
          will come, in the evening, or at midnight, or at cockcrow, or
          in the morning, lest he come suddenly and find you asleep.","reference"=>"Mark 13:35, 36");
                    $a_cw[1] = array("cw"=>"In the wilderness prepare the way of the Lord, make straight
          in the desert a highway for our God.","reference"=>"Isaiah 40:3");
                    $a_cw[2] = array("cw"=>"The glory of the Lord shall be revealed, and all flesh shall see
          it together.","reference"=>"Isaiah 40:5");
                    $returnValue = $a_cw[$which];
            }
            if (($do_id == "CM") || ($do_id == "CMII") || ($do_id == "CMIII")) {
                $which = rand(0, 1);
                $cm_cw[0] = array("cw"=>"Behold, I bring you good news of a great joy which will come
                to all the people; for to you is born this day in the city of
                David, a Savior, who is Christ the Lord.","reference"=>"Luke 2:10, 11");
                $cm_cw[1] = array("cw"=>"Behold, the dwelling of God is with mankind.  He will dwell
                with them, and they shall be his people, and God himself will
                be with them, and be their God.","reference"=>"Revelation 21:3");
                $returnValue = $cm_cw[$which];
            }
            if (($do_id == "1E") || ($do_id == "2E") || ($do_id == "3E") ||
                ($do_id == "4E") || ($do_id == "5E") || ($do_id == "6E") ||
                ($do_id == "7E") || ($do_id == "8E") || ($do_id == "LE")) {
                  $which = rand(0, 2);
                  $e_cw[0] = array("cw"=>"Nations shall come to your light, and kings to the brightness
                  of your rising.","reference"=>"Isaiah 60:3");
                  $e_cw[1] = array("cw"=>"I will give you as a light to the nations, that my salvation may
                  reach to the end of the earth.","reference"=>"Isaiah 49:6b");
                  $e_cw[2] = array("cw"=>"From the rising of the sun to its setting my Name shall be great
                  among the nations, and in every place incense shall be offered
                  to my Name, and a pure offering; for my Name shall be great
                  among the nations, says the Lord of hosts.","reference"=>"Malachi 1:11");
                  $returnValue = $e_cw[$which];
            }
            if (($do_id == "1L") || ($do_id == "2L") || ($do_id == "3L") ||
              ($do_id == "4L") || ($do_id == "5L")) {
                  $which = rand(0, 4);
                  $l_cw[0] = array("cw"=>"If we say we have no sin, we deceive ourselves, and the truth
                  is not in us, but if we confess our sins, God, who is faithful
                  and just, will forgive our sins and cleanse us from all
                  unrighteousness.","reference"=>"1 John 1:8, 9");
                  $l_cw[1] = array("cw"=>"Rend your hearts and not your garments.  Return to the Lord
                  your God, for he is gracious and merciful, slow to anger and
                  abounding in steadfast love, and repents of evil.","reference"=>"Joel 2:13");
                  $l_cw[2] = array("cw"=>"I will arise and go to my father, and I will say to him,
                  'Father, I have sinned against heaven and before you; I am
                  no longer worthy to be called your son.'","reference"=>"Luke 15:18, 19");
                  $l_cw[3] = array("cw"=>"To the Lord our God belong mercy and forgiveness, because we have
                  rebelled against him and have not obeyed the voice of the Lord our God by
                  following his laws which he set before us.","reference"=>"Daniel 9:9, 10");
                  $l_cw[4] = array("cw"=>"Jesus said, 'If anyone would come after me, let him deny himself and
                  take up his cross and follow me.'","reference"=>"Mark 8:34");
                  $returnValue = $l_cw[$which];
            }
            if ($do_id == "PS") {
                $which = rand(0, 1);
                $ps_cw[0] = array("cw"=>"All we like sheep have gone astray; we have turned every one
                to his own way; and the Lord has laid on him the iniquity of
                us all.","reference"=>"Isaiah 53:6");
                $ps_cw[1] = array("cw"=>"Is it nothing to you, all you who pass by?  Look and see if
                there is any sorrow like my sorrow which was brought upon
                me, whom the Lord has afflicted.","reference"=>"Lamentations 1:12");
                $returnValue = $ps_cw[$which];
            }

            if (($do_id == "R") || ($do_id == "2R") || ($do_id == "3R") ||
                ($do_id == "4R") || ($do_id == "5R") || ($do_id == "6R") ||
                ($do_id == "7R") || ($do_id == "P") || ($do_id == "AD")) {
                $which = rand(0, 5);
                $r_cw[0] = array("cw"=>"Alleluia! Christ is risen. The Lord is risen indeed. Alleluia!"
                ,"reference"=>"");
                $r_cw[1] = array("cw"=>"On this day the Lord has acted; we will rejoice and be glad in
                it.","reference"=>"Psalm 118:24");
                $r_cw[2] = array("cw"=>"Thanks be to God, who gives us the victory through our Lord
                Jesus Christ.","reference"=>"1 Corinthians 15:57");
                $r_cw[3] = array("cw"=>"If then you have been raised with Christ, seek the things that
                are above, where Christ is, seated at the right hand of God.","reference"=>"Colossians 3:1");
                $r_cw[4] = array("cw"=>"Christ has entered, not into a sanctuary made with hands, a
                copy of the true one, but into heaven itself, now to appear in
                the presence of God on our behalf.","reference"=>"Hebrews 9:24");
                $r_cw[5] = array("cw"=>"You shall receive power when the Holy Spirit has come upon
                you; and you shall be my witness in Jerusalem, and in all
                Judea, and Samaria, and to the ends of the earth.","reference"=>"Acts 1:8");
                $returnValue = $r_cw[$which];
            }
            if ($do_id == "T") {
                $which = rand(0, 2);
                $t_cw[0] = array("cw"=>"Holy, holy, holy is the Lord God Almighty, who was, and is,
                and is to come!","reference"=>"Colossians 1:12");
                $returnValue = $t_cw[$which];
            }
            $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only) from start of line to end
             //of subject
            $is_proper = preg_match($pattern, $do_id);
            if ($is_proper) {
                $which = rand(0, 6);
                $p_cw[0] = array("cw"=>"Grace to you and peace from God our Father and the Lord
                Jesus Christ.","reference"=>"Phillipians 1:2");
                $p_cw[1] = array("cw"=>"I was glad when they said to me, 'Let us go to the house of
                the Lord.'","reference"=>"Psalm 122:1");
                $p_cw[2] = array("cw"=>"Let the words of my mouth and the meditation of my heart
                be acceptable in your sight, O Lord, my strength and my
                redeemer.","reference"=>"Psalm 19:14");
                $p_cw[3] = array("cw"=>"Send out your light and your truth, that they may lead me,
                and bring me to your holy hill and to your dwelling.","reference"=>"Psalm 43:3");
                $p_cw[4] = array("cw"=>"The Lord is in his holy temple; let all the earth keep silence
                before him.","reference"=>"Habakkuk 2:20");
                $p_cw[5] = array("cw"=>"The hour is coming, and now is, when the true worshipers
                will worship the Father in spirit and truth, for such the
                Father seeks to worship him.","reference"=>"John 4:23");
                $p_cw[6] = array("cw"=>"Thus says the high and lofty One who inhabits eternity,
                whose name is Holy, 'I dwell in the high and holy place and
                also with the one who has a contrite and humble spirit, to
                revive the spirit of the humble and to revive the heart of the
                contrite.'","reference"=>"Isaiah 57:15");
                $returnValue = $p_cw[$which];
            }
      // End of Rite II
        }
    // End of Morning Prayer
    } elseif ($which_prayer == "evening_prayer") {
        if ($which_rite == "rite_i") {
            $which = rand(0, 7);
            $which1 = rand(0, 7);
            while ($which1 == $which) {
                $which1 = rand(0, 7);
            }
            $comfort[0] = array("cw"=>"Let my prayer be set forth in thy sight as the incense, and let
            the lifting up of my hands be an evening sacrifice.","reference"=>"Psalm 141:2");
            $comfort[1] = array("cw"=>"Grace be unto you, and peace, from God our Father, and
            from the Lord Jesus Christ.","reference"=>"Philippians 1:2");
            $comfort[2] = array("cw"=>"O worship the Lord in the beauty of holiness; let the whole
            earth stand in awe of him.","reference"=>"Psalm 96:9");
            $comfort[3] = array("cw"=>"Thine is the day, O God, thine also the night; thou hast
            established the moon and the sun.  Thou hast fixed all the
            boundaries of the earth; thou hast made summer and winter.","reference"=>"Psalm 74:15,16");
            $comfort[4] = array("cw"=>"I will bless the Lord, who giveth me counsel; my heart
            teacheth me, night after night.  I have set the Lord always
            before me; because he is at my right hand, I shall not fall.","reference"=>"Psalm 16:7,8");
            $comfort[5] = array("cw"=>"Seek him that made the Pleides and Orion, that turneth deep
            darkness into the morning, and darkeneth the day into night;
            that calleth for the waters of the sea, and poureth them out
            upon the face of the earth:  The Lord is his Name.","reference"=>"Amos 5:8");
            $comfort[6] = array("cw"=>"If I say, 'Surely the darkness will cover me, and the light
            around me turn to night,' darkness is not dark to thee, O Lord;
            the night is as bright as the day; darkness and light to thee
            are both alike.","reference"=>"Psalm 139:10,11");
            $comfort[7] = array("cw"=>"Jesus said, 'I am the light of the world; he that followeth me
            shall not walk in darkness, but shall have the light of life.'","reference"=>"John 8:12");
            $returnValue = array("first"=>$comfort[$which],"second"=>$comfort[$which1]);
            // End of Rite I
        } elseif ($which_rite == "rite_ii") {
            $which = rand(0, 7);
            $which1 = rand(0, 7);
            while ($which1 == $which) {
                $which1 = rand(0, 7);
            }
            $comfort[0] = array("cw"=>"Let my prayer be set forth in your sight as incense, the lifting up of my hands
            as an evening sacrifice.","reference"=>"Psalm 141:2");
            $comfort[1] = array("cw"=>"Grace to you, and peace, from God our Father and from the Lord Jesus
            Christ.","reference"=>"Philippians 1:2");
            $comfort[2] = array("cw"=>"Worship the Lord in the beauty of holiness; let the whole earth tremble before
            him.","reference"=>"Psalm 96:9");
            $comfort[3] = array("cw"=>"Yours is the day, O God, yours also the night; you established the
            moon and the sun.  You fixed all the boundaries of the earth; you made both summer and winter.",
            "reference"=>"Psalm 74:15,16");
            $comfort[4] = array("cw"=>"I will bless the Lord, who gives me counsel; my heart teaches me,
            night after night.  I have set the Lord always before me; because he is at my right hand, I
            shall not fall.","reference"=>"Psalm 16:7,8");
            $comfort[5] = array("cw"=>"Seek him who made the Pleiades and Orion, and turns deep darkness into
            the morning, and darkens the day into night; who calls for the waters of the sea, and pours them
            out upon the surface of the earth:  The Lord is his Name.","reference"=>"Amos 5:8");
            $comfort[6] = array("cw"=>"If I say, 'Surely the darkness will cover me, and the light around me
            turn to night,' darkness is not dark to you, O Lord; the night is as bright as the day; darkness
            and light to you are both alike.","reference"=>"Psalm 139:10,11");
            $comfort[7] = array("cw"=>"Jesus said, 'I am the light of the world; whoever follows me will not
            walk in darkness, but will have the light of life.'","reference"=>"John 8:12");
            $returnValue = array("first"=>$comfort[$which],"second"=>$comfort[$which1]);
          // End of Rite II
        }
        // End of Evening Prayer
    }

    return $returnValue;
}
