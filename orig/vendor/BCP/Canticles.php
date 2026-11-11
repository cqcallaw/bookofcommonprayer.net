<?php
namespace BCP;

class Canticles
{
    public $canticleList    = null;
    private $db             = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param string collectType T|C Either Traditional or Contemporary
     */
    public function __construct($db)
    {
        $this->db = $db;
        self::getCanticles($this->db);
    }
    /**
     * getCollects - retrieves the collects as either traditional or contemporary
     *
     * @param object $db Data Connector PDO Object
     */
    public function getCanticles()
    {
        $query = "SELECT * FROM canticles ORDER BY cant_id ASC";
        $statement = $this->db->dbh->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $data) {
            $canticle_id = $data["cant_id"];
            $data_array = array("id"=>$data["cant_id"],"title"=>$data["cant_title"],"latin"=>$data["cant_latin"],
                    "ref"=>$data["cant_ref"],"instr"=>$data["cant_instr"],"cont"=>$data["cant_cont"]);
            // $json = json_encode($data_array);
            // $multi_array[$canticle_id] = $json;
            $multi_array[$canticle_id] = $data_array;
        }
        //$this->canticleList = json_encode($multi_array);
        $this->canticleList = $multi_array;
    }
}
