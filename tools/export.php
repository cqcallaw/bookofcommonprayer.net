<?php
/**
 * Build the static site's data from the local database and the PHP calendar.
 *
 * Run inside the web container:
 *   docker exec -w /var/www/html dev-web-1 php tools/export.php
 *
 * The day index calls BCP\ChurchCalendar and BCP\DailyOffice the same way
 * api/daily_office.php does for type=archive, including the year+1 branch
 * and the Pentecost/Trinity id swap. It does not reimplement Easter.
 */

ini_set("memory_limit", "512M");
// ChurchCalendar warns when a Sunday key is missing. That is existing behavior.
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("UTC");
$daysOnly = in_array("days", $argv, true);
chdir("/var/www/html");

require "system/cfg/config.php";
set_include_path(CLASS_DIR . PATH_SEPARATOR . VENDOR . PATH_SEPARATOR . get_include_path());
require CLASS_DIR . "SplAutoload.php";
$loader = new SplClassLoader();
$loader->register();
require __DIR__ . "/sunday_names.php";

$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
    DB_USER,
    DB_PASS,
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    )
);

$root = "/var/www/html";
$dataDir = $root . "/site/_data";
$bibleDir = $root . "/site/bible";
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0775, true);
}

function write_json($path, $value, $pretty = true)
{
    $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    if ($pretty) {
        $flags |= JSON_PRETTY_PRINT;
    }
    $json = json_encode($value, $flags);
    if ($json === false) {
        throw new RuntimeException("json_encode failed for $path: " . json_last_error_msg());
    }
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($path, $json . "\n");
}

function dump_query(PDO $pdo, $sql, $path)
{
    $rows = $pdo->query($sql)->fetchAll();
    write_json($path, $rows, false);
    echo "wrote " . basename($path) . " (" . count($rows) . ")\n";
    return $rows;
}

