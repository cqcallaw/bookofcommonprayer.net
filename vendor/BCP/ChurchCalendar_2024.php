<?php
namespace BCP;

/**
 * This is a class that returns a list of Sundays in the Church calendar
 * This is all we need, and can be manipulated by the Daily Office and
 * Lectionary classes to further specificity for their needs.
 *
 * @author C. Mike Seiler <cmikeseiler@gmail.com>
 * @version 1.0
 * @copyright 2014 C. Mike Seiler
 *
 * @param string $year The Current Year
 * @param string $do_yearNo The "Daily Office Year"; either 1 or 2
 * @param string $sunday The current sunday; e.g. the Sunday that started this week
 * @param string $today The current date.
 * @param string $today_dow The day of the week today.
 * @param string $winter_collect Whether there is the additional collect during portions of the winter.
 * @param array $sundays An array of Sunday dates that match particular sundays (e.g. 1 Lent, Pentecost, Easter)
 */
class ChurchCalendar
{
    public $year                    = null;
    public $lectionary_year         = null;
    public $advent_year             = null;
    public $next_year               = null;
    public $today                   = null;
    public $today_dow               = null;
    public $today_zday              = null;
    public $timezone_offset         = null;
    protected $time_adjust          = null;
    public $sundays                 = array();
    public $current_sunday          = null;
    public $current_season          = null;
    public $upcoming_sunday         = null;
    public $christmas_not_sunday    = null;
    public $holy_name               = null;
    public $epiphany_day            = null;
    public $ash_wed                 = null;
    public $ascension_day           = null;
    public $pentecost               = null;
    public $trinity_sun             = null;
    public $cs_1_day                = null;
    public $cs_1_year               = null;
    public $cs_2_day                = null;
    public $cs_2_dow                = null;
    public $hn_year                 = null;
    public $one_advent_next_zday    = null;
    public $monday_before_one_advent_zday = null;
    public $one_advent_zday         = null;
    public $pentecost_proper        = null;
    public $trinity_proper          = null;

    public function __construct($isLectionary, $year, $timezone_offset = null, $zday = null)
    {
    // ADJUST THE TIME AND DATE ACCORDING TO THE TIMEZONE OFFSET
    // WE NEED THIS FOR ALL DATE RELATED CALCULATIONS
    // time() IS CURRENT TIMESTAMP
    // OFFSET CALCULATES AN INTEGER BY AN HOUR (IN SECONDS)
    // AND SUBTRACTS IT FROM THE TIMESTAMP
    //$this->timezone_offset = (isset($timezone_offset)) ? $timezone_offset : null;
        $this->ail = $isLectionary;
        $this->isLectionary = ($isLectionary == 'lectionary') ? true : false;
        $this->timezone_offset = $timezone_offset;
        $this->time_adjust = time() + ($this->timezone_offset * 60 * 60);
        $this->year = isset($year) ? $year : date("Y", $this->time_adjust);
        $this->today_zday = isset($zday) ? $zday : date("z", $this->time_adjust);
        self::findEaster();
    }

