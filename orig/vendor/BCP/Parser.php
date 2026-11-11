<?php
namespace BCP;

use PDO;

// Parser Class PHP
class Parser
{
    public $passage             = null;
    public $chapter             = null;
    public $reference           = null;
    private $current_reference  = null;
    public $version             = null;
    public $book_id             = null;
    public $book_name           = null;
    public $morning_intention   = null;
    public $evening_intention   = null;
    public $useBCP              = null;
    private $db                 = null;

    public function __construct($db, $useBCP, $version = null)
    {
        $this->db = isset($db) ? $db : null;
        $this->useBCP = isset($useBCP) ? $useBCP : null;
        $this->version = isset($version) ? $version : "NRSV";
    }
    /**
     * Parse Reading Function - Returns the passage from the database that was passed to the function
     * @param object $db The PDO database object that was passed
     * @param string $reading The string of data to be parsed; e.g Joel 2:1-2,12-17
     * @param boolean $isPsalm Flag to indicate if it is a Psalm
     * We are now splitting the readings in the controller, so we will only ever parse
     * one reading at a time
     */
    public function parseReadings($reading, $isPsalm = null)
    {
        $this->isPsalm = isset($isPsalm) ? true : false;
        // Set the readings reference - essentially what
        // will be used as a title
        $reading = ltrim($reading);
        $this->evening_intention = strstr($reading, "***")
            ? true : false;
        // Determine if this is intended for use in the morning
        $this->morning_intention = (strstr($reading, "**") && !strstr($reading, "***"))
            ? true : false;
        // Now that we have this info, remove the indicators
        $this->reference = str_replace("*", "", $reading);
        // Determine if our reading is a "None" indicator
        $this->no_reading = ($this->reference == "None") ? true : false;
        // First, we need to see if the "reading" is actually two
        // by checking if we have the "or" or the "&&" and that means
        // we don't need to deal with it during the loop of references
        // Determine if we have a comma type reading
        $this->comma_reading = (strstr($reading, ",")) ? true : false;
        // Determine if we have brackets
        // we only set the flag so that we can add the brackets
        // around the reading after parsing it.
        $this->bracketed_reading = (strstr($reading, "["))
            ? true : false;
        // Determine if we have parentheses
        $this->paren_reading = (strstr($reading, "(")) ? true : false;
        // Determine if we have a span of chapters
        $this->spanned_reading = (count(explode(":", $reading)) > 2)
            ? true : false;
        // Now that we have the important details of where to send
        // our reading for parsing, we can get the book name and ID
        list($this->book_name, $this->book_id, $this->current_reference)
            = self::getBookNameAndId();
        // For comma readings
        if (($this->comma_reading == true)) {
            $this->passage = self::parseComma();
        } elseif (($this->paren_reading == true) && ($this->spanned_reading == false)) {
            // For paren readings that don't span
            $this->passage = self::parseParen();
        } elseif (($this->bracketed_reading == true) && ($this->spanned_reading == false)) {
            // For Bracketed Readings that don't span
            $this->passage = self::parseBracketed();
        } elseif ($this->spanned_reading == true) {
            // For Spanned Readings
            // we do have bracketed readings that span (i.e. Colossians [3:18-4:1])
            // Bracketed readings are easiest, as we just pull the info
            // and then place the brackets around them at the end.
            if ($this->bracketed_reading == true) {
                $bracket_portion = self::parseBracketed(true);
            }
            // And we do have paren readings that span (i.e. 2Corinthians 6:3-13(14-7:1))
            // Paren readings are more difficult, as we need to get the reading and
            // parse out the items in between.
            if ($this->paren_reading == true) {
                $paren_portion = self::parseParen(true);
            }
            $this->here = "Inside ElseIf and no paren or brackets...";
            // Otherwise, we have a straightforward Spanned Reading
            $this->passage = self::parseSpanned();
        } else {
            $this->here = "Inside Else with parseStandard";
            $this->cr = $this->current_reference;
            $this->passage = self::parseStandard($this->current_reference);
        }
    }