if ($daysOnly) {
    echo "days only\n";
} else {
echo "liturgical tables\n";
$tables = array(
    "daily_office" => "SELECT do_id, do_name, do_yearNo, do_dow, do_psalms, do_ot, do_nt, do_gospel FROM daily_office",
    "collects" => "SELECT id, name, text, preface, pref_id FROM collects",
    "collects_contemporary" => "SELECT id, name, text, preface, pref_id FROM collects_contemporary",
    "collects_es" => "SELECT id, name, text, preface, pref_id FROM collects_es",
    "canticles" => "SELECT cant_id AS id, cant_title AS title, cant_latin AS latin, cant_ref AS ref, cant_instr AS instr, cant_cont AS cont FROM canticles",
    "canticles_es" => "SELECT cant_id AS id, cant_title AS title, cant_latin AS latin, cant_ref AS ref, cant_instr AS instr, cant_cont AS cont FROM canticles_es",
    "prefaces" => "SELECT * FROM prefaces",
    "prefaces_contemporary" => "SELECT * FROM prefaces_contemporary",
    "prefaces_es" => "SELECT * FROM prefaces_es",
    "prefaces_contemporary_es" => "SELECT * FROM prefaces_contemporary_es",
    "sunday_lectionary" => "SELECT sl_year_no, sl_id, sl_name, sl_psalm, sl_ot, sl_nt, sl_gospel FROM sunday_lectionary",
    "rcl_sunday_lectionary" => "SELECT sl_year_no, sl_id, sl_name, sl_psalm, sl_ot, sl_nt, sl_gospel FROM RCL_sunday_lectionary",
    "holy_days_daily_office" => "SELECT hd_do_id, hd_do_name, hd_do_date, hd_do_time, hd_do_psalm, hd_do_first, hd_do_second FROM holy_days_daily_office",
    "holy_days_lectionary" => "SELECT * FROM holy_days_lectionary",
    "rcl_holy_days_lectionary" => "SELECT * FROM RCL_holy_days_lectionary",
);
foreach ($tables as $name => $sql) {
    dump_query($pdo, $sql, "$dataDir/$name.json");
}

$books = $pdo->query(
    "SELECT book_id, MIN(book_name) AS name FROM bible WHERE version = 'KJV' GROUP BY book_id ORDER BY book_id"
)->fetchAll();
$apocrypha = $pdo->query(
    "SELECT book_id, MIN(book_name) AS name FROM bible WHERE version = 'RSV' AND book_id >= 67 GROUP BY book_id ORDER BY book_id"
)->fetchAll();
$byId = array();
foreach (array_merge($books, $apocrypha) as $row) {
    $byId[(int) $row["book_id"]] = array("id" => (int) $row["book_id"], "name" => $row["name"]);
}
ksort($byId);
write_json("$dataDir/books.json", array_values($byId));
echo "wrote books.json (" . count($byId) . ")\n";

$versions = array(
    "ASV" => "American Standard Version",
    "BBE" => "Bible in Basic English",
    "D" => "Darby Translation",
    "DR" => "Douay-Rheims Bible",
    "FD" => "French Darby Translation",
    "FLS" => "French Louis Segond",
    "KJV" => "King James Version",
    "LUT" => "German Luther Bible",
    "NKJV" => "New King James Version",
    "NRSV" => "New Revised Standard Version",
    "RSV" => "Revised Standard Version",
    "RV" => "Reina Valera",
    "RVR" => "1909 Reina Valera",
    "SEV" => "Sagradas Escrituras Version Antigua",
    "SPN" => "Spanish Modern",
    "VUL" => "Latin Vulgate",
    "W" => "Webster's Translation",
    "WEB" => "World English Bible",
    "YLT" => "Young's Literal Translation",
);
$versionRows = array();
foreach ($versions as $code => $label) {
    $versionRows[] = array("id" => $code, "name" => $label);
}
write_json("$dataDir/versions.json", $versionRows);

echo "bible chapters\n";
$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
$stmt = $pdo->query(
    "SELECT version, book_id, chapter, verse, text FROM bible ORDER BY version, book_id, chapter, verse"
);
$current = null;
$verses = array();
$chapterCount = 0;
$flush = function () use (&$current, &$verses, &$chapterCount, $bibleDir) {
    if ($current === null) {
        return;
    }
    $path = $bibleDir . "/" . $current["version"] . "/" . $current["book_id"] . "/" . $current["chapter"] . ".json";
    write_json($path, array("verses" => $verses), false);
    $chapterCount++;
    $verses = array();
};
while ($row = $stmt->fetch()) {
    $key = $row["version"] . "/" . $row["book_id"] . "/" . $row["chapter"];
    if ($current === null || $current["key"] !== $key) {
        $flush();
        $current = array(
            "key" => $key,
            "version" => $row["version"],
            "book_id" => $row["book_id"],
            "chapter" => $row["chapter"],
        );
    }
    $verses[] = array("verse" => (int) $row["verse"], "text" => rtrim($row["text"], "\r\n"));
}
$flush();
$stmt->closeCursor();
echo "wrote $chapterCount bible chapters\n";

$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$psalmStmt = $pdo->query(
    "SELECT chapter, part, verse, text, daytime, daynumber FROM bcp_psalms ORDER BY chapter, verse"
);
$current = null;
$verses = array();
$psalmChapters = 0;
$flushPsalm = function () use (&$current, &$verses, &$psalmChapters, $bibleDir) {
    if ($current === null) {
        return;
    }
    $path = $bibleDir . "/BCP/19/" . $current . ".json";
    write_json($path, array("verses" => $verses), false);
    $psalmChapters++;
    $verses = array();
};
$psalter = array();
while ($row = $psalmStmt->fetch()) {
    $chapter = (string) $row["chapter"];
    if ($current !== $chapter) {
        $flushPsalm();
        $current = $chapter;
    }
    $verses[] = array("verse" => (int) $row["verse"], "text" => rtrim($row["text"], "\r\n"));
    $day = (string) $row["daynumber"];
    $when = $row["daytime"];
    if (!isset($psalter[$day])) {
        $psalter[$day] = array("day" => (int) $day, "morning" => array(), "evening" => array());
    }
    $slot = ($when === "E") ? "evening" : "morning";
    if (!in_array((int) $row["chapter"], $psalter[$day][$slot], true)) {
        $psalter[$day][$slot][] = (int) $row["chapter"];
    }
}
$flushPsalm();
ksort($psalter);
write_json("$dataDir/psalter_index.json", array_values($psalter));
echo "wrote $psalmChapters BCP psalm chapters\n";
}

/**
 * Same date resolution as the archive branch of api/daily_office.php.
 * $kind is daily_office or lectionary. Daily office also builds DailyOffice
 * and applies the LE label and the P/T id swap.
 */
