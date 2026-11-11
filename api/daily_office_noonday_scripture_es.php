<?php
// Get the JSON payload from the Angular script
//$data = json_decode(file_get_contents('php://input'));

$ns = getNoondayScripture();

$this->qsOutput = json_encode($ns);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getNoondayScripture()
{
    $which = rand(0, 2);
    $script[0] = array("text"=>"El amor de Dios ha sido derramado en nuestros corazones
por el Espíritu Santo que nos fue dado."
    ,"reference"=>"Romanos 5:5");
    $script[1] = array("text"=>"Si alguno está en Cristo, nueva criatura es; las cosas
viejas pasaron; he aquí todas son hechas nuevas. Y todo
esto proviene de Dios, quien nos reconcilió consigo
mismo por Cristo, y nos dio el ministerio de la
reconciliación."
    ,"reference"=>"2 Corintios 5:17-18");
    $script[2] = array("text"=>"Desde donde el sol nace hasta donde se pone, es grande
mi Nombre entre las naciones, y en todo lugar se ofrece
a mi Nombre incienso y ofrenda limpia, porque grande
es mi Nombre entre las naciones, dice el Señor de los
ejércitos."
    ,"reference"=>"Malaquías 1:11");
    $returnValue = $script[$which];
    return $returnValue;
}
