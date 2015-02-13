<?php

Namespace Info;

class PTConfigureBase extends Base {

    public $hidden ;

    public $name ;

    public function __construct() {
    }

    public function routesAvailable() {
      return array("help", "status", "install", "ensure", "uninstall", "version", "run-at-reboots");
    }

}