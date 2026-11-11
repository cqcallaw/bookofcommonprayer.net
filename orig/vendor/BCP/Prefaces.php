<?php
namespace BCP;

class Prefaces
{
    public $prefaceList = null;
    private $db         = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param string prefaceType T|C Either Traditional or Contemporary
     */
    public function __construct($prefaceType, $db)
    {
        self::getPrefaces($prefaceType, $db);
    }
    /**
     * getCollects - retrieves the collects as either traditional or contemporary
     *
     * @param string collectType T|C Either Traditional or Contemporary
     */
    private function getPrefaces($prefaceType, $db)
    {
        $type = ($prefaceType == "C") ? "_contemporary" : "";
        $query = "SELECT *
                    FROM prefaces".$type;
        if (isset($pref_id)) {
            $query .= " WHERE pref_id = :pref_id";
        }
        $statement = $db->dbh->prepare($query);
        if (isset($pref_id)) {
            $statement->bindParam(":pref_id", $pref_id);
        }
        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $data) {
            $pref_id = $data['pref_id'];
            $data_array = array("pref_id"=>$data['pref_id'],"pref_name"=>$data['pref_name'],
                    "pref_instr"=>$data['pref_instr'],"pref_text"=>$data['pref_text']);
            $json = json_encode($data_array);
            $multi_array[$pref_id] = $json;
        }
        $this->prefaceList = json_encode($multi_array);
    }

    /**
     * processPrefs - Process the prefaces that got pulled from DB
     * @param  [type] $preface_list [description]
     * @return [type]               [description]
     */
    public function processPrefs($preface_list)
    {
        $prefaces = "";
        foreach ($preface_list as $pref) {
            $pref_obj = json_decode($pref);
            $pref_name = $pref_obj->pref_name;
            $pref_instr = $pref_obj->pref_instr;
            $pref_text = $pref_obj->pref_text;
            $prefaces .= "<p><strong>$pref_name</strong><br>";
            if ($pref_instr) {
                $prefaces .= "<em>$pref_instr</em><br>";
            }
            $pref_text = str_replace("*or*", "<p><em> - or this - </em></p>", $pref_text);
            $pref_text = str_replace("=Amen.=", "<em>Amen.</em>", $pref_text);
            $prefaces .= "$pref_text</p>";
        }
        return $prefaces;
    }
}
