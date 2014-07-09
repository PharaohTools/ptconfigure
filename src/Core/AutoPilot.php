<?php

Namespace Core ;

use Model\Base;

class AutoPilot {

    public $params ;
    protected $appHomeDir ;

    public function __construct() {
        $this->setProperties();
    }

    protected function setProperties() {
        $this->appHomeDir = dirname(dirname(dirname(__FILE__))) ;
    }

}
