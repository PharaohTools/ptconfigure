<?php

/**
 * Pharaoh Tools Constants
 */

define('PHARAOH_APP', "dapperstrano") ;

if (in_array(PHP_OS, array("Windows", "WINNT"))) {
    $sd = getenv('SystemDrive') ;
    $pf = getenv('ProgramFiles') ;
    $pf = str_replace(" (x86)", "", $pf) ;
    $command = "where /R \"{$pf}\" *VBoxManage* " ;
    $outputArray = array();
    exec($command, $outputArray);
    define('VBOXMGCOMM', "\"{$outputArray[0]}\" ") ;
    define('PFILESDIR', $sd."\\PharaohTools\\") ;
    define('CLEOCOMM', '"'.PFILESDIR.'cleopatra.cmd"') ;
    define('DAPPCOMM', '"'.PFILESDIR."dapperstrano.cmd") ;
    define('PHLCOMM', '"'.PFILESDIR."phlagrant.cmd") ;
    define("DS", "\\");
    define("BASE_TEMP_DIR", getenv("SystemDrive").'\Temp\\'); }
else if (in_array(PHP_OS, array("Linux", "Solaris", "FreeBSD", "OpenBSD", "Darwin"))) {
    define('VBOXMGCOMM', "vboxmanage ") ;
    define('PFILESDIR', "/opt/") ;
    define('CLEOCOMM', "cleopatra ") ;
    define('DAPPCOMM', "dapperstrano ") ;
    define('PHLCOMM', "phlagrant") ;
    define("DS", "/");
    define("BASE_TEMP_DIR", '/tmp/'); }