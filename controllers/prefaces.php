<?php
// DEFINE OUR HEADER AND FOOTER FILES
$this->setHF("header-content.php", true);
$this->setHF("footer.php");
// CREATE A NEW COLLECT OBJECT AND TELL IT WE WANT CONTEMPORARY COLLECTS
$db = new \QuickShell\DML\Database();
$which = ( isset($this->qsMethod) && ($this->qsMethod == "trad") ) ? "T":"C";
// PASS VARIABLE TO VIEW
$this->which_collect = $which;
// GET THE COLLECTS FROM DATABASE
// GET A NEW PREFACE OBJECT
$p = new \BCP\Prefaces($which, $db);
$pList = json_decode($p->prefaceList);
$this->prefaces = $p->processPrefs($pList);
