<?php
// Get the JSON payload from the Angular script
//$data = json_decode(file_get_contents('php://input'));

$cc = getComplineCollect();

$this->qsOutput = json_encode($cc);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getComplineCollect()
{
    $which = rand(0, 3);
    $coll[0] = array("Sé nuestra luz en las tinieblas, oh Señor, y por tu gran
misericordia defiéndenos de todos los peligros y riesgos
de esta noche; por amor de tu único Hijo, nuestro
Salvador Jesucristo.  =Amen.=");
    $coll[1] = array("Sé con nosotros, oh Señor misericordioso, y protégenos
en las horas de esta noche, para que, los que estamos
agobiados por los cambios y azares de esta vida,
descansemos sólo en ti, que nunca cambias; por
Jesucristo nuestro Señor.  =Amen.=");
    $coll[2] = array("Míranos, oh Señor, desde tu trono celestial, e ilumina
esta noche con tu célico resplandor; para que, así de
noche como de día, tu pueblo glorifique tu santo
Nombre; por Jesucristo nuestro Señor.  =Amen.=");
    $coll[3] = array("Visita, oh Señor, este lugar, y ahuyenta de él todas las
asechanzas del enemigo; que tus santos ángeles moren
con nosotros para preservarnos en paz; y que tu
bendición sea siempre sobre nosotros; por Jesucristo
nuestro Señor.  =Amen.=");
    $returnValue = $coll[$which];
    return $returnValue;
}
