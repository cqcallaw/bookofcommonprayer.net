<?php
// Get the JSON payload from the Angular script
$data = json_decode(file_get_contents('php://input'));

if ($data->id == "LE" && $data->dow >= 3) {
    // force lent after Ash Wednesday
    $data->id = "1L";
}
$cw = getComfortableWords($data->wp, $data->wr, $data->id, $data->dow);

$this->qsOutput = json_encode($cw);

function getComfortableWords($which_prayer, $which_rite, $do_id, $dow)
{
  // if we are in the special holiday ids then we need to make it a
  // number (any number), so it will be treated like a "proper"
    $do_id = (
        ($do_id == 'STST') || ($do_id == 'STJE') || ($do_id == 'HI')
        || ($do_id == '12-29') || ($do_id == '12-30') || ($do_id == '12-31')
        || ($do_id == '1-2') || ($do_id == '1-3') || ($do_id == '1-4')
        || ($do_id == '1-5') || ($do_id == '1-7') || ($do_id == '1-8')
        || ($do_id == '1-9') || ($do_id == '1-10') || ($do_id == '1-11')
        || ($do_id == '1-12') || ($do_id == 'HN')
        )
        ? '12' : $do_id;
    if ($which_prayer == "morning_prayer") {
      // COMFORTABLE WORDS - Spanish only has one morning prayer
        if (($do_id == "1A") || ($do_id == "2A") || ($do_id == "3A") || ($do_id == "4A")) {
            $which = rand(0, 2);
            $a_cw[0] = array("cw"=>"Velen, porque no saben cuándo vendrá el señor de la
casa; si al anochecer, o a la medianoche, o al canto del
gallo, o a la mañana; para que cuando venga de repente,
no los halle durmiendo.","reference"=>"San Marcos 13:35, 36");
            $a_cw[1] = array("cw"=>"Preparen camino al Señor; enderezen calzada en la soledad
a nuestro Dios.","reference"=>"Isaías 40:3");
            $a_cw[2] = array("cw"=>"Se manifestará la gloria del Señor, y toda carne
juntamente la verá.","reference"=>"Isaías 40:5");
            $returnValue = $a_cw[$which];
        }
        if (($do_id == "CM") || ($do_id == "CMII") || ($do_id == "CMIII")) {
            $which = rand(0, 1);
            $cm_cw[0] = array("cw"=>"He aquí les doy nuevas de gran gozo, que será para todo
el pueblo: que les ha nacido hoy, en la ciudad de David,
un Salvador, que es Cristo el Señor.","reference"=>"San Lucas 2:10, 11");
            $cm_cw[1] = array("cw"=>"He aquí el tabernáculo de Dios con los hombres, y él
morará con ellos; y ellos serán su pueblo, y Dios mismo
estará con ellos como su Dios."
                ,"reference"=>"Apocalipsis 21:3");
            $returnValue = $cm_cw[$which];
        }
        if (($do_id == "1E") || ($do_id == "2E") || ($do_id == "3E") || ($do_id == "4E") ||
          ($do_id == "5E") || ($do_id == "6E") || ($do_id == "7E") || ($do_id == "8E") ||
          ($do_id == "LE")) {
            $which = rand(0, 2);
            $e_cw[0] = array("cw"=>"Andarán las naciones a tu luz, y los reyes al resplandor
de tu nacimiento.",
            "reference"=>"Isaías 60:3");
            $e_cw[1] = array("cw"=>"Te di por luz de las naciones, para que seas mi salvación
hasta lo postrero de la tierra.","reference"=>"Isaías 49:6b");
            $e_cw[2] = array("cw"=>"Desde donde el sol nace hasta donde se pone, es grande
mi Nombre entre las naciones; y en todo lugar se ofrece
a mi Nombre incienso y ofrenda limpia, porque grande
es mi Nombre entre las naciones, dice el Señor de los
ejércitos.","reference"=>"Malaquías 1:11");
            $returnValue = $e_cw[$which];
        }
        if (($do_id == "1L") || ($do_id == "2L") || ($do_id == "3L") ||
          ($do_id == "4L") || ($do_id == "5L")) {
            $which = rand(0, 4);
            $l_cw[0] = array("cw"=>"Si decimos que no tenemos pecado, nos engañamos a
nosotros mismos, y la verdad no está en nosotros. Si
confesamos nuestros pecados, él es fiel y justo para
perdonar nuestros pecados, y limpiarnos de toda
maldad.","reference"=>"1 San Juan 1:8, 9");
            $l_cw[1] = array("cw"=>"Rasguen su corazón, y no sus vestidos, y conviértanse al
Señor su Dios; porque misericordioso es y clemente,
tardo para la ira y grande en misericordia, y que se duele
del castigo.","reference"=>"Joel 2:13");
            $l_cw[2] = array("cw"=>"Me levantaré e iré a mi padre, y le diré: 'Padre, he
pecado contra el cielo y contra ti. Ya no soy digno de ser
llamado tu hijo'.","reference"=>"San Lucas 15:18, 19");
            $l_cw[3] = array("cw"=>"Del Señor nuestro Dios es el tener misericordia y el
perdonar, aunque contra él nos hemos rebelado, y no
obedecimos a la voz del Señor nuestro Dios, para Andar
en sus leyes que él puso delante de nosotros.","reference"=>"Daniel 9:9, 10");
            $l_cw[4] = array("cw"=>"Jesús dijo: 'Si alguno quiere venir en pos de mí, niéguese
a sí mismo, y tome su cruz, y sígame'.","reference"=>"San Marcos 8:34");
            $returnValue = $l_cw[$which];
        }
        if ($do_id == "PS") {
            $which = rand(0, 1);
            $ps_cw[0] = array("cw"=>"Todos nosotros nos descarriamos como ovejas, cada cual
se apartó por su camino; mas el Señor cargó en él el
pecado de todos nosotros.","reference"=>"Isaías 53:6");
            $ps_cw[1] = array("cw"=>"¿No les conmueve a cuantos pasan por el camino?
Miren, y vean si hay dolor como mi dolor que me ha
venido; porque el Señor me ha angustiado.","reference"=>"Lamentaciones 1:12");
            $returnValue = $ps_cw[$which];
        }
        if (($do_id == "R") || ($do_id == "2R") || ($do_id == "3R") || ($do_id == "4R") ||
        ($do_id == "5R") || ($do_id == "6R") || ($do_id == "7R") || ($do_id == "P") ||
        ($do_id == "AD")) {
            $which = rand(0, 5);
            $r_cw[0] = array("cw"=>"¡Aleluya! Cristo ha resucitado.
Es verdad! El Señor ha resucitado. ¡Aleluya!","reference"=>"");
            $r_cw[1] = array("cw"=>"Este es el día en que actuó el Señor; regocijémonos y
alegrémonos en él.","reference"=>"Salmo 118:24");
            $r_cw[2] = array("cw"=>"Gracias sean dadas a Dios, que nos da la victoria por
medio de nuestro Señor Jesucristo.","reference"=>"1 Corintios 15:57");
            $r_cw[3] = array("cw"=>"Si han resucitado con Cristo, busquen las cosas de arriba,
donde está Cristo sentado a la diestra de Dios.","reference"=>"Colosenses 3:1");
            $r_cw[4] = array("cw"=>"No entró Cristo en el santuario hecho de mano, figura
del verdadero, sino en el cielo mismo para presentarse
ahora por nosotros ante Dios.","reference"=>"Hebreos 9:24");
            $r_cw[5] = array("cw"=>"Recibirán poder, cuando haya venido sobre ustedes el
Espíritu Santo, y me serán testigos en Jerusalén, en toda
Judea, en Samaria, y hasta lo último de la tierra.","reference"=>"Hechos 1:8");
            $returnValue = $r_cw[$which];
        }
        if ($do_id == "T") {
            $which = rand(0, 3);
            $t_cw[0] = array("cw"=>"Santo, santo, santo es el Señor Dios todopoderoso, el
que era, el que es y el que ha de venir.","reference"=>"Apocalipsis 4:8");
            $t_cw[1] = array("cw"=>"Damos gracias al Padre que nos hizo aptos para
participar de la herencia de los santos en luz.","reference"=>"Colosenses 1:12");
            $t_cw[2] = array("cw"=>"Ya no son extranjeros ni advenedizos, sino
conciudadanos de los santos, y miembros de la familia
de Dios.","reference"=>"Efesios 2:19");
            $t_cw[3] = array("cw"=>"Por toda la tierra salió su sonido, y hasta el extremo del
mundo su mensaje.","reference"=>"Salmo 19:4");
            $returnValue = $t_cw[$which];
        }
        $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only)
          // from start of line to end of subject
        $is_proper = preg_match($pattern, $do_id);
        if ($is_proper) {
            $which = rand(0, 6);
            $p_cw[0] = array("cw"=>"Gracia y paz a ustedes, de Dios nuestro Padre y del Señor
Jesucristo.","reference"=>"Filipenses 1:2");
            $p_cw[1] = array("cw"=>"Me alegré cuando me dijeron: 'Vamos a la casa del
Señor'.","reference"=>"Salmo 122:1");
            $p_cw[2] = array("cw"=>"Sean gratos los dichos de mi boca y la meditación de mi
corazón delante de ti, oh Señor, Roca mía y Redentor
mío.","reference"=>"Salmo 19:14");
            $p_cw[3] = array("cw"=>"Envía tu luz y tu verdad; que éstas me guíen, y me
conduzcan a tu santo monte, a tus moradas.","reference"=>"Salmo 43:3");
            $p_cw[4] = array("cw"=>"El Señor está en su santo templo; calle delante de él toda
la tierra.","reference"=>"Habacuc 2:20");
            $p_cw[5] = array("cw"=>"La hora viene, y ahora es, cuando los verdaderos
adoradores adorarán al Padre en espíritu y en verdad;
porque también el Padre tales adoradores busca que le
adoren.","reference"=>"San Juan 4:23");
            $p_cw[6] = array("cw"=>"Así dijo el Alto y Sublime, el que habita la eternidad, y
cuyo Nombre es el Santo: 'Yo habito en la altura y la
santidad, y con el quebrantado y humilde de espíritu,
para hacer vivir el espíritu de los humildes, y para
vivificar el corazón de los quebrantados'.","reference"=>"Isaías 57:15");
            $returnValue = $p_cw[$which];
        }
    // End of Morning Prayer
    } elseif ($which_prayer == "evening_prayer") {
        $which = rand(0, 7);
        $which1 = rand(0, 7);
        while ($which1 == $which) {
            $which1 = rand(0, 7);
        }
        $comfort[0] = array("cw"=>"Ascienda mi oración como incienso ante tu presencia, él
alzar de mis manos como el sacrificio vespertino.","reference"=>"Salmo 141:2");
        $comfort[1] = array("cw"=>"Gracia y paz a ustedes, de Dios nuestro Padre y del Señor
Jesucristo.","reference"=>"Filipenses 1:2");
        $comfort[2] = array("cw"=>"Adoren al Señor en la hermosura de la santidad; tiemble
delante de él toda la tierra.","reference"=>"Salmo 96:9");
        $comfort[3] = array("cw"=>"Tuyo es el día, tuya también la noche; tú estableciste la
luna y el sol. Tú fijaste todos los linderos de la tierra; el
verano y el invierno tú los formaste.","reference"=>"Salmo 74:15,16");
        $comfort[4] = array("cw"=>"Bendeciré al Señor que me aconseja; aun en las noches
me enseña mi corazón. Al Señor he puesto siempre
delante de mí; porque está a mi diestra no seré
conmovido.","reference"=>"Salmo 16:7,8");
        $comfort[5] = array("cw"=>"Busquen al que hace las Pléyades y el Orión, y vuelve las
tinieblas en mañana, y hace oscurecer el día como noche;
el que llama a las aguas del mar, y las derrama sobre la
faz de la tierra; el Señor es su Nombre.","reference"=>"Amós 5:8");
        $comfort[6] = array("cw"=>"Si dijere: 'Ciertamente las tinieblas me encubrirán, y aún
la luz se hará noche alrededor de mí', las tinieblas no son
oscuras para ti; la noche resplandece como el día; lo
mismo te son las tinieblas que la luz.","reference"=>"Salmo 139:10,11");
        $comfort[7] = array("cw"=>"Jesús dijo: 'Yo soy la luz del mundo; el que me sigue, no
andará en tinieblas, sino que tendrá la luz de la
vida'.","reference"=>"San Juan 8:12");
        $returnValue = array("first"=>$comfort[$which],"second"=>$comfort[$which1]);
        // End of Evening Prayer
    }

    return $returnValue;
}
