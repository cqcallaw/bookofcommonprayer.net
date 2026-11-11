<?php
namespace BCP;

use PDO;

class DailyOfficeHolyDayList
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
SELECT distinct(hd_do_id), hd_do_date
  FROM holy_days_daily_office
 ORDER BY hd_do_date, field(hd_do_date,'M','E')"
        );
        $query->execute();
        $this->result = $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
