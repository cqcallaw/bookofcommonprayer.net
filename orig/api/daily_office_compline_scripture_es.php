<?php
// Get the JSON payload from the Angular script
//$data = json_decode(file_get_contents('php://input'));

$cs = getComplineScripture();

$this->qsOutput = json_encode($cs);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getComplineScripture()
{
    $which = rand(0, 3);
    $cw[0] = array("text"=>"Tú estás entre nosotros, oh Señor, y sobre nosotros es
invocado tu Nombre; no nos desampares, Señor nuestro
Dios."
    ,"reference"=>"Jeremías 14:9,22");
    $cw[1] = array("text"=>"Vengan a mí todos los que están trabajados y cargados, y
yo los haré descansar. Lleven mi yugo sobre ustedes, y
aprendan de mí, que soy manso y humilde de corazón; y
hallarán descanso para sus almas; porque mi yugo es
fácil y ligera mi carga."
    ,"reference"=>"San Mateo 11:28-30");
    $cw[2] = array("text"=>"El Dios de paz que resucitó de los muertos a nuestro
Señor Jesucristo, el gran Pastor de las ovejas, por la
sangre del pacto eterno, les haga aptos en toda obra
buena para que hagan su voluntad, haciendo él en
ustedes lo que es agradable delante de él por Jesucristo;
al cual sea la gloria por los siglos de los siglos."
    ,"reference"=>"Hebreos 13:20-21");
    $cw[3] = array("text"=>"Sean sobrios, y velen; porque su adversario el diablo,
como león rugiente, anda alrededor buscando a quien
devorar; al cual resistan firmes en la fe."
    ,"reference"=>"1 San Pedro 5:8-9a");
    $returnValue = $cw[$which];
    return $returnValue;
}
