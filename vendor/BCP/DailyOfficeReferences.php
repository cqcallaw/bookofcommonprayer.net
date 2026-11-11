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
class DailyOfficeReferences
{
    public $db         = null;
    public $yearNo     = null;
    public $do_id      = null;
    public $resultSet  = null;

    /**
     * Constructor - needs all parameters passed to be of any use
     * @param object $db Database object use to pull data
     * @param array $args Array of elements needed to pull data from schema
     */
    public function __construct($db, $do_yearNo, $do_id, $proper_args = null)
    {
        $this->db = isset($db) ? $db : null;
        if (!isset($this->db)) {
            throw new Exception("Database Object not set in Daily Office Readings Class.");
        }

        // The Year Number must be set, as it defines where the readings come from
        $this->yearNo = ( isset($do_yearNo) )? $do_yearNo : null;
        // The ID must be set, as they define the specific readings by year
        $this->do_id = ( isset($do_id) ) ? $do_id : null;
        if (isset($proper_args)) {
            $this->p_prop = $proper_args[0];
            $this->t_prop = $proper_args[1];
        }

        if (isset($this->yearNo) && isset($this->do_id)) {
            $this->resultSet = self::getReferences();
        } else {
            throw new Exception("Year & id elements are not set!");
        }
    }
    /**
     * private function getReferences - retrieves the references from the parameters sent, then calls
     * the getReadings function in turn
     *
     */
    private function getReferences()
    {
        $q = "(
SELECT do_id, do_name, do_psalms, do_ot, do_nt, do_gospel,
    CASE
        WHEN (do_dow = '') then ''
        WHEN (do_dow = 0) THEN 'Sunday'
        WHEN (do_dow = 1) THEN 'Monday'
        WHEN (do_dow = 2) THEN 'Tuesday'
        WHEN (do_dow = 3) THEN 'Wednesday'
        WHEN (do_dow = 4) THEN 'Thursday'
        WHEN (do_dow = 5) THEN 'Friday'
        WHEN (do_dow = 6) THEN 'Saturday'
    END AS week_day, do_dow
FROM daily_office
WHERE do_yearNo=:doYearNo
 AND do_id=:do_id)";
        if (($this->do_id == 'P') || ($this->do_id == 'T')) {
            $q .= "
UNION 
(SELECT do_id, do_name, do_psalms, do_ot, do_nt, do_gospel,
    CASE
        WHEN (do_dow = '') then ''
        WHEN (do_dow = 0) THEN 'Sunday'
        WHEN (do_dow = 1) THEN 'Monday'
        WHEN (do_dow = 2) THEN 'Tuesday'
        WHEN (do_dow = 3) THEN 'Wednesday'
        WHEN (do_dow = 4) THEN 'Thursday'
        WHEN (do_dow = 5) THEN 'Friday'
        WHEN (do_dow = 6) THEN 'Saturday'
    END AS week_day, do_dow
  FROM daily_office
 WHERE do_yearNo='".$this->yearNo."'";
            if ($this->do_id == 'P') {
                $q .= " AND do_id='".$this->p_prop."'";
            } elseif ($this->do_id == 'T') {
                $q .= " AND do_id='".$this->t_prop."'";
            }
            $q .= " AND do_dow > 0)";
        }
        $q .= " ORDER BY do_dow";
        $query = $this->db->dbh->prepare($q);
        $this->query = $query;
        $query->bindParam(":doYearNo", $this->yearNo);
        $query->bindParam(":do_id", $this->do_id);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
