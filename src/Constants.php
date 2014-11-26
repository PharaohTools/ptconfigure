<?php

/**
 * Pharaoh Tools Constants
 */

define('PHARAOH_APP', "dapperstrano") ;

if (in_array(PHP_OS, array("Windows", "WINNT"))) {
    define("DS", "\\");
    define("BASE_TEMP_DIR", getenv("SystemDrive").'\Temp\\'); }
else if (in_array(PHP_OS, array("Linux", "Solaris", "FreeBSD", "OpenBSD", "Darwin"))) {
    define("DS", "/");
    define("BASE_TEMP_DIR", '/tmp/'); }