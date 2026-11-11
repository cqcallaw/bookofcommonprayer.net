<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$antiphon = getAntiphon($data->wr, $data->id);

$this->qsOutput = json_encode($antiphon);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getAntiphon($which_rite, $do_id)
{
    if ($which_rite == "rite_i") {
        if (($do_id == "1A") || ($do_id == "2A") || ($do_id == "3A") || ($do_id == "4A")) {
            $returnValue = array("timeframe"=>"In Advent",
            "antiphon"=>"Our King and Savior draweth nigh: O come, let us adore him.");
        }
        if (($do_id == "E") || ($do_id == "2E") || ($do_id == "3E") || ($do_id == "4E")
        || ($do_id == "5E")  || ($do_id == "6E")  || ($do_id == "7E")  || ($do_id == "8E")
        || ($do_id == "LE")) {
            $returnValue = array("timeframe"=>"During Epiphany",
            "antiphon"=>"The Lord hath manifested forth his glory:  O come, let us
            adore him.");
        }
        if (($do_id == "1L") || ($do_id == "2L") || ($do_id == "3L")
        || ($do_id == "4L") || ($do_id == "5L")) {
            $returnValue = array("timeframe"=>"In Lent",
            "antiphon"=>"The Lord is full of compassion and mercy:  O come, let us
            adore him.");
        }
        if (($do_id == "R") || ($do_id == "2R") || ($do_id == "3R")
        || ($do_id == "4R") || ($do_id == "5R") || ($do_id == "6R")
        || ($do_id == "7R")) {
            if ((($do_id == "6R") || ($do_id == "5R") || ($do_id == "4R")
            || ($do_id == "3R") || ($do_id == "2R")
            || ($do_id == "R")) && ($do_dow <= "4")) {
                $returnValue = array("timeframe"=>"From Easter Day until the Ascension"
                ,"antiphon"=>"Alleluia.  The Lord is risen indeed:  O come, let us adore him.
                Alleluia.");
            } elseif (($do_id == "6R" && $do_dow > "4") || $do_id == "7R") {
                $returnValue = array("timeframe"=>"From Ascension Day until the Day
                of Pentecost"
                ,"antiphon"=>"Alleluia.  Christ the Lord ascendedeth into heaven:  O come,
                let us adore him. Alleluia.");
            }
        }
        if (($do_id == "P")) {
            $returnValue = array("timeframe"=>"On the Day of Pentecost"
            ,"antiphon"=>"Alleluia.  The Spirit of the Lord filleth the world:  O come,
            let us adore him.  Alleluia.");
        }
        if (($do_id == "T")) {
            $returnValue = array("timeframe"=>"On Trinity Sunday"
            ,"antiphon"=>"Father, Son, and Holy Spirit, one God:  O come, let us
            adore him.");
        }
        $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only)
        // from start of line to end of subject
        $is_proper = preg_match($pattern, $do_id);
        if ($is_proper) {
            $which = rand(0, 2);
            $day_of[0] = "The earth is the Lord's for he made it:  O come, let us adore him.";
            $day_of[1] = "Worship the Lord in the beauty of his holiness:  O come, let us adore him.";
            $day_of[2] = "The mercy of the Lord is everlasting:  O come, let us adore him.";
            $which_antiphon = $day_of[$which];
            $returnValue = array("timeframe"=>"On other Sundays and weekdays"
            ,"antiphon"=>$which_antiphon);
        }
    } elseif ($which_rite == "rite_ii") {
        if (($do_id == "1A") || ($do_id == "2A") || ($do_id == "3A")
        || ($do_id == "4A")) {
            $returnValue = array("timeframe"=>"In Advent"
            ,"antiphon"=>"Our King and Savior now draws near: Come, let us adore him.");
        }
        if (($do_id == "E") || ($do_id == "2E") || ($do_id == "3E")
        || ($do_id == "4E")  || ($do_id == "5E")  || ($do_id == "6E")
        || ($do_id == "7E")  || ($do_id == "8E")  || ($do_id == "LE")) {
            $returnValue = array("timeframe"=>"During Epiphany"
            ,"antiphon"=>"The Lord has shown forth his glory:  Come, let us
            adore him.");
        }
        if (($do_id == "1L") || ($do_id == "2L") || ($do_id == "3L")
        || ($do_id == "4L") || ($do_id == "5L")) {
            $returnValue = array("timeframe"=>"In Lent"
            ,"antiphon"=>"The Lord is full of compassion and mercy:  Come, let us
            adore him.");
        }
        if (($do_id == "R") || ($do_id == "2R") || ($do_id == "3R")
        || ($do_id == "4R") || ($do_id == "5R") || ($do_id == "6R")
        || ($do_id == "7R")) {
            if ((($do_id == "6R") || ($do_id == "5R") || ($do_id == "4R")
            || ($do_id == "3R") || ($do_id == "2R")
            || ($do_id == "R")) && ($do_dow <= "4")) {
                $returnValue = array("timeframe"=>"From Easter Day until the Ascension"
                ,"antiphon"=>"Alleluia.  The Lord is risen indeed:  Come, let us adore him.
                Alleluia.");
            } elseif (($do_id == "6R" && $do_dow > "4") || $do_id == "7R") {
                $returnValue = array("timeframe"=>"From Ascension Day until the Day
                of Pentecost"
                ,"antiphon"=>"Alleluia.  Christ the Lord has ascended into heaven:  Come,
                  let us adore him. Alleluia.");
            }
        }
        if (($do_id == "P")) {
            $returnValue = array("timeframe"=>"On the Day of Pentecost"
            ,"antiphon"=>"Alleluia.  The Spirit of the Lord renews the face of the earth:  Come,
            let us adore him.  Alleluia.");
        }
        if (($do_id == "T")) {
            $returnValue = array("timeframe"=>"On Trinity Sunday"
            ,"antiphon"=>"Father, Son, and Holy Spirit, one God:  Come, let us
            adore him.");
        }
        $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only)
        // from start of line to end of subject
        $is_proper = preg_match($pattern, $do_id);
        if ($is_proper) {
            $which = rand(0, 2);
            $day_of[0] = "The earth is the Lord's for he made it:  Come, let us adore him.";
            $day_of[1] = "Worship the Lord in the beauty of holiness:  Come, let us adore him.";
            $day_of[2] = "The mercy of the Lord is everlasting:  Come, let us adore him.";
            $which_antiphon = $day_of[$which];
            $returnValue = array("timeframe"=>"On other Sundays and weekdays"
            ,"antiphon"=>$which_antiphon);
        }
    }
    return $returnValue;
}
