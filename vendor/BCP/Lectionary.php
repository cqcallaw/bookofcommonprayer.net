<?php
namespace BCP;

/**
 * This is a class that returns a link to the lectionary readings page
 * of all sundays in a given year.  With appropriate flags, it can also
 * return a link to be used in an email, or can return a single link.
 *
 * @author C. Mike Seiler <cmikeseiler@gmail.com>
 * @version 1.0
 * @copyright 2014 C. Mike Seiler
 *
 * @param string $lectionary_year The textual "year" from the BCP; e.g. 'A','B','C' in the cycle; calculated
 * @param int $CL Simple flag of 1 or 0; Contemporary Lectionary prayers
 * @param int $RCL Simple flag of 1 or 0; Use Revised Common Lectionary
 */
#[\AllowDynamicProperties]
class Lectionary extends ChurchCalendar
{
    public $lectionary_year;
    public $CL;
    public $RCL;
    public $sundayById;

    public function __construct(\BCP\ChurchCalendar $ChurchCalendar, $CL = null, $RCL = null)
    {
        // Assign parent attributes to child - makes for less typing (i.e. $this->ChurchCalendar->year... etc)
        foreach ($ChurchCalendar as $key => $value) {
            $this->$key = $value;
        }
        $this->CL = $CL;
        $this->RCL = $RCL;
        self::findLectionaryIds();
    }

