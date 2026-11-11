<?php
namespace BCP;

use Exception;
use PDO;

#[\AllowDynamicProperties]
class Preface
{
    private $db = null;
    private $wp = null;
    public $id  = null;
    public $language = null;

    public function __construct($db, $id, $wp, $language)
    {
        $this->db = isset($db) ? $db : null;
        if (!isset($this->db)) {
            throw new Exception("Database Object not set in Lectionary
      Preface Class.");
        }
        $this->id = $id;
        $this->wp = isset($wp) ? $wp : null;
        $this->language = $language;
        $this->p = self::getPreface();
    }

    private function getPreface()
    {
        $query = "
        SELECT trim(TRAILING '\n' FROM pref_id) AS pref_id
            FROM collects
          WHERE id = :id";
        $s = $this->db->dbh->prepare($query);
        $s->bindParam(":id", $this->id);
        $s->execute();
        $results = $s->fetch(PDO::FETCH_ASSOC);
        $pref_id = implode("', '", explode(",", $results['pref_id']));
        $pref_ids = "'".$pref_id."'";

        try {
            if ($this->language == 'EN') {
                if ($this->wp == 'rite_ii') {
                    $query_p = "
            SELECT *
              FROM prefaces_contemporary
             WHERE pref_id IN ($pref_ids)
            ";
                } else {
                    $query_p = "
            SELECT *
              FROM prefaces
             WHERE pref_id IN ($pref_ids)
            ";
                }
            } else {
                if ($this->wp == 'rite_ii') {
                    $query_p = "
            SELECT *
              FROM prefaces_contemporary_es
             WHERE pref_id IN ($pref_ids)
            ";
                } else {
                    $query_p = "
            SELECT *
              FROM prefaces_es
             WHERE pref_id IN ($pref_ids)
            ";
                }
            }

            $sp = $this->db->dbh->prepare($query_p);
            $sp->execute();
            $results_p = $sp->fetchAll(PDO::FETCH_ASSOC);
            return $results_p;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
