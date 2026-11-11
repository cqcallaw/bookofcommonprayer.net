<?php
namespace BCP;

use Exception;
use PDO;

class CanticlesByPrayerType
{
    public $ptype   = null;
    public $dow     = null;
    public $rite    = null;
    public $season  = null;
    public $language= null;
    public $c_list  = null;
    private $db     = null;

    /**
     * constructor - generate the construction by calling the self-contained methods
     *
     * @param string $collectType T|C Either Traditional or Contemporary
     * @param string $pType The type of prayer we are engaged in, e.g. Morning or
     * @param string $rite The rite - either rite_i or rite_ii
     * @param string $dow Day of week 0-6
     * @param string $season Calendar season
     * Evening, etc.
     *
     * @return
     */
    public function __construct($db, $ptype, $rite, $dow, $season, $language)
    {
        $this->db = $db;
        $this->ptype = $ptype;
        $this->rite = $rite;
        $this->dow = $dow;
        $this->season = $season;
        $this->language = $language;
        if ($this->language == 'ES') {
            self::getCanticleIdsByPrayerTypeES();
        } else {
            self::getCanticleIdsByPrayerType();
        }
    }
    /**
     * getCanticleIdsByPrayerType - Retrieve a list of  Canticles based
     * on the day of week and the type of prayer.  The table of canticles should
     * not change, given that it's remained the same for decades, so unless the
     * BCP changes, we are going to use the hard-coded values here. See pg 145.
     *
     * @param string $ptype Which prayer the viewer currently uses; morn or eve
     * @param string $dow Which day of week it is, so we can select a random
     * selection of canticles for the canticle list.
     */
    public function getCanticleIdsByPrayerType()
    {
        if ($this->rite == 'rite_i') {
            // get the season
            if (($this->season == "Lent") || ($this->season == "Holy_Week")) {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"14","1"=>"9","2"=>"2","3"=>"14","4"=>"8","5"=>"14","6"=>"1"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"14","2"=>"10","3"=>"1","4"=>"11","5"=>"2","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"4","1"=>"19","2"=>"18","3"=>"4","4"=>"19","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            } elseif ($this->season == "Advent") {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"11","1"=>"9","2"=>"2","3"=>"11","4"=>"8","5"=>"14","6"=>"1"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"14","2"=>"10","3"=>"1","4"=>"11","5"=>"2","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"4","1"=>"19","2"=>"18","3"=>"4","4"=>"19","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            } elseif (($this->season == "Easter") || ($this->season == "Easter_Week")) {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"8","1"=>"9","2"=>"2","3"=>"11","4"=>"8","5"=>"10","6"=>"1"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"8","2"=>"10","3"=>"1","4"=>"11","5"=>"2","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"7","1"=>"19","2"=>"18","3"=>"4","4"=>"6","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            } else {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"4","1"=>"9","2"=>"2","3"=>"11","4"=>"8","5"=>"10","6"=>"1"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"8","2"=>"10","3"=>"1","4"=>"11","5"=>"2","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"7","1"=>"19","2"=>"18","3"=>"4","4"=>"6","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            }
        } elseif ($this->rite == 'rite_ii') {
            // get the season
            if (($this->season == "Lent") || ($this->season == "Holy_Week")) {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"14","1"=>"9","2"=>"13","3"=>"14","4"=>"8","5"=>"14","6"=>"12"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"14","2"=>"10","3"=>"12","4"=>"11","5"=>"13","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"16","1"=>"19","2"=>"18","3"=>"16","4"=>"19","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            } elseif ($this->season == "Advent") {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"11","1"=>"9","2"=>"13","3"=>"11","4"=>"8","5"=>"10","6"=>"12"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"8","2"=>"10","3"=>"12","4"=>"11","5"=>"13","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"21","1"=>"19","2"=>"18","3"=>"16","4"=>"20","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            } elseif (($this->season == "Easter") || ($this->season == "Easter_Week")) {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"8","1"=>"9","2"=>"13","3"=>"11","4"=>"8","5"=>"10","6"=>"12"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"8","2"=>"10","3"=>"12","4"=>"11","5"=>"13","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"21","1"=>"19","2"=>"18","3"=>"16","4"=>"20","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            } else {
                $list_of_canticles = array(
                    "first" => array(
                        "morning_prayer" => array(
                            "0"=>"16","1"=>"9","2"=>"13","3"=>"11","4"=>"8","5"=>"10","6"=>"12"
                        ),
                        "evening_prayer" => array(
                            "0"=>"3","1"=>"8","2"=>"10","3"=>"12","4"=>"11","5"=>"13","6"=>"9"
                        )
                    ),
                    "second"=> array(
                        "morning_prayer" => array(
                            "0"=>"21","1"=>"19","2"=>"18","3"=>"16","4"=>"20","5"=>"18","6"=>"19"
                        ),
                        "evening_prayer" => array(
                            "0"=>"5","1"=>"5","2"=>"3","3"=>"5","4"=>"3","5"=>"5","6"=>"3"
                        )
                    )
                );
            }
        }
        $this->c_list["first"] = self::getCanticleById($list_of_canticles["first"][$this->ptype][$this->dow]);
        $this->c_list["second"] = self::getCanticleById($list_of_canticles["second"][$this->ptype][$this->dow]);
    }

