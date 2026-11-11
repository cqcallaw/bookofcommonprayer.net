<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

$ac = getAdditionalCollect($data->wr, $data->wp, $data->dow);

$this->qsOutput = json_encode($ac);

function getAdditionalCollect($which_rite, $which_prayer, $do_dow)
{
    if ($which_prayer == "morning_prayer") {
        if ($do_dow == "0") {
            $returnValue = array("name"=>"Colecta para los domingos"
            , "text" => "Oh Dios, tú nos alegras con el recuerdo semanal de la
gloriosa resurrección de tu Hijo nuestro Señor:
Concédenos tal bendición en este día, mediante nuestra
adoración, que ocupemos todos los días de esta semana
en tu favor; por Jesucristo nuestro Señor. =Amen.=");
        } elseif ($do_dow == "5") {
              $returnValue = array("name"=>"Colecta para los viernes"
              , "text" => "Dios todopoderoso, cuyo muy amado Hijo no ascendió
al gozo de tu presencia sin antes padecer, ni entró en
gloria sin antes ser crucificado: Concédenos, por tu
misericordia, que nosotros, caminando por la vía de la
cruz, encontremos que ésta es la vía de la vida y de la
paz; por Jesucristo nuestro Señor.  =Amen.=");
        } elseif ($do_dow == "6") {
              $returnValue = array("name"=>"Colecta para los sábados"
              , "text" => "Dios todopoderoso, que después de la creación del
mundo descansaste de todos tus trabajos, y santificaste
un día de reposo para todas tus criaturas: Concede que
nosotros, apartando toda ansiedad terrenal, nos
dispongamos debidamente para el servicio de tu
santuario, y que nuestro descanso aquí en la tierra sea
una preparación para el reposo eterno en el cielo, que has
prometido a tu pueblo; por Jesucristo nuestro Señor.  =Amen.=");
        } else {
            $which = rand(0, 3);
            $collect[0] = array("name"=>"Colecta por la renovación de la vida"
              , "text" => "Oh Dios, Rey eterno, que con tu luz separas el día de la
noche, y transformas en claridad la sombra de muerte:
Arroja de nosotros todo mal deseo, inclina nuestro
corazón a guardar tu ley, y guía nuestros pasos por el
sendero de la paz; para que, al hacer con gusto tu
voluntad durante el día, nos alegre darte gracias cuando
llegue la noche; por Jesucristo nuestro Señor.  =Amen.=");
            $collect[1] = array("name"=>"Colecta por la paz"
              , "text" => "Oh Dios, autor de la paz y amante de la concordia,
conocerte es vida eterna, y servirte, plena libertad:
Defiende a estos tus humildes siervos de todos los
asaltos de nuestros enemigos; para que, confiados en tu
protección, no temamos la fuerza de ningún adversario;
por el poder de Jesucristo nuestro Señor.  =Amen.=");
            $collect[2] = array("name"=>"Colecta por la gracia"
              , "text" => "Señor Dios, todopoderoso y eterno Padre, nos hiciste
llegar sanos y salvos hasta este nuevo día: Consérvanos
con tu gran poder, para que no caigamos en pecado, ni
nos venza la adversidad; y, en todo lo que hagamos,
dirígenos a realizar tus designios; por Jesucristo nuestro
Señor.  =Amen.=");
            $collect[3] = array("name"=>"Colecta para guía divina"
              , "text" => "Padre celestial, en ti vivimos, nos movemos y tenemos el
ser: Te suplicamos humildemente que nos guíes y
gobiernes con tu Santo Espíritu, para que en todos los
afanes y quehaceres de nuestra vida no te olvidemos, sino
que recordemos que siempre caminamos en tu presencia;
por Jesucristo nuestro Señor.  =Amen.=");
              $returnValue = $collect[$which];
        }
    } elseif ($which_prayer == "evening_prayer") {
        if ($do_dow == "0") {
            $returnValue = array("name"=>"Colecta para los domingos"
            , "text" => "Señor Dios, cuyo Hijo nuestro Salvador Jesucristo
triunfó sobre los poderes de la muerte, y nos preparó un
lugar en la nueva Jerusalén: Concede que nosotros, los
que hoy te hemos dado gracias por su resurrección, te
alabemos en esa Ciudad en donde él es la luz, y donde
vive y reina por los siglos de los siglos.  =Amen.=");
        } elseif ($do_dow == "5") {
            $returnValue = array("name"=>"Colecta para los viernes"
            , "text" => "Señor Jesucristo, por tu muerte quitaste el aguijón de la
muerte: Concede a tus siervos que caminemos de tal
modo en la fe hacia el lugar a donde tú nos has
precedido, que al fin durmamos apaciblemente en ti, y
despertemos a tu semejanza; por amor de tu tierna
misericordia.  =Amen.=");
        } elseif ($do_dow == "6") {
            $returnValue = array("name"=>"Colecta para los sábados"
            , "text" => "Oh Dios, fuente de luz eterna: Derrama tu día
interminable sobre los que aguardamos tu venida, para
que nuestros labios te alaben, nuestras vidas te bendigan
y nuestra adoración en la mañana te dé gloria; por
Jesucristo nuestro Señor.  =Amen.=");
        } else {
            $which = rand(0, 3);
            $collect[0] = array("name"=>"Colecta por ayuda en todo peligro"
            , "text" => "Sé nuestra luz en las tinieblas, oh Señor, y por tu gran
misericordia defiéndenos de todos los peligros y riesgos
de esta noche; por amor de tu único Hijo, nuestro
Salvador Jesucristo.  =Amen.=");
            $collect[1] = array("name"=>"Colecta por la paz"
            , "text" => "Santísimo Dios, manantial de todos los buenos deseos, de
todos los juicios rectos y de todas las obras justas:
Concede a tus siervos la paz que el mundo no les puede
dar, a fin de que nuestras mentes estén fijas en hacer tu
voluntad, y para que, librados del temor de todo
enemigo, vivamos en paz y tranquilidad; por las
bondades de Cristo Jesús nuestro Salvador.  =Amen.=");
            $collect[2] = array("name"=>"Colecta para pedir protección"
            , "text" => "Oh Dios, tú eres la vida de los que viven, la luz de los
fieles, la fortaleza de los que trabajan, y el descanso de
los muertos: Te damos gracias por las bendiciones del día
que termina, y humildemente te suplicamos nos des tu
protección durante la noche que comienza. Llévanos en
seguridad hasta las horas del alba; por aquél que murió y
resucitó por nosotros, tu Hijo nuestro Salvador
Jesucristo.  =Amen.=");
            $collect[3] = array("name"=>"Colecta por la presencia de Cristo"
            , "text" => "Quédate con nosotros, Señor Jesús, ahora que la noche se
acerca y ha pasado el día. Sé nuestro compañero en el
camino, enciende nuestros corazones, y despierta la
esperanza, para que te conozcamos tal como te revelas en
las Escrituras y en la fracción del pan. Concede esto por
amor de tu Nombre.  =Amen.=");
            $returnValue = $collect[$which];
        }
    }
    return $returnValue;
}
