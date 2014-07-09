<?php

Namespace Core ;

class AutoPilot extends \Model\Base{

    public $params ;
    protected $appHomeDir ;

    public function __construct($params = null) {
        parent::__construct($params);
        $this->setProperties();
    }

    protected function setProperties() {
        $this->appHomeDir = dirname(dirname(dirname(__FILE__))) ;
    }

}
