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
class DailyOfficeReadings
{
    public $db         = null;
    public $yearNo     = null;
    public $do_id      = null;
    public $do_dow     = null;
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
        $this->yearNo = ( isset($args[0]) && (is_numeric($args[0])) )? $args[0] : null;
        // The ID must be set, as they define the specific readings by year
        // $this->do_id = ( isset($args[1]) && (ctype_alnum($args[1])) ) ? $args[1] : null;
        // removed ctype_alnum as do_id can be things like 12-29, and it fails with the above
        $this->do_id = ( isset($args[1]) ) ? $args[1] : null;
        // Day of Week is not necessarily set, as the holy days attach to extract
        // dates and not to specific days of the week, such as Monday in 7 Easter, etc.
        $this->do_dow = ( isset($args[2]) && (is_numeric($args[2])) ) ? $args[2] : null;

        if (isset($this->yearNo) && isset($this->do_id)) {
            $this->resultSet = self::getReferences();
        } else {
            throw new Exception("Year & id elements are not set, or are not numeric.");
        }
    }
    /**
     * private function getReferences - retrieves the references from the parameters sent, then calls
     * the getReadings function in turn
     *
     */
    private function getReferences()
    {
        if (!isset($this->do_dow)) {
            $query = $this->db->dbh->prepare("
                    SELECT trim(do_psalms) psalms
                 , trim(do_ot) ot
                 , trim(do_nt) nt
                 , trim(TRAILING '\n' FROM do_gospel) gospel
                      FROM daily_office
                     WHERE do_yearNo=:doYearNo
                       AND do_id=:do_id
                    ");
            $query->bindParam(":doYearNo", $this->yearNo);
            $query->bindParam(":do_id", $this->do_id);
        } else {
            $query = $this->db->dbh->prepare("
      SELECT trim(do_psalms) psalms
             , trim(do_ot) ot
             , trim(do_nt) nt
             , trim(TRAILING '\n' FROM do_gospel) gospel
        FROM daily_office do
       WHERE do_id = :do_id
         AND do_yearNo = :doYearNo
         AND do_dow = :do_dow
                    ");
            $query->bindParam(":doYearNo", $this->yearNo);
            $query->bindParam(":do_id", $this->do_id);
            $query->bindParam(":do_dow", $this->do_dow);
        }
        $query->execute();
        $this->query = $query;
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        list($this->ot_list, $this->ot_type) =
            self::getReadingsArray($results[0]['ot']);
        list($this->nt_list, $this->nt_type) =
            self::getReadingsArray($results[0]['nt']);
        list($this->gospel_list, $this->gospel_type) =
            self::getReadingsArray($results[0]['gospel']);
        list($this->m_psalms,$this->e_psalms) =
            self::getPsalmsArray($results[0]['psalms']);
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
            $returnArray = explode("or", $reference);
            $reading_type = "OR";
        } else {
            $returnArray[] = $reference;
            $reading_type = "NORMAL";
        }
        $readings = array_map('trim', $returnArray);
        return array($readings, $reading_type);
    }

    /**
     * getPsalmsArray - returns an array of morning and
     * evening psalms
     * @param string $reference The list of psalms references
     * @return array Returns an array of morning and evening Psalms
     */
    private function getPsalmsArray($reference)
    {
        $split = explode(":o:", $reference);
        $morning = array_values(array_filter(explode(",", $split[0])));
        $evening = array_values(array_filter(explode(",", $split[1])));
        return array($morning, $evening);
    }
}
