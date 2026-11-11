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
        $which = rand(0, 2);
        $mission[0] = array(
        "Dios todopoderoso y eterno, cuyo Espíritu gobierna y
santifica a todo el cuerpo de tu pueblo fiel: Recibe las
súplicas y oraciones que te ofrecemos por todos los
miembros de tu santa Iglesia, para que en su vocación y
ministerio te sirvan verdadera y devotamente; por
nuestro Señor y Salvador Jesucristo.  =Amen.=");
        $mission[1] = array(
        "Oh Dios, que has hecho de una sola sangre a todos los
pueblos de la tierra, y enviaste a tu bendito Hijo a
predicar la paz, tanto a los que están lejos como a los que
están cerca: Concede que la gente en todo lugar te
busque y te encuentre; trae a las naciones a tu redil;
derrama tu Espíritu sobre toda carne; y apresura la
venida de tu reino; por Jesucristo nuestro Señor.  =Amen.=");
        $mission[2] = array(
        "Señor Jesucristo, tú extendiste tus brazos amorosos sobre
el cruel madero de la cruz, para estrechar a todos los
seres humanos en tu abrazo salvador: Revístenos con tu
Espíritu de tal manera que, extendiendo nuestras manos
en amor, llevemos a quienes no te conocen a reconocerte
y amarte; por el honor de tu Nombre.  =Amen.=");
        $returnValue = $mission[$which];
    } elseif ($which_prayer == "evening_prayer") {
        $which =  rand(0, 2);
        $mission[0] = array("Dios y Padre de todos, los cielos de los cielos te adoran:
Haz que toda la tierra también te rinda culto, que todas
las naciones te obedezcan, que todas las lenguas te
confiesen y bendigan, y que todas las personas en todas
partes te amen y te sirvan en paz; por Jesucristo nuestro
Señor. =Amen.=");
        $mission[1] = array("Vela, oh amantísimo Señor, con los que trabajan, o velan,
o lloran esta noche. A tus ángeles manda que guarden a
los que duermen. Cuida a los enfermos, Cristo Señor;
otorga reposo a los cansados, bendice a los moribundos,
consuela a los que sufren, compadécete de los afligidos,
escuda a los gozosos. Todo esto te pedimos por tu gran
amor. =Amen.=");
        $mission[2] = array("Oh Dios, tú manifiestas en tus siervos las señales de tu
presencia: Envía sobre nosotros el Espíritu de amor, para
que en el compañerismo mutuo tu gracia abundante se
acreciente entre nosotros; por Jesucristo nuestro Señor. =Amen.=");
        $returnValue = $mission[$which];
    }
    return $returnValue;
}
