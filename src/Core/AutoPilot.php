<?php

Namespace Core ;

class AutoPilot {

    protected $appHomeDir ;

    public function __construct() {
        $this->setProperties();
    }

    protected function setProperties() {
        $this->appHomeDir = dirname(dirname(dirname(__FILE__))) ;
    }

}
