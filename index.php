<?php

use application\AppStart;

/**
 * Načtení potřebných souborů a knihoven
 */

require_once("autoloader.php");
require_once("settings.php");
require_once("composer/vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php");

$app = new AppStart();
$app->appStart();