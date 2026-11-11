<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$nc = getNoondayCollect();

$this->qsOutput = json_encode($nc);

// We know that the antiphon is only for Morning Prayer, so we don't need
// to have the which_prayer flag here, only the rites, for modernized English
function getNoondayCollect()
{
    $which = rand(0, 3);
    $coll[0] = array("Padre celestial, envía tu Santo Espíritu a nuestros
corazones, para que nos dirija y gobierne según tu
voluntad, nos consuele en todas nuestras aflicciones, nos
defienda de todo error, y nos conduzca a toda verdad;
por Jesucristo nuestro Señor. =Amen.=");
    $coll[1] = array("Bendito Salvador, en esta hora colgabas en la cruz,
extendiendo tus brazos amorosos: Concede que todos los
pueblos de la tierra miren hacia ti y sean salvos; por tu
entrañable misericordia.  =Amen.=");
    $coll[2] = array("Salvador todopoderoso, que al mediodía llamaste a tu
siervo San Pablo para ser un apóstol a los gentiles: Te
rogamos que ilumines al mundo con el resplandor de tu
gloria, para que todas las naciones vengan y te adoren; tú
que vives y reinas por los siglos de los siglos.  =Amen.=");
    $coll[3] = array("Señor Jesucristo, que dijiste a tus apóstoles, 'La paz les
dejo, mi paz les doy': No mires nuestros pecados sino la
fe de tu Iglesia; y concédenos la paz y la unidad de esa
Ciudad celestial; donde con el Padre y el Espíritu Santo
tú vives y reinas ahora y por siempre. =Amen.=");
    return $coll[$which];
}
