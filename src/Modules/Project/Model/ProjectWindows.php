<?php

Namespace Model;

class ProjectWindows extends ProjectLinuxMac  {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected function projectContainerInitialize() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params) ;
        $command = 'mkdir '.$this->projectContainerDirectory;
        self::executeAndOutput($command, "Project Container directory created");
        $logging->log("Moving to Container {$this->projectContainerDirectory}", $this->getModuleName());
//        $command = 'pwd '.$this->projectContainerDirectory;
//        self::executeAndOutput($command, "Showing Container Directory");
        file_put_contents('dhprojc', "");
        $logging->log("Project Container file created", $this->getModuleName());
    }

}