    /**
     * parseParen Method to parse readings that have parentheses in them
     * @param boolean isSpanned If there is a spanned reading, then we
     * need to make sure that we do the additional gymnastics
     * @return string Parsed passage
     */
    private function parseParen($isSpanned = null)
    {
        $split = explode(":", $this->current_reference);
        // In the case of say "2Corinthians (6:14-18)" we need to
        // make sure that the bracket is not at the beginning
        if (strstr($split[0], "(")) {
            // basically, set the bracket start and end
            // and then do a regular type
            $this->full_paren = true;
            $this->chapter = str_replace("(", "", $split[0]);
            $current = str_replace("(", "", $this->current_reference);
            $current = str_replace(")", "", $current);
            $this->cr = $current;
            return self::parseStandard($current);
        } else {
            $this->chapter = $split[0];
        }
        // Some of the references have "a,b,c" in them...
        $cleaned_ref = self::cleanPartialVerses($split[1]);
        $before_parens = strstr($cleaned_ref, "(", true);
        $this->bp = $before_parens;
        preg_match('/\(([^)]+)\)/', $cleaned_ref, $matches);
        $inside_parens = $matches[1];
        $this->ip = $inside_parens;
        // get an array of values - always returns at least one
        $before_values = explode("-", $before_parens);
        // If parens are right after colon
        if (empty($before_parens)) {
            // See if it is more than one verse and change before_values
            if (explode("-", $inside_parens) > 1) {
                $before_values = explode("-", $inside_parens);
                $this->bv = $before_values;
            } else {
                // if only one verse, use the matched regex item in an array
                $before_values = array($inside_parens);
                $this->bv2 = $before_values;
            }
            $this->paren_start = $before_values[0];
            $this->paren_end = isset($before_values[1])
                ? $before_values[1]
                : $before_values[0];
            // now, the second values are going to be different
            // i.e. not technically "inside"
            $after_parens = strstr($cleaned_ref, ")");
            $inside_values = explode("-", str_replace(")", "", $after_parens));
        } else {
            $inside_values = explode("-", $inside_parens);
            $this->paren_start = $inside_values[0];
            $this->paren_end = isset($inside_values[1])
                ? $inside_values[1]
                : $inside_values[0];
        }
        // if all is as planned, our thing looks like this:
        // before values is x-x, inside values are (x-x) and
        // we need to check for a third set of values
        $after_parens = strstr($cleaned_ref, ")");
        if (!empty($after_parens)) {
            $after_values = explode("-", str_replace(")", "", $after_parens));
        }

        // If it's set that the spanned reading is true, we need to
        // do a bunch of additional gymnastics to make this work
        if (isset($isSpanned) && ($isSpanned == true)) {
            $this->inside_parens = "I got a true flag";
        } else {
            return self::doDataCall($before_values, $inside_values, $after_values);
        }
    }

    /**
     * parseBracketed Method to parse readings that have brackets in them
     * @param boolean isSpanned If there is a spanned reading, then we
     * need to make sure that we do the additional gymnastics
     * @return string Parsed passage
     */
    private function parseBracketed($isSpanned = null)
    {
        $split = explode(":", $this->current_reference);
        // In the case of say "Colossians [4:1-8]" we need to
        // make sure that the bracket is not at the beginning
        if (strstr($split[0], "[")) {
            $this->here = "Inside Parse Bracketed...";
            // basically, set the bracket start and end
            // and then do a regular type
            $this->full_bracket = true;
            // Chapter gets set in the parseStandard method
            $current = str_replace("[", "", $this->current_reference);
            $current = str_replace("]", "", $current);
            return self::parseStandard($current);
        } else {
            $this->here = "Inside the Else of the Parse Bracketed...";
            $this->chapter = $split[0];
        }
        // Some of the references have "a, b, c" in them...
        $cleaned_ref = isset($split[1]) ? self::cleanPartialVerses($split[1]) : null;
        $before_brackets = strstr($cleaned_ref, "[", true);
        $this->bb = $before_brackets;
        preg_match('/\[(.*?)\]/', $cleaned_ref, $matches);
        $inside_brackets = isset($matches[1]) ? $matches[1] : null;
        $this->inside_brackets = $inside_brackets;
        // get an array of values - always returns at least one
        $before_values = explode("-", $before_brackets);
        $inside_values = explode("-", $inside_brackets);
        $this->bracket_start = $inside_values[0];
        $this->bracket_end = isset($inside_values[1])
            ? $inside_values[1]
            : $inside_values[0];
        // If it's set that the spanned reading is true, we need to
        // do a bunch of additional gymnastics to make this work
        if (isset($isSpanned) && ($isSpanned == true)) {
            $this->inside_brackets = "I got a true flag";
        } else {
            return self::doDataCall($before_values, $inside_values);
        }
    }

