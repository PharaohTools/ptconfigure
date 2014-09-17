<?php

/**
 * Pharaoh Tools Constants
 */

if (PHP_OS == "Windows") {
    define("DS", "\\");
    echo "is ds defined ".defined("DS") ;
    define("BASE_TEMP_DIR", getenv("SystemDrive").'\Temp\\'); }
else if (in_array(PHP_OS, array("Linux", "Solaris", "FreeBSD", "OpenBSD", "Darwin"))) {
    define("DS", "/");
    echo "is ds defined ".defined("DS") ;
    define("BASE_TEMP_DIR", '/tmp/'); }