    /**
     * getCanticleIdsByPrayerType - Retrieve a list of  Canticles based
     * on the day of week and the type of prayer.  The table of canticles should
     * not change, given that it's remained the same for decades, so unless the
     * BCP changes, we are going to use the hard-coded values here. See pg 145.
     *
     * @param string $ptype Which prayer the viewer currently uses; morn or eve
     * @param string $dow Which day of week it is, so we can select a random
     * selection of canticles for the canticle list.
     */
    public function getCanticleIdsByPrayerTypeES()
    {
        // get the season
        if (($this->season == "Lent") || ($this->season == "Holy_Week")) {
            $list_of_canticles = array(
                "first" => array(
                    "morning_prayer" => array(
                        "0"=>"7","1"=>"2","2"=>"6","3"=>"7","4"=>"1","5"=>"7","6"=>"5"
                    ),
                    "evening_prayer" => array(
                        "0"=>"8","1"=>"7","2"=>"3","3"=>"5","4"=>"4","5"=>"6","6"=>"2"
                    )
                ),
                "second"=> array(
                    "morning_prayer" => array(
                        "0"=>"9","1"=>"12","2"=>"11","3"=>"9","4"=>"12","5"=>"11","6"=>"12"
                    ),
                    "evening_prayer" => array(
                        "0"=>"10","1"=>"10","2"=>"8","3"=>"10","4"=>"8","5"=>"10","6"=>"8"
                    )
                )
            );
        } elseif ($this->season == "Advent") {
            $list_of_canticles = array(
                "first" => array(
                    "morning_prayer" => array(
                        "0"=>"4","1"=>"2","2"=>"6","3"=>"4","4"=>"1","5"=>"3","6"=>"5"
                    ),
                    "evening_prayer" => array(
                        "0"=>"8","1"=>"1","2"=>"3","3"=>"5","4"=>"4","5"=>"6","6"=>"2"
                    )
                ),
                "second"=> array(
                    "morning_prayer" => array(
                        "0"=>"9","1"=>"12","2"=>"11","3"=>"9","4"=>"12","5"=>"11","6"=>"12"
                    ),
                    "evening_prayer" => array(
                        "0"=>"10","1"=>"10","2"=>"8","3"=>"10","4"=>"8","5"=>"10","6"=>"8"
                    )
                )
            );
        } elseif (($this->season == "Easter") || ($this->season == "Easter_Week")) {
            $list_of_canticles = array(
                "first" => array(
                    "morning_prayer" => array(
                        "0"=>"1","1"=>"2","2"=>"6","3"=>"4","4"=>"1","5"=>"3","6"=>"5"
                    ),
                    "evening_prayer" => array(
                        "0"=>"8","1"=>"1","2"=>"3","3"=>"5","4"=>"4","5"=>"6","6"=>"2"
                    )
                ),
                "second"=> array(
                    "morning_prayer" => array(
                        "0"=>"14","1"=>"12","2"=>"11","3"=>"9","4"=>"13","5"=>"11","6"=>"12"
                    ),
                    "evening_prayer" => array(
                        "0"=>"10","1"=>"10","2"=>"8","3"=>"10","4"=>"8","5"=>"10","6"=>"8"
                    )
                )
            );
        } else {
            $list_of_canticles = array(
                "first" => array(
                    "morning_prayer" => array(
                        "0"=>"9","1"=>"2","2"=>"6","3"=>"4","4"=>"1","5"=>"3","6"=>"5"
                    ),
                    "evening_prayer" => array(
                        "0"=>"8","1"=>"1","2"=>"3","3"=>"5","4"=>"4","5"=>"6","6"=>"2"
                    )
                ),
                "second"=> array(
                    "morning_prayer" => array(
                        "0"=>"14","1"=>"12","2"=>"11","3"=>"9","4"=>"13","5"=>"11","6"=>"12"
                    ),
                    "evening_prayer" => array(
                        "0"=>"10","1"=>"10","2"=>"8","3"=>"10","4"=>"8","5"=>"10","6"=>"8"
                    )
                )
            );
        }
        $this->c_list["first"] = self::getCanticleById($list_of_canticles["first"][$this->ptype][$this->dow]);
        $this->c_list["second"] = self::getCanticleById($list_of_canticles["second"][$this->ptype][$this->dow]);
    }
    /**
     * getCanticleById - retrieves the Canticle by id passed
     *
     * @param int $cant_id Canticle ID passed via API controller
     * @return mixed $result Canticle data array
     */
    public function getCanticleById($cant_id)
    {
        if ($this->language == 'ES') {
            $query = "
        SELECT *
          FROM canticles_es
         WHERE cant_id = :cant_id";
        } else {
            $query = "
        SELECT *
          FROM canticles
         WHERE cant_id = :cant_id";
        }
         $s = $this->db->dbh->prepare($query);
         $s->bindParam(":cant_id", $cant_id);
         $s->execute();
         $result = $s->fetch(PDO::FETCH_ASSOC);
         return $result;
    }
}
