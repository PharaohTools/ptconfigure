<?php

Namespace Core ;

use Model\Base;

class AutoPilot extends Base {

    public $params ;
    protected $appHomeDir ;

    public function __construct($params = array()) {
        global $argv ;
        $argv_or_array = (isset($argv)) ? $argv : array() ;
        $argv_and_params = array_merge($argv_or_array, $params) ;
        parent::__construct($argv_and_params);
        $this->setProperties();
    }

    protected function setProperties() {
        $this->appHomeDir = dirname(dirname(dirname(__FILE__))) ;
    }

}
