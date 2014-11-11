<?php

/**
 * Pharaoh Tools Constants
 */

if (in_array(PHP_OS, array("Windows", "WINNT"))) {
    $pf = getenv('ProgramFiles') ;
    $pf = str_replace(" (x86)", "", $pf) ;
    $command = "where /R \"{$pf}\" *VBoxManage* " ;
    $outputArray = array();
    exec($command, $outputArray);
    define('VBOXMGCOMM', "\"{$outputArray[0]}\" ") ;
    define('PFILESDIR', $pf) ;
    define('CLEOCOMM', "cleopatra.cmd") ;
    define('DAPPCOMM', "dapperstrano.cmd") ;
    define('PHLCOMM', "phlagrant.cmd") ;
    define('BOXDIR', 'C:\\PharaohTools\boxes') ;
    define("DS", "\\");
    define("BASE_TEMP_DIR", getenv("SystemDrive").'\Temp\\'); }
else if (in_array(PHP_OS, array("Linux", "Solaris", "FreeBSD", "OpenBSD", "Darwin"))) {
    define('VBOXMGCOMM', "vboxmanage ") ;
    define('PFILESDIR', "/opt/") ;
    define('CLEOCOMM', "cleopatra ") ;
    define('DAPPCOMM', "dapperstrano ") ;
    define('PHLCOMM', "phlagrant") ;
    define('BOXDIR', DS.'phlagrant/boxes'.'\\') ;
    define("DS", "/");
    define("BASE_TEMP_DIR", '/tmp/'); }