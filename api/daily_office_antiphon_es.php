<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$antiphon = getAntiphon($data->wr, $data->id);

$this->qsOutput = json_encode($antiphon);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getAntiphon($which_rite, $do_id)
{
    if (($do_id == "1A") || ($do_id == "2A") || ($do_id == "3A") || ($do_id == "4A")) {
        $returnValue = array("timeframe"=>"En Adviento",
        "antiphon"=>"Nuestro Rey y Salvador se acerca: vengan y adorémosle.");
    }
    if (($do_id == "E") || ($do_id == "2E") || ($do_id == "3E") || ($do_id == "4E")
    || ($do_id == "5E")  || ($do_id == "6E")  || ($do_id == "7E")  || ($do_id == "8E")
    || ($do_id == "LE")) {
        $returnValue = array("timeframe"=>"Desde la Epifanía",
        "antiphon"=>"El Señor ha manifestado su gloria: vengan y adorémosle.");
    }
    if (($do_id == "1L") || ($do_id == "2L") || ($do_id == "3L")
    || ($do_id == "4L") || ($do_id == "5L")) {
        $returnValue = array("timeframe"=>"En Cuaresma",
        "antiphon"=>"Misericordioso y clemente es el Señor: vengan y
adorémosle.");
    }
    if (($do_id == "R") || ($do_id == "2R") || ($do_id == "3R")
    || ($do_id == "4R") || ($do_id == "5R") || ($do_id == "6R")
    || ($do_id == "7R")) {
        if ((($do_id == "6R") || ($do_id == "5R") || ($do_id == "4R")
        || ($do_id == "3R") || ($do_id == "2R")
        || ($do_id == "R")) && ($do_dow <= "4")) {
            $returnValue = array("timeframe"=>"Desde el Día de Pascua hasta la Ascensión"
            ,"antiphon"=>"¡Aleluya! Es verdad, el Señor ha resucitado: vengan y
adorémosle. ¡Aleluya!");
        } elseif (($do_id == "6R" && $do_dow > "4") || $do_id == "7R") {
            $returnValue = array("timeframe"=>"Desde el Día de la Ascensión hasta el Día de Pentecostés"
            ,"antiphon"=>"¡Aleluya! Cristo el Señor ascendió a los cielos: vengan
y adorémosle. ¡Aleluya!");
        }
    }
    if (($do_id == "P")) {
        $returnValue = array("timeframe"=>"En el Día de Pentecostés"
        ,"antiphon"=>"¡Aleluya! El Espíritu del Señor renueva la faz de la tierra:
vengan y adorémosle. ¡Aleluya!");
    }
    if (($do_id == "T")) {
        $returnValue = array("timeframe"=>"En el Domingo de Trinidad"
        ,"antiphon"=>"Padre, Hijo y Espíritu Santo, un solo Dios: vengan y
adorémosle.");
    }
    $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only)
    // from start of line to end of subject
    $is_proper = preg_match($pattern, $do_id);
    if ($is_proper) {
        $which = rand(0, 2);
        $day_of[0] = "La tierra es del Señor, pues él la hizo: vengan y
adorémosle.";
        $day_of[1] = "Adoren al Señor en la hermosura de la santidad: vengan
y adorémosle.";
        $day_of[2] = "La misericordia del Señor es para siempre: vengan y
adorémosle.";
        $which_antiphon = $day_of[$which];
        $returnValue = array("timeframe"=>"En otros domingos y días de semana"
        ,"antiphon"=>$which_antiphon);
    }
    return $returnValue;
}