    /**
     * parseComma Method to parse readings that have commas in them
     * @param boolean isSpanned If there is a spanned reading, then we
     * need to make sure that we do the additional gymnastics
     * @return string Parsed passage
     */
    private function parseComma($isSpanned = null)
    {
        $split = explode(":", $this->current_reference);
        $this->chapter = $split[0];
        // Some of the references have "a, b, c" in them...
        $cleaned_ref = self::cleanPartialVerses($split[1]);
        $comma_array = explode(",", $cleaned_ref, 3);
        if (isset($comma_array[0])) {
            $before_values = explode("-", $comma_array[0]);
        }
        if (isset($comma_array[1])) {
            $inside_values = explode("-", $comma_array[1]);
        } else {
            $inside_values = null;
        }
        if (isset($comma_array[2])) {
            $after_values = explode("-", $comma_array[2]);
        } else {
            $after_values = null;
        }
        // If it's set that the spanned reading is true, we need to
        // do a bunch of additional gymnastics to make this work
        return self::doDataCall($before_values, $inside_values, $after_values);
    }

    /**
     * parseStandard Method to parse regurlar readings
     * @param string reference String with reading reference
     * @return string Parsed passage
     */
    private function parseStandard($reference)
    {
        //TODO: PSalm chapter is not being set.
        $split = explode(":", $reference);
        $this->chapter = $split[0];
        // Some of the references have "a, b, c" in them...
        // If this is a psalm, we don't need to clean it
        // TODO: wrap this, so no undefined index warning
        $cleaned_ref = isset($split[1]) ? self::cleanPartialVerses($split[1]) : "";
        // get an array of values - always returns at least one
        $before_values = explode("-", $cleaned_ref);
        $this->bv = $before_values;
        $this->bv_empty = empty($before_values[0]);
        if (empty($before_values[0]) && ($this->isPsalm == true)) {
            // Apparently, psalm 119 has 176 verses, so go one
            // bigger to get them all, just in case...
            $before_values = array(0, 177);
            return self::doDataCall($before_values);
        } else {
            return self::doDataCall($before_values);
        }
    }

    /**
     * parseSpanned Method to parse spanned readings
     * @return string Parsed passage
     */
    private function parseSpanned()
    {
        $split = explode("-", $this->current_reference);
        // for each of these, we need to add in the possible end
        // or the beginning - i.e. verse 1
        $first_ref = $split[0];
        $first_splits = explode(":", $first_ref);
        // We will never split across books, so this is a safe bet
        $this->chapter = $first_splits[0];
        $this->first_chapter = $this->chapter;
        // similar to the standard parsing, we send the highest possible
        // verse so we get the remaining...
        $first_values = array($first_splits[1], 177);
        $first_passage = self::doDataCall($first_values);
        // Do the second passage
        $second_ref = $split[1];
        $second_splits = explode(":", $second_ref);
        $this->chapter = $second_splits[0];
        $this->second_chapter = $this->chapter;
        // Start at 1 and go to the end of the verse requested
        $second_values = array(1, $second_splits[1]);
        // I call it second values here, but in the data call it
        // is $first_values, technically
        $second_passage = self::doDataCall($second_values);
        // Now I need to union the arrays without losing keys
        return array_merge($first_passage, $second_passage);
    }

