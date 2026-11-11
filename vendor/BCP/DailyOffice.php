<?php
namespace BCP;

/**
 * This is a class that returns a link to the Daily Office Page for
 * the current day.  The links are formatted in such a way that the readings
 * can be pulled from the database by the Daily Office Readings class
 *
 * @author C. Mike Seiler <cmikeseiler@gmail.com>
 * @version 1.0
 * @copyright 2014 C. Mike Seiler
 *
 * @param string $do_yearNo The "Daily Office Year"; either 1 or 2
 * @param string $do_id The id of the daily office, as it is in the database
 * @param string $winter_collect Whether there is the additional collect during portions of the winter.
 */
#[\AllowDynamicProperties]
class DailyOffice
{
    public $do_yearNo       = null;
    public $do_id           = null;
    public $do_id_name      = null;
    public $do_dow          = null;
    public $winter_collect  = null;

    public function __construct(ChurchCalendar $ChurchCalendar)
    {
        self::findDailyOffice($ChurchCalendar);
    }

    private function findDailyOffice($cc)
    {
        $mod = $cc->year%2; // Year 1 is a year in which no mod remains
        if ($mod == 0) {
            $this->do_yearNo = "2"; // even numbered years
        } elseif ($mod == 1) {
            $this->do_yearNo = "1"; // odd numbered years
        }

        // Key is the readable name of the sunday, e.g. Sunday, December-21-2014
        // Value is the ID we track it with in the Database, e.g. 4A, CM, 2C, E
        foreach ($cc->sundays as $key => $value) {
            // bug: cc->current_sunday turns into monday...
            if ($cc->current_sunday == $key) {
                // Set the Winter Collect value to the current week's Sunday ID
                $wc = $value;
                // Now, return the correct daily id based on the current week's Sunday ID and Day of Week
                if ($value == "CM" || $value == "HN" || $value == "4A" || $value == "1C"
                || $value == "2C" || $value == "E") {
                    // all sundays or possible sundays
                    if ($value == "4A") {
                        $this->do_id = self::fourAdvent($cc->four_advent, $cc->today_dow);
                        // basically - if we get a result, then for DB purposes
                        // the day of week needs to be null
                        if (isset($this->do_id)) {
                            $this->do_dow = "null";
                        }
                    }
                    if ($value == "CM") {
                        // CM IS FINE AS WE THEN KNOW THE DATE IS A DOW=0 AND DAY=25
                        $this->do_id_name = "Christmas Day";
                        $this->do_id = self::christMass($cc->today_dow);
                        if (isset($this->do_id)) {
                            $this->do_dow = "null";
                        }
                    }
                    if ($value == "HN") {
                        // HN IS FINE AS WE THEN KNOW THE DATE IS A DOW=0 AND DAY=01
                        $this->do_id_name = "Holy Name";
                        $this->do_id = self::holyName($cc->today_dow);
                        // This will always be either null or a specific id that is movable
                        // so the DB has a null do_dow
                        if (isset($this->do_id) || is_null($this->do_id)) {
                            $this->do_dow = "null";
                        }
                    }
                    if ($value == "1C") {
                        $this->do_id_name = "First Sunday After Christmas";
                        $this->do_id = self::firstSunday(
                            $cc->today_dow,
                            $cc->cs_1_day,
                            $cc->cs_1_year,
                            $cc->hn_year
                        );//etc.
                        // This will always be either null or a specific id that is movable
                        // so the DB has a null do_dow
                        if (isset($this->do_id) || is_null($this->do_id)) {
                            $this->do_dow = "null";
                        }
                    }
                    if ($value == "2C") {
                        $this->do_id_name = "Second Sunday After Christmas";
                        $this->do_id = self::secondSunday($cc->today_dow, $cc->cs_2_day);//etc.
                        // This will always be either null or a specific id that is movable
                        // so the DB has a null do_dow
                        if (isset($this->do_id) || is_null($this->do_id)) {
                            $this->do_dow = "null";
                        }
                    }
                    if ($value == "E") {
                        // Epiphany sunday only, then we have to do additional exact days until 1E
                        // the do_id_name refers to "week of..."
                        $this->do_id_name = "Epiphany";
                        $this->do_id = self::weekEpiphany($cc->today_dow);
                        if ($this->do_id == "") {
                            // that is, it's a sunday and returns null
                            $this->do_id = $value;
                        }
                    }
                }
                if (isset($this->do_id)) {
                    // HERE WE NEED TO SET THE $cc->today_dow TO null BECAUSE BY SETTING THE $this->do_id WE'VE
                    // BASICALLY SAID THAT WE'VE HAD TO FIND A SPECIFIC DAY THAT'S MOVABLE, ERGO, NO DOW
                    $this->do_dow = "null";
                } else {
                    $this->do_id = $value;
                    $this->do_dow = $cc->today_dow;
                }
                if (($this->do_id == "12-29") || ($this->do_id == "12-30")  || ($this->do_id == "12-31")
                || ($this->do_id == "1-2")  || ($this->do_id == "1-3")  || ($this->do_id == "1-4")
                || ($this->do_id == "1-5")  || ($this->do_id == "1-7")  || ($this->do_id == "1-8")
                || ($this->do_id == "1-9")  || ($this->do_id == "1-10")  || ($this->do_id == "1-11")
                || ($this->do_id == "1-12")) {
                    $this->winter_collect = $wc; // GRAB THE PREVIOUS SUNDAY'S DO_ID
                }
            }
        }
    }

