<?php
// Get the JSON payload from the Angular script
// Don't need it now, but will when I put the o_auth security in
//$data = json_decode(file_get_contents('php://input'));

$c = getConclusion();

$this->qsOutput = json_encode($c);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getConclusion()
{
    $which = rand(0, 2);
    $conclude[0] = array("text"=>"La gracia de nuestro Señor Jesucristo, el amor de Dios y
la comunión del Espíritu Santo sean con todos nosotros,
ahora y siempre.
    =Amen.=","reference"=>"2 Corintios 13:14");
    $conclude[1] = array("text"=>"Que el Dios de la esperanza nos colme de todo gozo y
paz en nuestra fe, por el poder del Espíritu Santo. =Amen.=","reference"=>"Romanos 15:13");
    $conclude[2] = array("text"=>"Gloria a Dios, cuyo poder, actuando en nosotros, puede
realizar todas las cosas infinitamente mejor de lo que
podemos pedir o pensar: Gloria a él en la Iglesia de
generación en generación, y en Cristo Jesús por los siglos
de los siglos. =Amen.=","reference"=>"Efesios 3:20, 21");
    return $conclude[$which];
}
