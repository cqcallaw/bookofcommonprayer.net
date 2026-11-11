<?php
// GET THE HEADER AND FOOTER FILES
header("HTTP/1.0 404 Not Found");
$this->qsHeader = THEME_DIR.$this->qsTheme."header.php";
$this->qsFooter = THEME_DIR.$this->qsTheme."footer.php";
// PROCESS THE CONTENT
$this->qsContent = self::processView(VIEWS.$this->qsController.".view.php");
    echo self::processView($this->qsHeader);
    echo $this->qsContent;
    echo self::processView($this->qsFooter);
