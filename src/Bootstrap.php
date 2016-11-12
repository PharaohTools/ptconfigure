<?php

require_once("BootstrapCore.php") ;
$bootStrap = new \Core\BootStrap();
$argv_or_null = (isset($argv)) ? $argv : null ;
$bootStrapParams = (isset($_ENV['ptconfigure_bootstrap'])) ? unserialize($_ENV['ptconfigure_bootstrap']) : $argv_or_null ;
$bootStrap->main($bootStrapParams);