    private function fourAdvent($four_advent, $today_dow)
    {
        // FOUR ADVENT WILL ALWAYS BE A SUNDAY, SO WE NEED FIGURE OUT WHAT
        // DAY OF WEEK IT IS, SO THAT WE CAN GIVE THE CORRECT READINGS
        $advent_possibilities = array(
            "18"=>array("0"=>null,"1"=>null,"2"=>null,"3"=>null,"4"=>null,"5"=>null,"6"=>"12-24"),
            "19"=>array("0"=>null,"1"=>null,"2"=>null,"3"=>null,"4"=>null,"5"=>"12-24","6"=>"CM"),
            "20"=>array("0"=>null,"1"=>null,"2"=>null,"3"=>null,"4"=>"12-24","5"=>"CM","6"=>"STST"),
            "21"=>array("0"=>null,"1"=>null,"2"=>null,"3"=>"12-24","4"=>"CM","5"=>"STST","6"=>"STJE"),
            "22"=>array("0"=>null,"1"=>null,"2"=>"12-24","3"=>"CM","4"=>"STST","5"=>"STJE","6"=>"HI"),
            "23"=>array("0"=>null,"1"=>"12-24","2"=>"CM","3"=>"STST","4"=>"STJE","5"=>"HI","6"=>"12-29"),
            "24"=>array("0"=>"12-24","1"=>"CM","2"=>"STST","3"=>"STJE","4"=>"HI","5"=>"12-29","6"=>"12-30"),
        );
        return $advent_possibilities[$four_advent][$today_dow];
    }
    ////////////
    // CHRISTMAS IS A SUNDAY
    ////////////
    private function christMass($today_dow)
    {
        $christmas_possibilities = array(
            "0"=>null,"1"=>"STST","2"=>"STJE","3"=>"HI","4"=>"12-29","5"=>"12-30","6"=>"12-31"
        );
        return $christmas_possibilities[$today_dow];
    }

    private function holyName($today_dow)
    {
        $hn_possibilities = array(
            "0"=>null,"1"=>"1-2","2"=>"1-3","3"=>"1-4","4"=>"1-5","5"=>"E","6"=>"1-7"
        );
        return $hn_possibilities[$today_dow];
    }

    private function firstSunday($today_dow, $cs_1_day, $cs_1_year, $hn_year)
    {
        if ($cs_1_year < $hn_year) {
            $fsPossibilities = array(
                "26"=>array("0"=>null,"1"=>"STJE","2"=>"HI","3"=>"12-29","4"=>"12-30","5"=>"12-31","6"=>"HN"),
                "27"=>array("0"=>null,"1"=>"HI","2"=>"12-29","3"=>"12-30","4"=>"12-31","5"=>"HN","6"=>"1-2"),
                "28"=>array("0"=>null,"1"=>"12-29","2"=>"12-30","3"=>"12-31","4"=>"HN","5"=>"1-2","6"=>"1-3"),
                "29"=>array("0"=>null,"1"=>"12-30","2"=>"12-31","3"=>"HN","4"=>"1-2","5"=>"1-3","6"=>"1-4"),
                "30"=>array("0"=>null,"1"=>"12-31","2"=>"HN","3"=>"1-2","4"=>"1-3","5"=>"1-4","6"=>"1-5"),
                "31"=>array("0"=>null,"1"=>"HN","2"=>"1-2","3"=>"1-3","4"=>"1-4","5"=>"1-5","6"=>"E")
            );
            return $fsPossibilities[$cs_1_day][$today_dow];
        } else {
            return null;
        }
    }

    private function secondSunday($today_dow, $cs_2_day)
    {
        if ($cs_2_day < 6) {
            $ss_possibilities = array(
                "2"=>array("0"=>null,"1"=>"1-3","2"=>"1-4","3"=>"1-5","4"=>"E","5"=>"1-7","6"=>"1-8"),
                "3"=>array("0"=>null,"1"=>"1-4","2"=>"1-5","3"=>"E","4"=>"1-7","5"=>"1-8","6"=>"1-9"),
                "4"=>array("0"=>null,"1"=>"1-5","2"=>"E","3"=>"1-7","4"=>"1-8","5"=>"1-9","6"=>"1-10"),
                "5"=>array("0"=>null,"1"=>"E","2"=>"1-7","3"=>"1-8","4"=>"1-9","5"=>"1-10","6"=>"1-11")
            );
            return $ss_possibilities[$cs_2_day][$today_dow];
        } else {
            return null;
        }
    }
    // EPIPHANY DATES ARE FIGURED ABOVE NEXT TO CHRISTMAS SUNDAYS
    // AS EPIPHANY IS NECESSARY IN FIGURING WHETHER 2 CHRISTMAS SUNDAYS ARE NEEDED
    private function weekEpiphany($today_dow)
    {
        $week_ids = array(
          "0"=>null,"1"=>"1-7","2"=>"1-8","3"=>"1-9","4"=>"1-10","5"=>"1-11","6"=>"1-12"
        );
        $this->do_id = $week_ids[$today_dow];
        return $this->do_id;
    }
}
