<?php
namespace BCP;

use Exception;
use PDO;

/**
 * This class that returns the readings from the database based on
 * the information passed in via an array from the API controller
 *
 * @author C. Mike Seiler <cmikeseiler@gmail.com>
 * @version 1.0
 * @copyright 2016 C. Mike Seiler
 *
 * @param object $db The Database object instantiated in the API controller
 * @param array $args The array of arguments passed from the API controller,
 * containing the year number (numeric), the id (alpha_numeric), day of week
 * (numeric)
 */
#[\AllowDynamicProperties]
class LectionaryReadings
{
    public $db         = null;
    public $year       = null;
    public $id         = null;
    public $lectionary_table     = null;
    public $resultSet  = null;

    /**
     * Constructor - needs all parameters passed to be of any use
     * @param object $db Database object use to pull data
     * @param array $args Array of elements needed to pull data from schema
     */
    public function __construct($db, array $args)
    {
        $this->db = isset($db) ? $db : null;
        if (!isset($this->db)) {
            throw new Exception("Database Object not set in Daily Office Readings Class.");
        }
        // The Year Number must be set, as it defines where the readings come from
        $this->year = ( isset($args[0]) && (ctype_alpha($args[0])) )? $args[0] : null;
        // The ID must be set, as they define the specific readings by year
        // $this->id = ( isset($args[1]) && (ctype_alnum($args[1])) ) ? $args[1] : null;
        $this->id = ( isset($args[1]) ) ? $args[1] : null;
        $this->lectionary_table = ( isset($args[2]) ) ? $args[2] : null;

        if (isset($this->year) && isset($this->id) && isset($this->lectionary_table)) {
            $this->resultSet = self::getReferences();
        } else {
            throw new Exception("Year & id elements are not set, or are not strings.");
        }
    }
    /**
     * private function getReferences - retrieves the references from the parameters sent, then calls
     * the getReadings function in turn
     *
     */
    private function getReferences()
    {
        if ($this->lectionary_table == "sunday_lectionary") {
            $query = $this->db->dbh->prepare("
                  SELECT trim(sl_psalm) as psalm
                 , trim(sl_ot) as ot
                 , trim(sl_nt) as nt
                 , trim(TRAILING '\n' FROM sl_gospel) as gospel
                    FROM sunday_lectionary
                   WHERE sl_year_no = :year
                     AND sl_id = :id
                  ");
        } elseif ($this->lectionary_table == "RCL_sunday_lectionary") {
            $query = $this->db->dbh->prepare("
                  SELECT trim(sl_psalm) as psalm
                 , trim(sl_ot) as ot
                 , trim(sl_nt) as nt
                 , trim(TRAILING '\n' FROM sl_gospel) as gospel
                    FROM RCL_sunday_lectionary
                   WHERE sl_year_no = :year
                     AND sl_id = :id
                  ");
        }
        $query->bindParam(":year", $this->year);
        $query->bindParam(":id", $this->id);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        list($this->ot_list, $this->ot_type) =
            self::getReadingsArray($results[0]['ot']);
        list($this->nt_list, $this->nt_type) =
            self::getReadingsArray($results[0]['nt']);
        list($this->gospel_list, $this->gospel_type) =
            self::getReadingsArray($results[0]['gospel']);
        list($this->psalm,$this->psalm_type) =
            self::getReadingsArray($results[0]['psalm']);
        return $results;
    }

    /**
     *  getReadingsArray - returns an array of readings,
     *  which is either a single indexed array, or double
     * @param string $reference The Individual Column returned
     * from the data call
     */
    private function getReadingsArray($reference)
    {
        if (strstr($reference, "&&")) {
            $returnArray = explode("&&", $reference);
            $reading_type = "AND";
        } elseif (strstr($reference, " or ")) {
            $returnArray = explode(" or ", $reference);
            $reading_type = "OR";
        } else {
            $returnArray[] = $reference;
            $reading_type = "NORMAL";
        }
        $readings = array_map('trim', $returnArray);
        return array($readings, $reading_type);
    }
}
