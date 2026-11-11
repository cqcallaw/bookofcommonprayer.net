<?php
namespace BCP;

use PDO;

#[\AllowDynamicProperties]
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
        list($this->ot_list, $this->ot_type, $this->ot_joins) =
          self::getReadingsArray($results[0]['hd_ot']);
        list($this->nt_list, $this->nt_type, $this->nt_joins) =
          self::getReadingsArray($results[0]['hd_nt']);
        list($this->gospel_list, $this->gospel_type, $this->gospel_joins) =
          self::getReadingsArray($results[0]['hd_gospel']);
        list($this->psalm, $this->psalm_type, $this->psalm_joins) =
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
        // Alternatives are split before "&&". A few Easter Vigil rows are
        // stored as "orGenesis" with no space. "8:6-18" continues the book
        // from the previous piece of the same alternative.
        $reference = trim((string) $reference);
        $alternatives = preg_split('/\s+[Oo][Rr](?:\s+|(?=[A-Z]))|(?<=\S)[Oo][Rr](?=[A-Z])/', $reference);
        $readings = array();
        $joins = array();
        $altCount = count($alternatives);
        foreach ($alternatives as $altIndex => $alternative) {
            $parts = explode("&&", $alternative);
            $book = null;
            $kept = array();
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part === "") {
                    continue;
                }
                $bare = ltrim($part, " \t[(");
                if (preg_match('/^\d+:/', $bare)) {
                    if ($book) {
                        $part = $book." ".$bare;
                    }
                } elseif (preg_match('/^(\d\s*[A-Za-z]+(?:\s+[A-Za-z]+)*)/', $bare, $match)
                    || preg_match('/^([A-Za-z]+(?:\s+[A-Za-z]+)*)/', $bare, $match)) {
                    $book = $match[1];
                }
                $kept[] = $part;
            }
            $keptCount = count($kept);
            foreach ($kept as $partIndex => $part) {
                $readings[] = $part;
                if ($partIndex < $keptCount - 1) {
                    $joins[] = "AND";
                } elseif ($altIndex < $altCount - 1) {
                    $joins[] = "OR";
                } else {
                    $joins[] = null;
                }
            }
        }
        if (count($readings) === 0) {
            $readings[] = $reference;
            $joins[] = null;
        }
        $used = array_values(array_filter($joins));
        if (count($readings) <= 1) {
            $reading_type = "NORMAL";
        } elseif (count(array_unique($used)) === 1) {
            $reading_type = $used[0];
        } else {
            $reading_type = "MIXED";
        }
        return array($readings, $reading_type, $joins);
    }
}
