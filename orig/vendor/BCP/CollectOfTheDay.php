<?php
namespace BCP;

use Exception;
use PDO;

class CollectOfTheDay
{
    private $db         = null;
    public $which_rite  = null;
    public $do_id       = null;
    public $wc          = null;
    public $language    = null;

    public function __construct($db, $which_rite, $id, $dow, $language, $wc = null)
    {
        $this->db = isset($db) ? $db : null;
        if (!isset($this->db)) {
            throw new Exception("Database Object not set in Daily Office CollectOfTheDay Class.");
        }
        $this->which_rite = $which_rite;
        $this->do_id = $id;
        $this->do_dow = $dow;
        $this->language = $language;
        $this->wc = isset($wc) ? $wc : null;
        $this->cod = self::getCOD();
    }

    public function getCOD()
    {
        if (($this->do_id == "R") || ($this->do_id == "PS")) {
            if ($this->do_dow > 0) {
                $this->do_id = $this->do_id."-".$this->do_dow;
            }
        } elseif (($this->do_id == "LE") && ($this->do_dow >= 3)) {
            $this->do_id = "AW";
        } elseif (($this->do_id == 'STST')) {
            $this->do_id = '12-26';
        } elseif (($this->do_id == 'STJE')) {
            $this->do_id = '12-27';
        } elseif (($this->do_id == 'HI')) {
            $this->do_id = '12-28';
        } elseif (($this->do_id == '1-2') || ($this->do_id == '1-3')
            || ($this->do_id == '1-4') || ($this->do_id == '1-5')) {
            // Need to figure out Christmas dow here
            $christmas_dow = date('w', mktime(0, 0, 0, 12, 25, date("Y")));
            if ( ($christmas_dow == 0) || ($christmas_dow == 1) || ($christmas_dow == 2)
                || ($christmas_dow == 3) || ($christmas_dow == 4) || ($christmas_dow == 5)
            ) {
                $this->do_id = '1C';
            } else {
                $this->do_id = '2C';
            }
        } elseif ((($this->do_id == '1-7') || ($this->do_id == '1-8')
            || ($this->do_id == '1-9') || ($this->do_id == '1-10')
            || ($this->do_id == '1-11') || ($this->do_id == '1-12')))
        {
            $this->do_id = 'E';
        } elseif (isset($wc)) {
            $this->do_id = $wc;
        } else {
            $this->do_id = $this->do_id;
        }
        if ($this->language == 'EN') {
            if ($this->which_rite == "rite_i") {
                $query = "
              SELECT *
                FROM collects
               WHERE id = :id";
            } elseif ($this->which_rite == "rite_ii") {
                $query = "
              SELECT *
                FROM collects_contemporary
               WHERE id = :id";
            } else {
                $query = "
              SELECT *
                FROM collects
               WHERE id = :id";
            }
        } else {
            $query = "
              SELECT *
                FROM collects_es
               WHERE id = :id";
        }
        $s = $this->db->dbh->prepare($query);
        $s->bindParam(":id", $this->do_id);
        $s->execute();
        $results = $s->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
}
