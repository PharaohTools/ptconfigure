<?php

Namespace Core ;

use Model\Base;

class AutoPilot extends Base {

    public $params ;
    protected $appHomeDir ;
    protected $myUser ;

    public function __construct() {
        $this->setProperties();
    }

    protected function setProperties() {
        $this->appHomeDir = dirname(dirname(dirname(__FILE__))) ;
        $this->myUser = self::executeAndLoad("whoami") ;
    }

}
