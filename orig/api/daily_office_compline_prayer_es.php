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
    $pray[0] = array("Vela, oh amantísimo Señor, con los que trabajan, o velan,
o lloran esta noche. A tus ángeles manda que guarden a
los que duermen. Cuida a los enfermos, Cristo Señor;
otorga reposo a los cansados, bendice a los moribundos,
consuela a los que sufren, compadécete de los afligidos,
escuda a los gozosos. Todo esto te pedimos por tu gran
amor.  =Amen.=");
    $pray[1] = array("Oh Dios, tu providencia inagotable sustenta al mundo en
que vivimos y aun nuestra propia vida: Vela, :le día y de
noche, por los que trabajan mientras otros duermen, y
concede que jamás olvidemos que nuestra vida común
depende de nuestras faenas mutuas; por Jesucristo
nuestro Señor.  =Amen.=");
    $returnValue = $pray[$it];
    return $returnValue;
}
