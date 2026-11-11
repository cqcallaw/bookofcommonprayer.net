<?php
namespace BCP;

use PDO;

class LectionaryHolyDayReadings
{
    public $date    = null;
    public $result  = null;
    public $wl      = null;
    public $ot_type = null;
    public $nt_type = null;
    public $gospel_type = null;
    public $psalm_type = null;
    public $ot_list = null;
    public $nt_list = null;
    public $gospel_list = null;
    public $psalm_list = null;
    private $db     = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param object db Database Object
     * @param string date The Holy Day ID
     * @param string wl Which Lectionary to use, RCL, or 1979
     */
    public function __construct($db, $date, $wl)
    {
        $this->db = (isset($db)) ? $db : null;
        $this->date = (isset($date)) ? $date : null;
        $this->wl = (isset($wl)) ? $wl : null;
        self::getHolyDays();
    }
    /**
     * getHolyDays - Get the possibility of holy days
     */
    private function getHolyDays()
    {
        if ($this->wl == 'RCL') {
            $query = $this->db->dbh->prepare(
                "SELECT *
            FROM RCL_holy_days_lectionary
           WHERE hd_id = :holy_day_date"
            );
        } else {
            $query = $this->db->dbh->prepare(
                "SELECT *
            FROM holy_days_lectionary
           WHERE hd_id = :holy_day_date"
            );
        }
        $query->bindParam(":holy_day_date", $this->date);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        list($this->ot_list, $this->ot_type) =
          self::getReadingsArray($results[0]['hd_ot']);
        list($this->nt_list, $this->nt_type) =
          self::getReadingsArray($results[0]['hd_nt']);
        list($this->gospel_list, $this->gospel_type) =
          self::getReadingsArray($results[0]['hd_gospel']);
        list($this->psalm,$this->psalm_type) =
          self::getReadingsArray($results[0]['hd_psalm']);
        $this->hd_name = $results[0]['hd_name'];
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
}
