<?php
// DEFINE OUR HEADER AND FOOTER FILES
$this->setHF("header-content.php", true);
$this->setHF("footer.php");
// CREATE A NEW COLLECT OBJECT
$db = new \QuickShell\DML\Database();
$which = ( isset($this->qsMethod) && ($this->qsMethod == "trad") ) ? "T":"C";
// PASS VARIABLE TO VIEW
$this->which_collect = $which;
// GET THE COLLECTS FROM DATABASE
$c = new \BCP\Collects($which, $db);
$cList = json_decode($c->collectList);
$this->collects = $c->processMultiArray($cList);
