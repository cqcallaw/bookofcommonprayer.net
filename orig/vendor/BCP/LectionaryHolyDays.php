<?php
namespace BCP;

use PDO;

class LectionaryHolyDays
{
    public $date    = null;
    public $result  = null;
    private $db     = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param string prefaceType T|C Either Traditional or Contemporary
     */
    public function __construct($db, $date)
    {
        $this->db = (isset($db)) ? $db : null;
        $this->date = (isset($date)) ? $date : null;
        self::getHolyDays();
    }
    /**
     * getHolyDays - Get the possibility of holy days
     */
    private function getHolyDays()
    {
        $query = $this->db->dbh->prepare(
            "SELECT *
            FROM holy_days_lectionary
           WHERE hd_id = :holy_day_date"
        );
        $query->bindParam(":holy_day_date", $this->date);
        $query->execute();
        $this->result = $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
