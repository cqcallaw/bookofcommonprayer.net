<?php
namespace BCP;

use PDO;

class LectionaryHolyDayList
{
    public $result  = null;
    private $db     = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param string prefaceType T|C Either Traditional or Contemporary
     */
    public function __construct($db)
    {
        $this->db = (isset($db)) ? $db : null;
        self::getHolyDayList();
    }
    /**
     * getHolyDays - Get the possibility of holy days
     */
    private function getHolyDayList()
    {
        $query = $this->db->dbh->prepare(
            "
SELECT distinct(hd_id), hd_date
  FROM holy_days_lectionary
 ORDER BY hd_date"
        );
        $query->execute();
        $this->result = $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