function resolve_day($kind, DateTime $timestamp, $tz = 0)
{
    $today_zday = $timestamp->format("z");
    $today_year = (int) $timestamp->format("Y");
    $today_dow = $timestamp->format("w");
    $today_month = $timestamp->format("n");
    $today_day = $timestamp->format("j");
    $cc = new \BCP\ChurchCalendar($kind, $today_year, $tz);
    $advent_year = $cc->advent_year;
    if (($cc->one_advent_zday > $today_zday) && ($cc->year > $today_year)) {
        $cc = new \BCP\ChurchCalendar($kind, $advent_year, $tz, $today_zday);
    }
    $current_sunday = date(
        "l, F-d-Y",
        mktime(0, 0, 0, $today_month, ($today_day - $today_dow), $today_year)
    );
    $cc->current_sunday = $current_sunday;
    $cc->today_dow = $today_dow;
    $cc->findCurrentSeason($current_sunday);
    $do = null;
    if ($kind === "daily_office") {
        $do = new \BCP\DailyOffice($cc);
        if (!array_key_exists($current_sunday, $cc->sundays)) {
            $cc = new \BCP\ChurchCalendar($kind, $today_year + 1, $tz);
            $do = new \BCP\DailyOffice($cc);
            foreach ($cc->sundays as $sundae => $id) {
                if ($current_sunday == $sundae) {
                    $do->do_id = $id;
                }
            }
        }
    } elseif (!array_key_exists($current_sunday, $cc->sundays)) {
        $cc = new \BCP\ChurchCalendar($kind, $today_year + 1, $tz);
    }
    return array($cc, $do, $current_sunday, $today_dow);
}

function week_after_rules($do, $cc)
{
    $names = sunday_details_for("EN");
    $namesEs = sunday_details_for("ES");
    $id = isset($do->do_id) ? (string) $do->do_id : "";
    // The API assigns the week from this table and leaves it null when the
    // id is absent (for example "1-2").
    $week = isset($names[$id]) ? $names[$id] : null;
    $weekEs = isset($namesEs[$id]) ? $namesEs[$id] : null;
    if ($do->do_id == "LE") {
        if ($do->do_dow == 3) {
            $week = array("color" => "purple", "name" => "Ash Wednesday", "id" => "AW");
        }
        if ($do->do_dow > 3) {
            $week = array("color" => "purple", "name" => "Season of Lent", "id" => "L");
        }
    } elseif ($do->do_id == "P" || $do->do_id == "T") {
        if ($do->do_id == "P") {
            $do->do_id = $cc->pentecost_proper;
        }
        if ($do->do_id == "T") {
            $do->do_id = $cc->trinity_proper;
        }
    }
    $do->do_id = isset($do->do_id) ? (string) $do->do_id : "";
    $dow = $do->do_dow;
    if ($dow === "null" || $dow === null || $dow === "" || !is_numeric($dow)) {
        $dow = null;
    } else {
        $dow = (string) $dow;
    }
    return array($week, $weekEs, $dow);
}

echo "days 2024-01-01 through 2032-12-31\n";
$start = new DateTime("2024-01-01");
$end = new DateTime("2032-12-31");
$days = array();
$cursor = clone $start;
$count = 0;
while ($cursor <= $end) {
    list($cc, $do, $sunday, $dow) = resolve_day("daily_office", $cursor, 0);
    list($week, $weekEs, $officeDow) = week_after_rules($do, $cc);
    list($lcc, $ignored, $lsunday) = resolve_day("lectionary", $cursor, 0);
    $lectionaryId = isset($lcc->sundays[$lsunday]) ? (string) $lcc->sundays[$lsunday] : null;
    $sundayId = isset($cc->sundays[$sunday]) ? (string) $cc->sundays[$sunday] : null;
    $days[] = array(
        "date" => $cursor->format("Y-m-d"),
        "y" => $cursor->format("Y"),
        "m" => $cursor->format("m"),
        "d" => $cursor->format("d"),
        "dow" => (int) $dow,
        "title" => $cursor->format("l, F j, Y"),
        "sunday" => $sunday,
        "sundayId" => $sundayId,
        "season" => $cc->current_season,
        "lectionaryYear" => $cc->lectionary_year,
        "lectionaryId" => $lectionaryId,
        "office" => array(
            "year" => (string) $do->do_yearNo,
            "id" => (string) $do->do_id,
            "dow" => $officeDow,
        ),
        "week" => $week,
        "weekEs" => $weekEs,
    );
    $count++;
    if ($count % 500 === 0) {
        echo "  $count\n";
    }
    $cursor->modify("+1 day");
}
write_json("$dataDir/days.json", $days, false);
echo "wrote days.json ($count)\n";
$owner = fileowner("/var/www/html/index.php");
$group = filegroup("/var/www/html/index.php");
if ($owner !== false) {
    $paths = $daysOnly ? $dataDir : "$dataDir $bibleDir";
    exec("chown -R " . (int) $owner . ":" . (int) $group . " $paths");
}
echo "done\n";