    /**
     * doDataCall Method to pull data from the database
     * @param mixed $first_values The first array containing values for verses
     * @param mixed $second_values The second array containing verse values
     * @param mixed $third_values The third array containing verse values
     * @return mixed $result This returns the DataBase object array, which will
     * get encoded as a JSON object later on.
     */
    private function doDataCall($first_values, $second_values = null, $third_values = null)
    {
        if (( isset($this->isPsalm) && ($this->isPsalm == true)) &&
            (isset($this->useBCP) && ($this->useBCP == true)) ) {
            $query = "
            SELECT 'Psalm', chapter, verse, text
                        FROM bcp_psalms
                        WHERE chapter=:chapter ";
            // if the second value of the items is set, then we have a "between"
            // otherwise we have just one verse
            if (isset($first_values[1])) {
                $query .= "AND verse BETWEEN :value_1 AND :value_2 ";
            } else {
                $query .= "AND verse=:value_1 ";
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($second_values)) {
                $query .= "UNION
                SELECT 'Psalm', chapter, verse, text
                            FROM bcp_psalms
                            WHERE chapter=:chapter2 ";
                if (isset($second_values[1])) {
                    $query .= "AND verse BETWEEN :value_3  AND :value_4 ";
                } else {
                    $query .= "AND verse=:value_3 ";
                }
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($third_values)) {
                $query .= "UNION
                SELECT 'Psalm', chapter, verse, text
                            FROM bcp_psalms
                         WHERE chapter=:chapter3 ";
                if (isset($third_values[1])) {
                    $query .= "AND verse BETWEEN :value_5  AND :value_6 ";
                } else {
                    $query .= "AND verse=:value_5 ";
                }
            }
            $query .= "ORDER BY verse";
            $prepped = $this->db->dbh->prepare($query);
            $prepped->bindParam(":chapter", $this->chapter, PDO::PARAM_INT);
            if (isset($second_values)) {
                $prepped->bindParam(":chapter2", $this->chapter, PDO::PARAM_INT);
            }
            if (isset($third_values)) {
                $prepped->bindParam(":chapter3", $this->chapter, PDO::PARAM_INT);
            }
            // if the second value of the items is set, then we have a "between"
            // otherwise we have just one verse
            if (isset($first_values[1])) {
                $prepped->bindParam(":value_1", $first_values[0], PDO::PARAM_INT);
                $prepped->bindParam(":value_2", $first_values[1], PDO::PARAM_INT);
            } else {
                $prepped->bindParam(":value_1", $first_values[0], PDO::PARAM_INT);
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($second_values)) {
                if (isset($second_values[1])) {
                    $prepped->bindParam(":value_3", $second_values[0], PDO::PARAM_INT);
                    $prepped->bindParam(":value_4", $second_values[1], PDO::PARAM_INT);
                } else {
                    $prepped->bindParam(":value_3", $second_values[0], PDO::PARAM_INT);
                }
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($third_values)) {
                if (isset($third_values[1])) {
                    $prepped->bindParam(":value_5", $third_values[0], PDO::PARAM_INT);
                    $prepped->bindParam(":value_6", $third_values[1], PDO::PARAM_INT);
                } else {
                    $prepped->bindParam(":value_5", $third_values[0], PDO::PARAM_INT);
                }
            }
            $prepped->execute();
            $result = $prepped->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $query = "
            SELECT book_name, chapter, verse, text
                        from bible
                        WHERE version=:version
                         AND book_id=:book_id
                         AND chapter=:chapter ";
            // if the second value of the items is set, then we have a "between"
            // otherwise we have just one verse
            if (isset($first_values[1])) {
                $query .= "AND verse BETWEEN :value_1 AND :value_2 ";
            } else {
                $query .= "AND verse=:value_1 ";
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($second_values)) {
                $query .= "UNION
                SELECT book_name, chapter, verse, text
                            from bible
                            WHERE version=:version2
                             AND book_id=:book_id2
                             AND chapter=:chapter2 ";
                if (isset($second_values[1])) {
                    $query .= "AND verse BETWEEN :value_3  AND :value_4 ";
                } else {
                    $query .= "AND verse=:value_3 ";
                }
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($third_values)) {
                $query .= "UNION
                SELECT book_name, chapter, verse, text
                            from bible
                            WHERE version=:version3
                             AND book_id=:book_id3
                             AND chapter=:chapter3 ";
                if (isset($third_values[1])) {
                    $query .= "AND verse BETWEEN :value_5  AND :value_6 ";
                } else {
                    $query .= "AND verse=:value_5 ";
                }
            }
            $query .= "ORDER BY verse";
            $prepped = $this->db->dbh->prepare($query);
            $prepped->bindParam(":version", $this->version, PDO::PARAM_STR);
            $prepped->bindParam(":book_id", $this->book_id, PDO::PARAM_INT);
            $prepped->bindParam(":chapter", $this->chapter, PDO::PARAM_INT);
            if (isset($second_values)) {
                $prepped->bindParam(":version2", $this->version, PDO::PARAM_STR);
                $prepped->bindParam(":book_id2", $this->book_id, PDO::PARAM_INT);
                $prepped->bindParam(":chapter2", $this->chapter, PDO::PARAM_INT);
            }
            if (isset($third_values)) {
                $prepped->bindParam(":version3", $this->version, PDO::PARAM_STR);
                $prepped->bindParam(":book_id3", $this->book_id, PDO::PARAM_INT);
                $prepped->bindParam(":chapter3", $this->chapter, PDO::PARAM_INT);
            }
            // if the second value of the items is set, then we have a "between"
            // otherwise we have just one verse
            if (isset($first_values[1])) {
                $prepped->bindParam(":value_1", $first_values[0], PDO::PARAM_INT);
                $prepped->bindParam(":value_2", $first_values[1], PDO::PARAM_INT);
            } else {
                $prepped->bindParam(":value_1", $first_values[0], PDO::PARAM_INT);
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($second_values)) {
                if (isset($second_values[1])) {
                    $prepped->bindParam(":value_3", $second_values[0], PDO::PARAM_INT);
                    $prepped->bindParam(":value_4", $second_values[1], PDO::PARAM_INT);
                } else {
                    $prepped->bindParam(":value_3", $second_values[0], PDO::PARAM_INT);
                }
            }
            // if the second values are set, and have more than one value, then
            // we need to include those in the query
            if (isset($third_values)) {
                if (isset($third_values[1])) {
                    $prepped->bindParam(":value_5", $third_values[0], PDO::PARAM_INT);
                    $prepped->bindParam(":value_6", $third_values[1], PDO::PARAM_INT);
                } else {
                    $prepped->bindParam(":value_5", $third_values[0], PDO::PARAM_INT);
                }
            }
            $prepped->execute();
            $result = $prepped->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }
    /**
     * getBookNameAndId - Returns the book name and the ID from the
     * reference set earlier
     */
    private function getBookNameAndId()
    {
        if ($this->isPsalm == true) {
            $book_id = 19;
            $book_name = "Psalm";
            // TODO: Need to get the bookName from the database
            // in case the user is using a foreign language
            return array($book_name, $book_id, $this->reference);
        } else {
            $name = explode(" ", $this->reference);
            $book_list = array(
            "GENESIS" => "1","EXODUS" => "2","LEVITICUS" => "3","NUMBERS" => "4","DEUTERONOMY" => "5","JOSHUA" => "6",
            "JUDGES" => "7","RUTH" => "8","1SAMUEL" => "9","2SAMUEL" => "10","1KINGS" => "11","2KINGS" => "12",
            "1CHRONICLES" => "13","2CHRONICLES" => "14","EZRA" => "15",
            "NEHEMIAH" => "16","ESTHER" => "17","JOB" => "18",
            "PSALM" => "19","PROVERBS" => "20","ECCLESIASTES" => "21","SONGOFSOLOMON" => "22","ISAIAH" => "23",
            "JEREMIAH" => "24","LAMENTATIONS" => "25","EZEKIEL" => "26","DANIEL" => "27","HOSEA" => "28",
            "JOEL" => "29","AMOS" => "30","OBADIAH" => "31","JONAH" => "32","MICAH" => "33","NAHUM" => "34",
            "HABAKKUK" => "35","ZEPHANIAH" => "36","HAGGAI" => "37","ZECHARIAH" => "38","MALACHI" => "39",
            "MATTHEW" => "40","MARK" => "41","LUKE" => "42","JOHN" => "43","ACTS" => "44","ROMANS" => "45",
            "1CORINTHIANS" => "46","2CORINTHIANS" => "47","GALATIANS" => "48","EPHESIANS" => "49","PHILIPPIANS" => "50",
            "COLOSSIANS" => "51","1THESSALONIANS" => "52","2THESSALONIANS" => "53",
            "1TIMOTHY" => "54","2TIMOTHY" => "55",
            "TITUS" => "56","PHILEMON" => "57","HEBREWS" => "58","JAMES" => "59","1PETER" => "60","2PETER" => "61",
            "1JOHN" => "62","2JOHN" => "63","3JOHN" => "64","JUDE" => "65","REVELATION" => "66","1MACCABEES" => "67",
            "ECCLESIASTICUS" => "68","WISDOM" => "69","BARUCH" => "70","JUDITH" => "71"
            );
            foreach ($book_list as $b_name => $b_id) {
                if ($b_name == strtoupper($name[0])) {
                    $book_id = $b_id;
                    $reading_leftover = $name[1];
                    break;
                }
            }
            // The APOCRYPHAL books are only available in the RSV
            if ($book_id >= 67) {
                $this->version = "RSV";
            }
            // TODO: Need to get the bookName from the database
            // in case the user is using a foreign language
            $book_name = self::getBookTitle($book_id);
            return array($book_name,$book_id,$reading_leftover);
        }
    }

    /**
      *
      */
    private function cleanPartialVerses($reference)
    {
        $reference = str_replace("a", "", $reference);
        $reference = str_replace("b", "", $reference);
        $reference = str_replace("c", "", $reference);
        return $reference;
    }
    /**
     * getBookTitle - Returns the book name based on the ID we retrieved; will return
     * the name of the book, even if it is in a different language
     * @param string $book_id The ID of the bible book
     */
    private function getBookTitle($book_id)
    {
        $query = $this->db->dbh->prepare("
            SELECT book_name
              from bible
             WHERE booK_id = :book_id
               AND version = :version
            LIMIT 1;
        ");
        $query->bindParam(":book_id", $book_id);
        $query->bindParam(":version", $this->version);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['book_name'];
    }
}
