<?php

Namespace Model;

class AutopilotAllLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->addAliasParams() ;
    }

    protected function addAliasParams() {
        if (isset($this->params["af"])) {
            $this->params["autopilot-file"] = $this->params["af"] ; }
        if (isset($this->params["auto"])) {
            $this->params["autopilot-file"] = $this->params["auto"] ; }
    }

}