    private function findEaster()
    {
        //$this->today_zday = date("z", $this->time_adjust); // numeric date of year 0 thru 365
        // the following tells us the year based on current time and date
        // to match to the object year ($this->year) on line #239
        $today_year = date("Y", $this->time_adjust);
        $this->today_dow = date("w", $this->time_adjust);
        $this_mo = date("m", $this->time_adjust);
        $this_day = date("d", $this->time_adjust);
        // Both Current Sunday and Today need to be based on the Advent Year, since the
        // calculations are based on the year that most of the readings fall into...
        // Church Year starts on Advent of (Year - 1), while the majority of readings
        // are in the year requested. e.g., Advent starts in 2014, but most of the readings are in 2015
        $this->advent_year = $this->year - 1;
        $mod = $this->advent_year%3; // Year A is a year in which no mod remains
        if ($mod == 0) {
            $this->lectionary_year = "A";
        } elseif ($mod == 1) {
            $this->lectionary_year = "B";
        } elseif ($mod == 2) {
            $this->lectionary_year = "C";
        }
        $this->next_year = $this->year + 1;
        // FINDING EASTER, FROM WHICH WE DERIVE VIRTUALLY EVERY OTHER DATE IN THE CALENDAR
        // using algorithm from Dr. Heiner Lichtenberg
        // http://www.ptb.de/de/org/4/44/441/oste.htm
        $k = intval($this->year/100);
        $m = 15 + intval(((3*$k)+3)/4) - intval(((8*$k)+13)/25);
        $s = 2 - intval(((3*$k)+3)/4);
        $a = $this->year % 19;
        $d = (19*$a + $m) % 30;
        $r = intval($d/29) + (intval($d/28) - intval($d/29))* (intval($a/11));
        $og = 21 + $d - $r;
        $sz = 7 - ($this->year+intval($this->year/4)+$s) % 7;
        $oe = 7 - ($og-$sz)%7;
        $os = $og + $oe;

        //THE FOLLOWING DATES ARE "MARKERS" TO POINT US TO THE PROPER DATES TO START USING
        //THE CORRECT DAILY READINGS.  LAST EPIPHANY IS NOT ALWAYS THE SAME DAY, AS EASTER CHANES
        //AND BECAUSE OF THIS THERE MAY BE FEWER EPIPHANY READINGS TO USE OR MORE PROPERS, ETC...
        $holy_name = date('l, F-d-Y', mktime(0, 0, 0, 1, 1, $this->year));
        $this->hn_year = date('Y', mktime(0, 0, 0, 1, 1, $this->year));
        $easter_day = date("l, F-d-Y", mktime(0, 0, 0, 3, $os, $this->year));
        $this->sundays[$easter_day] = "R";
        $last_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-49), $this->year));
        $this->sundays[$last_epiphany] = "LE";
        $this->ash_wed = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-46), $this->year));
        $this->sundays[$this->ash_wed] = "AW";
        $one_lent = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-42), $this->year));
        $this->sundays[$one_lent] = "1L";
        $this->ascension_day = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+39), $this->year));
        $this->sundays[$this->ascension_day] = "AD";

        $this->pentecost = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+49), $this->year));
        $this->sundays[$this->pentecost] = "P";
        $pentecost_month = date("n", mktime(0, 0, 0, 3, ($os+49), $this->year));
        $pentecost_day = date("j", mktime(0, 0, 0, 3, ($os+49), $this->year));

        $this->trinity_sun = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+56), $this->year));
        $this->sundays[$this->trinity_sun] = "T";

        ////////////////////////////////////////////////////////////
        // SPECIAL NEEDS FOR FIGURING CHRISTMAS SUNDAYS OR OTHERWISE & 1ST AND 2ND SUNDAYS AFTER CHRISTMAS
        ////////////////////////////////////////////////////////////
        // WE NEED TO FIGURE EPIPHANY IN ORDER TO SEE IF 2ND SUNDAY AFTER CHRISTMAS IS NEEDED
        $christmas_dow = date('w', mktime(0, 0, 0, 12, 25, $this->advent_year));
        $epiphany_dow = date('w', mktime(0, 0, 0, 1, 6, $this->year));
        $epiphany_wday = date('l', mktime(0, 0, 0, 1, 6, $this->year));
        $this->epiphany_day = date('l, F-d-Y', mktime(0, 0, 0, 1, 6, $this->year));
        $this->sundays[$this->epiphany_day] = "E";

        $e_diff = intval(7 - $epiphany_dow);
        if ($e_diff == 7) {//that is, Epiphany is on a Sunday, because $dow = 0, i.e. Sunday
            // now we must add a epiphany sunday
            $epiphany_sun = date('l, F-d-Y', mktime(0, 0, 0, 1, 6, $this->year));
            $this->sundays[$epiphany_sun] = "E";
        } else {
            $epiphany_sun = false;
        }
        $days_til_sun = intval(7-$christmas_dow);
        if ($days_til_sun > 0 && $days_til_sun < 7) {//Christmas is not on a Sunday
            //get the 1st Sunday After Christmas
            $this->christmas_not_sunday = date('l, F-d-Y', mktime(0, 0, 0, 12, 25, $this->advent_year));
            // not a sunday, but adding it to our array so we can display it
            $this->sundays[$this->christmas_not_sunday] = "CM";
            $christmas_sun_1 = date('l, F-d-Y', mktime(0, 0, 0, 12, (25+$days_til_sun), $this->advent_year));
            // Make Holy Name and put it into the list of days
            $this->holy_name = date('l, F-d-Y', mktime(0, 0, 0, 1, 1, $this->year));
            $this->sundays[$this->holy_name] = "HN";
            // add first sunday after christmas to the array
            $this->sundays[$christmas_sun_1] = "1C";
            $this->cs_1_day = date('j', mktime(0, 0, 0, 12, (25+$days_til_sun), $this->advent_year));
            //$cs_1_dow = date('w', mktime(0, 0, 0, 12,(25+$days_til_sun), $this->advent_year));
            $this->cs_1_year = date('Y', mktime(0, 0, 0, 12, (25+$days_til_sun), $this->advent_year));
            if (!$epiphany_sun) {//up above,
                //if it exists, don't overwrite with 2C
                $christmas_sun_2 = date('l, F-d-Y', mktime(0, 0, 0, 12, (25+$days_til_sun+7), $this->advent_year));
                $this->sundays[$christmas_sun_2] = "2C";
                $this->cs_2_day = date('j', mktime(0, 0, 0, 12, (25+$days_til_sun+7), $this->advent_year));
                $this->cs_2_dow = date('w', mktime(0, 0, 0, 12, (25+$days_til_sun+7), $this->advent_year));
            }
            // NOW, GO TO JUST BEFORE EPIPHANY TO SEE WHERE THIS IS ECHOED
        } elseif ($days_til_sun == 7) {
            //Christmas on a Sunday, because $dow=0 so 7-0 = 7
            $christmas_sunday = date('l, F-d-Y', mktime(0, 0, 0, 12, 25, $this->advent_year));
            $this->sundays[$christmas_sunday] = "CM";
            $christmas_sun_1 = date('l, F-d-Y', mktime(0, 0, 0, 12, (25+$days_til_sun), $this->advent_year));
            // IF CHRISTMAS IS ON A SUNDAY, THE FIRST SUNDAY AFTER CHRISTMAS IS ALWAYS "HOLY NAME"
            $this->sundays[$christmas_sun_1] = "HN";
            $this->cs_1_year = date('Y', mktime(0, 0, 0, 12, (25+$days_til_sun), $this->advent_year));
            // AND TECHNICALLY, IN THIS CASE, THERE IS NEVER A SECOND SUNDAY
            // AFTER CM BECAUSE EPIPHANY FALLS ON FRIDAY THEN
            // BUT WE HAVE IT HERE JUST BECAUSE I'M PICKY AND WANT TO HAVE IT
            // AROUND IN CASE THERE IS A BUG AND MY
            // CALCULATIONS SHOW UP WRONG
            if (!$epiphany_sun) {//up above,
                //if it exists, don't overwrite with 2C
                $christmas_sun_2 = date('l, F-d-Y', mktime(0, 0, 0, 12, (25+$days_til_sun+7), $this->advent_year));
                $this->sundays[$christmas_sun_2] = "2C";
            }
            // NOW, GO TO JUST BEFORE EPIPHANY TO SEE WHERE THIS IS ECHOED
        }
        ////////////////////////////////////////////////////////////////////
        $diff = abs(0 - $christmas_dow);
        if ($diff == 0) {
            // that is, Christmas is on a Sunday
            $diff = 7;
        }
        /////// THE FOLLOWING IS NEEDED FOR DATE CALCULATIONS IN fourAdvent();
        $this->four_advent = date("d", mktime(0, 0, 0, 12, (25-$diff), $this->advent_year));
        ///////
        $four_advent_sun = date("l, F-d-Y", mktime(0, 0, 0, 12, (25-$diff), $this->advent_year));
        $this->sundays[$four_advent_sun] = "4A";
        $three_advent_sun = date("l, F-d-Y", mktime(0, 0, 0, 12, (25-$diff-7), $this->advent_year));
        $this->sundays[$three_advent_sun] = "3A";
        $two_advent_sun = date("l, F-d-Y", mktime(0, 0, 0, 12, (25-$diff-14), $this->advent_year));
        $this->sundays[$two_advent_sun] = "2A";
        $one_advent_sun = date("l, F-d-Y", mktime(0, 0, 0, 12, (25-$diff-21), $this->advent_year));
        $this->sundays[$one_advent_sun] = "1A";
        $one_adv_mo = 12;
        $one_adv_mo_text = "December";
        $diff3 = 25 - $diff - 21;
        if ($diff3 <= 0) {
            $diff3 = 30 + ($diff3);
            //it seems that the calculations for $diff are based on 31 days
            // in December, so adjust needed
            $one_adv_mo = 11;
            $one_adv_mo_text = "November";
        }
        $this->one_advent_zday = date("z", mktime(0, 0, 0, $one_adv_mo, $diff3, $this->advent_year));
        if ($this->today_zday > 358) {
                $this->current_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day-$this->today_dow), $this->advent_year)
                );
                $this->upcoming_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day+(7 - $this->today_dow)), $this->advent_year)
                );
                $this->today = date("l, F d, Y", mktime(0, 0, 0, $this_mo, $this_day, $this->advent_year));
                $this->here = ">358";
        } elseif (($this->today_zday >= $this->monday_before_one_advent_zday) &&
            ($this->today_zday < $this->one_advent_next_zday)) {
                $this->current_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day-$this->today_dow), $this->advent_year)
                );
                $this->upcoming_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day+(7 - $this->today_dow)), $this->advent_year)
                );
                $this->today = date(
                    "l, F d, Y",
                    mktime(0, 0, 0, $this_mo, $this_day, $this->advent_year)
                );
                $this->here = ">monday_before_one_advent_zday";
        } elseif (($this->today_zday >= $this->one_advent_next_zday) && ($this->one_advent_next_zday != null)) {
                $this->current_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day-$this->today_dow), $this->advent_year)
                );
                $this->upcoming_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day+(7 - $this->today_dow)), $this->advent_year)
                );
                $this->today = date(
                    "l, F d, Y",
                    mktime(0, 0, 0, $this_mo, $this_day, $this->advent_year)
                );
                $this->here = "one_advent_zday";
        } else {
            // This is a bug... The current sunday will never be correct when
            // we are in a year ahead, like 2019 while we are looking for 2017
                $this->current_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day-$this->today_dow), $this->year)
                );
                $this->upcoming_sunday = date(
                    "l, F-d-Y",
                    mktime(0, 0, 0, $this_mo, ($this_day+(7 - $this->today_dow)), $this->year)
                );
                $this->today = date(
                    "l, F d, Y",
                    mktime(0, 0, 0, $this_mo, $this_day, $this->year)
                );
                $this->here = "else";
        }
        ////////////////////////////////////////////////////////////////////
        // FIND NEXT YEAR'S FIRST ADVENT
        // we don't need the zday of last year (i.e. $this->advent_year),
        // we need the zday of next year's first advent which is
        // technically this year's first advent, but who's really keeping track?
        ////////////////////////////////////////////////////////////////////
        $christmas_dow_next = date('w', mktime(0, 0, 0, 12, 25, $this->year));
        $diff_next = -intval(0 - $christmas_dow_next);
        if ($diff_next == 0) {
            // that is, Christmas is on a Sunday
            $diff_next = 7;
        }
        $four_advent_next = date("d", mktime(0, 0, 0, 12, (25-$diff_next), $this->year));
        $diff1_next = $four_advent_next - 7;
        $diff2_next = $four_advent_next - 14;
        $diff3_next = $four_advent_next - 21;
        $one_adv_mo_next = 12;
        if ($diff3_next <= 0) {
            $diff3_next = 30 + ($diff3_next);
            // it seems that the calculations for $diff are based on 31 days
            // in December, so adjust needed
            $one_adv_mo_next = 11;
        }
        // All we really need to determine when to move is
        // One advent ZDay, Monday before Advent Zday, and Today ZDay
        $one_advent_next_zday = date("z", mktime(0, 0, 0, $one_adv_mo_next, $diff3_next, $this->year));
        $this->one_advent_next_zday = $one_advent_next_zday;
        $this->monday_before_one_advent_zday = date(
            "z",
            mktime(0, 0, 0, $one_adv_mo_next, ($diff3_next-6), $this->year)
        );
        //
        ///////////////////////////////////////////////////////////////////
        // END OF THE FIX FOR NEXT YEAR'S FIRST ADVENT
        ///////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////
        // NOW WE CAN CHECK FOR THE FIRST SUNDAY OF ADVENT AND CHECK
        // THE ZDAYS AGAINST EACH OTHER
        // if ( ($this->today_zday >= $one_advent_next_zday) && ($today_year == $this->year) )
        // {
        // 	self::__construct($this->next_year);
        // 	return;
        // }
        if ($this->isLectionary) {
            if (($this->today_zday >= $this->monday_before_one_advent_zday)
                && ($today_year == $this->year) ) {
                    // reset any sundays we've already calculated, as we don't want any
                    // with the wrong year, i.e. it's 2016, and we have 2015 in here...
                    $this->sundays = null;
                    self::__construct('lectionary', $this->next_year, $this->timezone_offset);
                    return;
            }
        } else {
            if (($this->today_zday >= $one_advent_next_zday) && ($today_year == $this->year)) {
                // reset any sundays we've already calculated, as we don't want any
                // with the wrong year, i.e. it's 2016, and we have 2015 in here...
                $this->sundays = null;
                self::__construct('daily_office', $this->next_year, $this->timezone_offset);
                return;
            }
        }
        //////////////////////////////////////////////////////////////////////////////
        // EPIPHANY DATES ARE FIGURED ABOVE NEXT TO CHRISTMAS SUNDAYS
        // AS EPIPHANY IS NECESSARY IN FIGURING WHETHER 2 CHRISTMAS SUNDAYS ARE NEEDED
        //////////////////////////////////////////////////////////////////////////////
        $still_epiph = 1;
        $one_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff), $this->year));
        $this->sundays[$one_epiphany] = "1E";
        $two_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff+7), $this->year));
        $this->sundays[$two_epiphany] = "2E";
        $three_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff+14), $this->year));
        $this->sundays[$three_epiphany] = "3E";
        $four_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff+21), $this->year));
        $this->sundays[$four_epiphany] = "4E";
        if ($four_epiphany == $last_epiphany) {
            $still_epiph = 0; //set it to false
        }
        if ($four_epiphany != $last_epiphany && $still_epiph) {
            $this->sundays[$four_epiphany] = "4E";
        } elseif ($four_epiphany == $last_epiphany) {
            $four_epiphany = $last_epiphany;
            $this->sundays[$four_epiphany] = "LE";
            $still_epiph = 0;
        }
        $five_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff+28), $this->year));
        if ($five_epiphany != $last_epiphany && $still_epiph) {
            $this->sundays[$five_epiphany] = "5E";
        } elseif ($five_epiphany == $last_epiphany) {
            $five_epiphany = $last_epiphany;
            $this->sundays[$five_epiphany] = "LE";
            $still_epiph = 0;
        }
        $six_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff+35), $this->year));
        if ($six_epiphany != $last_epiphany && $still_epiph) {
            $this->sundays[$six_epiphany] = "6E";
        } elseif ($six_epiphany == $last_epiphany) {

            $six_epiphany = $last_epiphany;
            $this->sundays[$six_epiphany] = "LE";
            $still_epiph = 0;
        }
        $seven_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff+42), $this->year));
        if ($seven_epiphany != $last_epiphany && $still_epiph) {
            $this->sundays[$seven_epiphany] = "7E";
        } elseif ($seven_epiphany == $last_epiphany) {
            $seven_epiphany = $last_epiphany;
            $this->sundays[$seven_epiphany] = "LE";
            $still_epiph = 0;
        }
        $eight_epiphany = date("l, F-d-Y", mktime(0, 0, 0, 1, (6+$e_diff+49), $this->year));
        if ($eight_epiphany != $last_epiphany && $still_epiph) {
            $this->sundays[$eight_epiphany] = "8E";
        }
        if ($eight_epiphany == $last_epiphany) {
            $eight_epiphany = $last_epiphany;
            $this->sundays[$eight_epiphany] = "LE";
            $still_epiph = 0;
        }
        $two_lent = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-35), $this->year));
        $this->sundays[$two_lent] = "2L";
        $three_lent = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-28), $this->year));
        $this->sundays[$three_lent] = "3L";
        $four_lent = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-21), $this->year));
        $this->sundays[$four_lent] = "4L";
        $five_lent = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-14), $this->year));
        $this->sundays[$five_lent] = "5L";
        $holy_week = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os-7), $this->year));
        $this->sundays[$holy_week] = "PS";

        $two_easter = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+7), $this->year));
        $this->sundays[$two_easter] = "2R";
        $three_easter = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+14), $this->year));
        $this->sundays[$three_easter] = "3R";
        $four_easter = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+21), $this->year));
        $this->sundays[$four_easter] = "4R";
        $five_easter = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+28), $this->year));
        $this->sundays[$five_easter] = "5R";
        $six_easter = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+35), $this->year));
        $this->sundays[$six_easter] = "6R";
        $seven_easter = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+42), $this->year));
        $this->sundays[$seven_easter] = "7R";

        // HERE IS WHERE IT GETS HAIRY.  WE NEED TO FIND THE FIRST PROPER THAT IS CLOSEST TO PENTECOST SUNDAY
        // AND USE THAT PROPER AS THE STARTING POINT FOR THE YEAR.
        // THE UPSIDE IS THAT NO HUGE CALCULATIONS HAVE TO BE MADE.  WE NEED ONLY MAKE THE FIRST PENTECOST PROPER
        // CALCULATION, AND THEN KEEP ADDING 7 TO THE DATE TO MAKE THE NEXT PROPER. YEEHAW.
        $proper_array['1'] = '5-11';
        $proper_array['2'] = '5-18';
        $proper_array['3'] = '5-25';
        $proper_array['4'] = '6-1';
        $proper_array['5'] = '6-8';
        $proper_array['6'] = '6-15';
        $proper_array['7'] = '6-22';
        $proper_array['8'] = '6-29';
        $proper_array['9'] = '7-6';
        $proper_array['10'] = '7-13';
        $proper_array['11'] = '7-20';
        $proper_array['12'] = '7-27';
        $proper_array['13'] = '8-3';
        $proper_array['14'] = '8-10';
        $proper_array['15'] = '8-17';
        $proper_array['16'] = '8-24';
        $proper_array['17'] = '8-31';
        $proper_array['18'] = '9-7';
        $proper_array['19'] = '9-14';
        $proper_array['20'] = '9-21';
        $proper_array['21'] = '9-28';
        $proper_array['22'] = '10-5';
        $proper_array['23'] = '10-12';
        $proper_array['24'] = '10-19';
        $proper_array['25'] = '10-26';
        $proper_array['26'] = '11-2';
        $proper_array['27'] = '11-9';
        $proper_array['28'] = '11-16';
        $proper_array['29'] = '11-23';

        foreach ($proper_array as $key => $proper_test) {
            $day_month = explode("-", $proper_test);
            $month = $day_month[0];
            $day = $day_month[1];
            $proper_date = date("l, F-d-Y", mktime(0, 0, 0, $month, $day, $this->year));
            $last_month = $pentecost_month - 1;
            $next_month = $pentecost_month + 1;
            if ($month == $last_month || $month == $pentecost_month || $month == $next_month) {
                $days_to_add = date('t', mktime(0, 0, 0, $next_month, $day, $this->year));
                $days_to_sub = date('t', mktime(0, 0, 0, $last_month, $day, $this->year));
                if ($month == $last_month) {
                    //for those times when pentecost starts at the end of may, and requires a Proper in June
                    $day = $day + $days_to_sub;//subtract number of days in last month
                }
                if ($month == $next_month) {
                    $day = $day + $days_to_add;//add number of days in next month
                }
                $start_day = intval(abs($pentecost_day - $day));
                if ($start_day <= 3) {
                    //Wednesday makes a cutoff day, so more than 3 is the following week's Proper
                    
                    // Temporary Bug Fix
                    if ($this->year = "2024") {
                        $pentecost_proper = $key;
                        $this->pentecost_proper = "2";
                        $trinity_sun_proper = "3";
                        $this->trinity_proper = "3";
                    } else {
                        $this->pentecost_proper = $key;
                        $trinity_sun_proper = $key+1;
                        $this->trinity_proper = $key+1;
                    }
                        /*$this->pentecost_proper = $key;
                        $trinity_sun_proper = $key+1;
                        $this->trinity_proper = $key+1;
                        */
                    
                    if (($this->current_sunday == $this->pentecost) && ($this->today_dow != 0)) {
                        $start_proper = $pentecost_proper;
                        $datebuffer = 49;
                    } elseif (($this->current_sunday == $this->trinity_sun) && ($this->today_dow != 0)) {
                        $start_proper = $trinity_sun_proper;
                        $datebuffer = 56;
                    } else {
                        $start_proper = $trinity_sun_proper + 1;
                        $datebuffer = 63;
                    }
                    // //Wednesday makes a cutoff day, so more than 3 is the following week's Proper
                    // $pentecost_proper = $key;
                    // $this->pentecost_proper = $key;
                    // $trinity_sun_proper = $key+1;
                    // $this->trinity_proper = $key+1;
                    // if (($this->current_sunday == $this->pentecost) && ($this->today_dow != 0)) {
                    //     $start_proper = $pentecost_proper;
                    //     $datebuffer = 49;
                    // } elseif (($this->current_sunday == $this->trinity_sun) && ($this->today_dow != 0)) {
                    //     $start_proper = $trinity_sun_proper;
                    //     $datebuffer = 56;
                    // } else {
                    //     $start_proper = $trinity_sun_proper + 1;
                    //     $datebuffer = 63;
                    // }
                }
            } else {
                next($proper_array);
            }
        }

        for ($i = $start_proper, $x = $datebuffer; $i <=29; $i++, $x+=7) {
            if ($i == $pentecost_proper) {
                $loop_sunday = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+$x), $this->year));
                $this->sundays[$loop_sunday] = "P";
            } elseif ($i == $trinity_sun_proper) {
                $loop_sunday = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+$x), $this->year));
                $this->sundays[$loop_sunday] = "T";
            } else {
                $loop_sunday = date("l, F-d-Y", mktime(0, 0, 0, 3, ($os+$x), $this->year));
                $this->sundays[$loop_sunday] = "$i";
            }
        }
        // current season is used only in the daily office elements,
        $this->findCurrentSeason($this->current_sunday);
    // end of findEaster
    }

    public function findCurrentSeason($current_sunday)
    {
        $current_id = $this->sundays[$current_sunday];
        $this->current_id = $current_id;
        // Advent
        if (($current_id == "4A") || ($current_id == "3A") || ($current_id == "2A")
            || ($current_id == "1A")) {
            $this->current_season = "Advent";
        }
        // Christmas
        if (($current_id == "CM") || ($current_id == "CM1") || ($current_id == "CM2")
            || ($current_id == "HN") || ($current_id == "HI") || ($current_id == "STST")
            || ($current_id == "STJE") || ($current_id == "12-24") ) {
            $this->current_season = "Christmas";
        }
        // Epiphany
        // Lent
        if (($current_id == "1L") || ($current_id == "2L") || ($current_id == "3L")
            || ($current_id == "4L") || ($current_id == "5L") || ($current_id == "6L")
            || ($current_id == "7L") || ($current_id == "8L") || ($current_id == "9L")
            || ($current_id == "PS") || ($current_id == "PS-1") || ($current_id == "PS-2")
            || ($current_id == "PS-3") || ($current_id == "PS-4") || ($current_id == "PS-5")
            || ($current_id == "PS-6") ) {
            if (($current_id == "PS") || ($current_id == "PS-1") || ($current_id == "PS-2")
                || ($current_id == "PS-3") || ($current_id == "PS-4") || ($current_id == "PS-5")
                || ($current_id == "PS-6") ) {
                $this->current_season = "Holy_Week";
            } else {
                $this->current_season = "Lent";
            }
        }
        // Easter
        if (($current_id == "R") || ($current_id == "1R") || ($current_id == "2R") || ($current_id == "3R")
            || ($current_id == "4R") || ($current_id == "5R") || ($current_id == "6R")
            || ($current_id == "7R") || ($current_id == "AD") || ($current_id == "R-1")
            || ($current_id == "R-2") || ($current_id == "R-3") || ($current_id == "R-4")
            || ($current_id == "R-5") || ($current_id == "R-6")) {
            if (($current_id == "R-1") || ($current_id == "R-2") || ($current_id == "R-3")
                || ($current_id == "R-4") || ($current_id == "R-5") || ($current_id == "R-6")
            ) {
                $this->current_season = "Easter_Week";
            } else {
                $this->current_season = "Easter";
            }
        }
        // Pentecost
        if ($current_id == "P") {
            $this->current_season = "Pentecost";
        }
        // Trinity Sunday
        if ($current_id == "T") {
            $this->current_season = "Trinity";
        }
        // Regular Time - all others are ctype_alnum
        if (is_numeric($current_id)) {
            $this->current_season = "Regular";
        }
        // Christ the King (i.e. do_id = 29)
        if ($current_id == "29") {
            $this->current_season = "CTK";
        }
    }
}

