<?php
namespace BCP;

use PDO;

class Psalms
{
    public $psalmList   = null;
    public $selectors   = null;
    private $db         = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param object $db - PDO Database object
     */
    public function __construct($db)
    {
        $this->db = $db;
        self::getPsalms($db);
    }
    /**
     * getCollects - retrieves the Psalm data and returns a JSON object
     *
     */
    public function getPsalms()
    {
        $statement = $this->db->dbh->prepare("
                SELECT *
                  FROM bcp_psalms
                 ORDER BY chapter,part,verse,daynumber,daytime
                       ASC
                ");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $data) {
            $data_array = array("chapter"=>$data["chapter"],"part"=>$data["part"],
                    "verse"=>$data["verse"],"text"=>$data["text"],"daytime"=>$data["daytime"],
                    "daynumber"=>$data["daynumber"]);
            $json = json_encode($data_array);
            $chapter = $data["chapter"];
            $part = $data["part"];
            $verse = $data["verse"];
            $daytime = $data["daytime"];
            $daynumber = $data["daynumber"];
            $multi_array[$chapter][$part][$verse] = $json;
            // SELECTORS
            $data_array_selectors = array("chapter"=>$chapter,"daynumber"=>$daynumber,
            "daytime"=>$daytime,"part"=>$part);
            $json_selectors = json_encode($data_array_selectors);
            $multi_array_selectors[$daynumber][$daytime][$chapter] = $json_selectors;
        }
        $this->psalmList = json_encode($multi_array);
        $this->selectors = json_encode($multi_array_selectors);
    }
    /**
     * getPsalmSelectors - retrieves only the basic data without the content for use in HTML select
     *
     */
    public function getPsalmSelectors()
    {
        // GET THE DATA WE NEED
        $statement = $this->db->dbh->prepare("
        SELECT DISTINCT chapter,daytime,daynumber,part
          FROM bcp_psalms
           ORDER BY chapter,daynumber,daytime ASC, part DESC");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $data) {
            $chapter = $data["chapter"];
            $daytime = $data["daytime"];
            $daynumber = $data["daynumber"];
            $part = $data["part"];
            $data_array = array("chapter"=>$chapter,"daynumber"=>$daynumber,"daytime"=>$daytime,"part"=>$part);
            $json = json_encode($data_array);
            $multi_array[$daynumber][$daytime][$chapter] = $json;
        }
        $this->selectors = json_encode($multi_array);
    }
}
