<?php
require_once("BootstrapCore.php") ;
$bootStrap = new \Core\BootStrap();
$argv_or_null = (isset($argv)) ? $argv : null ;
// @todo document this feature that allows a user to provide a json envoronment variable of parameters
$bootStrapParams = (isset($_ENV['ptdeploy_bootstrap'])) ? json_decode($_ENV['ptdeploy_bootstrap']) : $argv_or_null ;
$bootStrap->setExitCode(0) ;
$bootStrap->main($bootStrapParams);