    public function findLectionaryIds()
    {
        $mod = $this->advent_year%3; // Year A is a year in which no mod remains
        if ($mod == 0) {
            $this->lectionary_year = "A";
        } elseif ($mod == 1) {
            $this->lectionary_year = "B";
        } elseif ($mod == 2) {
            $this->lectionary_year = "C";
        }
        // Now Loop through our Sundays from the Church Calendar, and add additional details.
        foreach ($this->sundays as $sunday => $reading_id) {
            // Prepare suffixes for Sundays in an order of something
            $name_array = array("1"=>"First","2"=>"Second","3"=>"Third","4"=>"Fourth",
            "5"=>"Fifth","6"=>"Sixth","7"=>"Seventh","8"=>"Eighth","9"=>"Ninth","L"=>"Last");
            $which_sunday = substr($reading_id, 0, 1);
            // ALL SUNDAYS HAVE THE FOLLOWING IN COMMON
            $this->sundayById[$reading_id] = array("lectionary_year"=>"$this->lectionary_year","id"=>"$reading_id",
                "current_sunday"=>"$sunday"
            );
            // ADVENT "blue"
            if ($reading_id == "1A" || $reading_id == "2A" || $reading_id == "4A") {
                $this->sundayById[$reading_id]["color"] = "blue";
                if ($reading_id == "1A") {
                    $this->sundayById[$reading_id]["name"] = $name_array[$which_sunday]." Sunday in Advent";
                }
                if ($reading_id == "2A") {
                    $this->sundayById[$reading_id]["name"] = $name_array[$which_sunday]." Sunday in Advent";
                }
                if ($reading_id == "4A") {
                    $this->sundayById[$reading_id]["name"] = $name_array[$which_sunday]." Sunday in Advent";
                }
            }
            // ADVENT pink
            if ($reading_id == "3A") {
                $this->sundayById[$reading_id]["color"] = "pink";
                if ($reading_id == "3A") {
                    $this->sundayById[$reading_id]["name"] = $name_array[$which_sunday]." Sunday in Advent";
                }
            }
            // CHRISTMAS and options
            if ($reading_id == "CM") {
                $this->sundayById[$reading_id]["color"] = "white";
                $this->sundayById[$reading_id]["name"] = "Christmas Day";
                $this->sundayById[$reading_id]["additional"] = array("CM","CMII","CMII");
            }
            // SUNDAYS after Christmas
            if ($reading_id == "1C") {
                $this->sundayById[$reading_id]["color"] = "white";
                $this->sundayById[$reading_id]["name"] = "First Sunday after Christmas";
            }
            if ($reading_id == "2C") {
                $this->sundayById[$reading_id]["color"] = "white";
                $this->sundayById[$reading_id]["name"] = "Second Sunday after Christmas";
            }
            // HOLY Name
            if ($reading_id == "HN") {
                $this->sundayById[$reading_id]["color"] = "white";
                $this->sundayById[$reading_id]["name"] = "The Holy Name of Jesus";
            }
            // EPIPHANY
            if ($reading_id == "E") {
                $this->sundayById[$reading_id]["color"] = "white";
                $this->sundayById[$reading_id]["name"] = "The Epiphany Of Christ";
            }
            if (($reading_id == "1E") || ($reading_id == "2E") || ($reading_id == "3E")
            || ($reading_id == "4E") || ($reading_id == "5E") || ($reading_id == "6E")
            || ($reading_id == "7E") || ($reading_id == "8E") || ($reading_id == "LE")) {
                $this->sundayById[$reading_id]["color"] = "green";
                $this->sundayById[$reading_id]["name"] = $name_array[$which_sunday]." Sunday after the Epiphany";
            }
            // LENT
            if (($reading_id == "AW") || ($reading_id == "1L") || ($reading_id == "2L")
            || ($reading_id == "3L") || ($reading_id == "4L") || ($reading_id == "5L")) {
                $this->sundayById[$reading_id]["color"] = "purple";
                if (substr($reading_id, 0, 1) == "A") {
                    $this->sundayById[$reading_id]["name"] = "Ash Wednesday";
                } else {
                    $this->sundayById[$reading_id]["name"] = $name_array[$which_sunday]." Sunday in Lent";
                }
            }
            // PASSION WEEK
            if ($reading_id == "PS") {
                $this->sundayById[$reading_id]["color"] = "passion";
                $this->sundayById[$reading_id]["name"] = "Palm Sunday";
                $this->sundayById[$reading_id]["additional"] = self::doHolyWeek($this->lectionary_year, $sunday);
            }
            // EASTER
            if ($reading_id == "R") {
                $this->sundayById[$reading_id]["color"] = "white";
                $this->sundayById[$reading_id]["name"] = "The Resurrection of Christ";
                $this->sundayById[$reading_id]["additional"] = self::doEasterWeek($this->lectionary_year, $sunday);
            }
            if (($reading_id == "1R") || ($reading_id == "2R") || ($reading_id == "3R")
            || ($reading_id == "4R") || ($reading_id == "5R") || ($reading_id == "6R")
            || ($reading_id == "7R") || ($reading_id == "AD")) {
                    $this->sundayById[$reading_id]["color"] = "white";
                if (substr($reading_id, 0, 1) == "A") {
                    $this->sundayById[$reading_id]["name"] = "The Ascension of Christ";
                } else {
                    $this->sundayById[$reading_id]["name"] = $name_array[$which_sunday]." Sunday in Easter";
                }
            }
            // PENTECOST
            if ($reading_id == "P") {
                $this->sundayById[$reading_id]["color"] = "red";
                $this->sundayById[$reading_id]["name"] = "Day of Pentecost";
            }
            // TRINITY
            if ($reading_id == "T") {
                $this->sundayById[$reading_id]["color"] = "white";
                $this->sundayById[$reading_id]["name"] = "Trinity Sunday";
            }
            // NORMAL TIME
            if (is_numeric($reading_id)) {
                if ($reading_id == "29") {
                    // CHRIST THE KING (Proper 29)
                    $this->sundayById[$reading_id]["color"] = "green";
                    $this->sundayById[$reading_id]["name"] = "Feast of Christ the King";
                } else {
                    $this->sundayById[$reading_id]["color"] = "green";
                    $this->sundayById[$reading_id]["name"] = "Sunday in Ordinary Time (Proper $reading_id)";
                }
            }
        }
    }
    /*
     * doHolyWeek - spits out additional readings for each weekday of Holy Week.
     * @param string $lectionary_year The year in format A,B,C
     * @param string $sunday The current Sunday formatted in l, f-d-y as sent through the loop
     *
     * @return array $week_return, an array of each day's readings
     */
    public function doHolyWeek($lectionary_year, $sunday)
    {
        $days = array("1" => "Monday","2" => "Tuesday","3" => "Wednesday","4" => "Thursday",
            "5" => "Friday","6" => "Saturday");
        for ($i = 1; $i <= 6; $i++) {
            $ps_dow = $days[$i];
            $week_return_array = array("lectionary_year"=>"$this->lectionary_year","id"=>"PS-$i",
            "color"=>"passion","current_sunday"=>"$sunday","name"=>"Holy Week: $ps_dow");
            $week_return[$ps_dow] = $week_return_array;
        }
        return $week_return;
    }

    /*
     * doEasterWeek - spits out additional readings for each weekday of Easter Week.
     * @param string $lectionary_year The year in format A,B,C
     * @param string $sunday The current Sunday formatted in l, f-d-y as sent through the loop
     *
     * @return array $week_return, an array of each day's readings
     */
    public function doEasterWeek($lectionary_year, $sunday)
    {
        $days = array("1" => "Monday","2" => "Tuesday","3" => "Wednesday","4" => "Thursday",
           "5" => "Friday","6" => "Saturday");
        for ($i = 1; $i <= 6; $i++) {
               $r_dow = $days[$i];
               $week_return_array = array("lectionary_year"=>"$this->lectionary_year",
               "id"=>"R-$i",
               "color"=>"white",
               "current_sunday"=>"$sunday",
               "name"=>"Easter Week: $r_dow"
                );
                $week_return[$r_dow] = $week_return_array;
        }
        return $week_return;
    }
// end of class
}
