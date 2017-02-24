<?php

Namespace Model;

class LinuxCompilerCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("Centos", "Redhat") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "LinuxCompiler";
        $this->installCommands = array(
            array("method"=> array(
                "object" => $this, "method" => "packageAdd", "params" => array("Yum", array("gcc", "c++", "cmake", "make"))
            ) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array(
                "object" => $this, "method" => "packageRemove", "params" => array("Yum", array("gcc", "c++", "cmake", "make"))
            ) ),
        );
        $this->programDataFolder = "/opt/LinuxCompiler"; // command and app dir name
        $this->programNameMachine = "linuxcompiler"; // command and app dir name
        $this->programNameFriendly = "Linux Comp!"; // 12 chars
        $this->programNameInstaller = "Linux Compiler";
        $this->initialize();
    }

    public function askStatus() {
        $stat1 = $this->askStatusByArray( array("gcc", "c++", "cmake", "make") ) ;
        return ($stat1==true) ? true : false ;
    }

    public function askWhetherToInstallFromArchive($pageVars){
        return $this->installFromArchive($pageVars);
    }

    public function askWhetherToInstallFromDirectory($pageVars){
        return $this->installFromDirectory($pageVars);
    }

    private function installFromArchive() {
        if (isset($this->params["output-file"]) && $this->params["output-file"] != "") {
            $this->finishedOutputFile = $this->params["output-file"]; }
        if (isset($this->params["command-to-execute"]) && $this->params["command-to-execute"] != "") {
            $this->commandData = $this->params["command-to-execute"]; }
        else {
            $commandEntry = $this->askForWhetherToExecuteCommandToScreen();
            if (!$commandEntry) {
                return false; }
            $this->commandData = $this->askForCommand();
            $this->checkCommandOkay(); }
        $commandOutputFilePath = $this->spawnCommand();
        return $commandOutputFilePath ;
    }


    private function installFromDirectory($pageVars) {
        $directory = $this->params["directory"] ;
        $command = array (
            "cd $directory",
            "$directory/configure",
            "make",
            "make install"
        );
        $rc = self::executeAndGetReturnCode($command, true, true) ;
        return ($rc["rc"] === 0) ? true : false ;
    }

}