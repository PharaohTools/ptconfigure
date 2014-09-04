<?php

/**
 * Pharaoh Tools Constants
 */

if (PHP_OS == "Windows") {
    define('DS', "\\");
    define('BASE_TEMP_DIR', 'C:\\tmp\\'); }
else if (in_array(PHP_OS, array("Linux", "Solaris", "FreeBSD", "OpenBSD", "Darwin"))) {
    define('DS', "/");
    define('BASE_TEMP_DIR', '/tmp/'); }