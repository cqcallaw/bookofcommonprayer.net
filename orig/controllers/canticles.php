<?php
// DEFINE OUR HEADER AND FOOTER FILES
$this->setHF("header-content.php", true);
$this->setHF("footer.php");
// CREATE A NEW COLLECT OBJECT
$db = new \QuickShell\DML\Database();
// GET THE COLLECTS FROM DATABASE
$c = new \BCP\Canticles($db);
$this->canticles = $c->canticleList;
