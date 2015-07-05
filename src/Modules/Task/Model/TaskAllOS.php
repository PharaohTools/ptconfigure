<?php

Namespace Model;

class TaskAllOS extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $environmentName ;
    protected $providerName ;
    protected $boxAmount ;
    protected $requestingModule ;
    protected $actionsToMethods ;

    public function __construct($params) {
        parent::__construct($params);
        $this->programNameMachine = "task"; // command and app dir name
        $this->programNameFriendly = "Task!"; // 12 chars
        $this->programNameInstaller = "Easily Executable Custom Tasks";
        $this->initialize();
    }

}