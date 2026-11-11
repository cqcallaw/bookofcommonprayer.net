<?php
namespace BCP;

use PDO;

class DailyOfficePsalms
{
    public $day     = null;
    public $time    = null;
    public $chapter = null;
    public $result  = null;
    private $db     = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param string prefaceType T|C Either Traditional or Contemporary
     */
    public function __construct($db, $data, $type)
    {
        $this->db = (isset($db)) ? $db : null;
        $this->day = (isset($data->day)) ? $data->day : null;
        $this->time = (isset($data->time)) ? $data->time : null;
        $this->chapter = (isset($data->chapter)) ? $data->chapter : null;
        if ($type == 'daytime') {
            self::getPsalmsByDaytime();
        } elseif ($type == 'chapter') {
            self::getPsalmByNumber();
        }
        return $this->result;
    }
    /**
     * getPsalmsByDaytime - Get the List of Psalms by daynumber
     */
    private function getPsalmsByDaytime()
    {
        $query = $this->db->dbh->prepare(
            "SELECT distinct(chapter)
           FROM bcp_psalms
          WHERE daynumber = :day
            AND daytime = :daytime
          ORDER BY chapter ASC"
        );
        $query->bindParam(":day", $this->day);
        $query->bindParam(":daytime", $this->time);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        //
        $result1 = array();
        foreach ($result as $key => $r) {
            $query1 = $this->db->dbh->prepare(
                "SELECT *
              FROM bcp_psalms
             WHERE chapter = :chapter
               AND daynumber = :day
               AND daytime = :daytime
             ORDER BY chapter,part,verse ASC"
            );
            $query1->bindParam(":chapter", $r['chapter']);
            $query1->bindParam(":day", $this->day);
            $query1->bindParam(":daytime", $this->time);
            $query1->execute();
            $result1[$r['chapter']] = $query1->fetchAll(PDO::FETCH_ASSOC);
        }
        $result1['daytime'] = ($this->time == 'A') ? 'Morning' : 'Evening';
        $result1['day'] = ($this->day) ? $this->day : null;
        $this->result = $result1;
    }

  /**
     * getPsalmByNumber - Returns the psalms selected
     */
    private function getPsalmByNumber()
    {
        $query = $this->db->dbh->prepare(
            "SELECT *
         FROM bcp_psalms
        WHERE chapter = :chapter
        ORDER BY part,verse ASC;"
        );
        $query->bindParam(":chapter", $this->chapter);
        $query->execute();
        $result[$this->chapter] = $query->fetchAll(PDO::FETCH_ASSOC);
        $this->result = $result;
    }
}
