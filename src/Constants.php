<?php

/**
 * Pharaoh Tools Constants
 */

define('PHARAOH_APP', "ptconfigure") ;

if (in_array(PHP_OS, array("Windows", "WINNT"))) {
    $sd = getenv('SystemDrive') ;
    $pf = getenv('ProgramFiles') ;
    $pf = str_replace(" (x86)", "", $pf) ;
    $command = "where /R \"{$pf}\" *VBoxManage* " ;
    $outputArray = array();
    exec($command, $outputArray);
    define('SUDOPREFIX', "");
    define('VBOXMGCOMM', "\"{$outputArray[0]}\" ") ;
    define('PFILESDIR', $sd."\\PharaohTools\\") ;
    define('PTCCOMM', PFILESDIR.'ptconfigureent.cmd"') ;
    define('PTBCOMM', PFILESDIR.'ptbuildent.cmd"') ;
    define('PTDCOMM',  PFILESDIR."ptdeployent.cmd") ;
    define('PTVCOMM',  PFILESDIR."ptvirtualizeent.cmd") ;
    define('BOXDIR', $sd.'\\PharaohTools\boxes') ;
    define("DS", "\\");
    define("BASE_TEMP_DIR", getenv("SystemDrive").'\Temp\\'); }
else if (in_array(PHP_OS, array("Linux", "Solaris", "FreeBSD", "OpenBSD", "Darwin"))) {
    $uname = exec('whoami');
    $isAdmin = ($uname == "root") ? true : false ;
    if ($isAdmin == true) { define('SUDOPREFIX', ""); }
    else { define('SUDOPREFIX', "sudo "); }
    define('VBOXMGCOMM', "vboxmanage ") ;
    define('PFILESDIR', "/opt/") ;
    define('PTCCOMM', "ptconfigureent ") ;
    define('PTBCOMM', "ptbuildent ") ;
    define('PTDCOMM', "ptdeployent ") ;
    define('PTVCOMM', "ptvirtualizeent") ;
    define("DS", "/");
    define("BASE_TEMP_DIR", '/tmp/');
    define('BOXDIR', DS.'ptvirtualize/boxes'.'\\') ; }