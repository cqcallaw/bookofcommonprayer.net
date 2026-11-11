<?php
namespace BCP;

class Collects
{
    public $collectList = null;
    private $db         = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param string collectType T|C Either Traditional or Contemporary
     * @param string coll_id If you want to call a particular collect, specify the ID
     */
    public function __construct($collectType, $db, $coll_id = null)
    {
        self::getCollects($collectType, $db, $coll_id);
    }
    /**
     * getCollects - retrieves the collects as either traditional or contemporary
     *
     * @param string collectType T|C Either Traditional or Contemporary
     * @param string coll_id If you want to call a particular collect, specify the ID
     */
    public function getCollects($collectType, $db, $coll_id = null)
    {
        $type = ($collectType == "C") ? "_contemporary" : "";
        $query = "SELECT *
                    FROM collects".$type;
        if (isset($coll_id)) {
            $query .= " WHERE id = :coll_id";
        }
        $statement = $db->dbh->prepare($query);
        if (isset($coll_id)) {
            $statement->bindParam(":coll_id", $coll_id);
        }

        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $data) {
            $collect_id = $data["id"];
            $data_array = array("id"=>$data["id"],"name"=>$data["name"],
                    "text"=>$data["text"],"preface"=>$data["preface"],"pref_id"=>$data["pref_id"]);
            $json = json_encode($data_array);
            $multi_array[$collect_id] = $json;
        }
        $this->collectList = json_encode($multi_array);
    }

    /**
     * processMultiArray - process the array and return the list of collects
     *
     * @param array $multiArray Array of items from the database
     */
    public function processMultiArray($multi_array)
    {
        $return = '';
        $collect_order = array ('1A','2A','3A','4A','CM','1C','HN','2C','E','1E','2E','3E','4E',
        '5E','6E','7E','8E','LE','AW','1L','2L','3L','4L','5L','PS','PS-1','PS-2','PS-3',
        'PS-4','PS-5','PS-6','R','R-1','R-2','R-3','R-4','R-5','R-6','2R','3R','4R','5R',
        '6R','AD','7R','P','T','CC','1','2','3','4','5','6','7','8','9','10','11','12',
        '13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28',
        '29','11-30','12-21','12-26','12-27','12-28','1-18','1-25','2-2','2-24','3-19',
        '3-25','4-25','5-1','5-31','6-11','6-24','6-29','7-4','7-22','7-25','8-6','8-15',
        '8-24','9-14','9-21','9-29','10-18','10-23','10-28','THK','11-1','SL','mart',
        'miss','past','tchr','monk','snt','vo1','vo2','vo3','vo4','vo5','vo6','vo7','vo8',
        'vo9','vo10','vo11','vo12','vo13','vo14','vo15-1','vo15-2','vo15-3','vo16','vo17',
        'vo18','vo19-1','vo19-2','vo19-3','vo20','vo21','vo22','vo23','vo24','vo25');

        foreach ($collect_order as $id) {
            $coll_obj = json_decode($multi_array->$id);
            $name = $coll_obj->name;
            $text = $coll_obj->text;
            $preface = $coll_obj->preface;
            $pref_id = $coll_obj->pref_id;
            // use ID to check for when to put in the anchor points
            if ($id == "11-30") {
                $in_hd = 1;
                $in_vo = 0;
                $return .= "<div id='holy_days'>
                <a target=\"_self\" href=\"#top\">
                <i class='fa fa-arrow-circle-o-up' aria-hidden='true'></i></a>
                <h2>Holy Days</h2></div>";
            }
            if ($id == "mart") {
                $in_hd = 0;
                $in_vo = 0;
                $return .= "<div id='saints'>
                <a target=\"_self\" href=\"#top\">
                <i class='fa fa-arrow-circle-o-up' aria-hidden='true'></i></a>
                <h2>The Common of Saints</h2>
                </div>";
            }
            if ($id == "vo1") {
                    $in_hd = 0;
                    $in_vo = 1;
                    $return .= "<div id='various'>
                    <a target=\"_self\" href=\"#top\">
                    <i class='fa fa-arrow-circle-o-up' aria-hidden='true'></i></a>
                    <h2>Various Occasions</h2></div>";
            }
            if (isset($in_hd)) {
                if (($id == "SL") || ($id == "THK")) {
                        $return .= "<p><strong>$name</strong><br>";
                        $text = str_replace("*or this*", "<p><em> - or this - </em></p>", $text);
                        $text = str_replace("=Amen.=", "<em>Amen.</em>", $text);
                        $return .= "$text<br>";
                        $return .= "<em>$preface</em></p>";
                } else {
                        $return .= "<p><strong>$name</strong> ($id)<br>";
                        $text = str_replace("*or this*", "<p><em> - or this - </em></p>", $text);
                        $text = str_replace("=Amen.=", "<em>Amen.</em>", $text);
                        $return .= "$text<br>";
                        $return .= "<em>$preface</em></p>";
                }
            } elseif (isset($in_vo)) {
                    $clean_id = str_replace("vo", "", $id);
                    $return .= "<p><strong>$clean_id: $name</strong><br>";
                    $text = str_replace("*or this*", "<p><em> - or this - </em></p>", $text);
                    $text = str_replace("=Amen.=", "<em>Amen.</em>", $text);
                    $return .= "$text<br>";
                    $return .= "<em>$preface</em></p>";
            } else {
                    $return .= "<p><strong>$name</strong><br>";
                    $text = str_replace("*or this*", "<p><em> - or this - </em></p>", $text);
                    $text = str_replace("=Amen.=", "<em>Amen.</em>", $text);
                    $return .= "$text<br>";
                    $return .= "<em>$preface</em></p>";
            }
        }
        return $return;
    }
}
