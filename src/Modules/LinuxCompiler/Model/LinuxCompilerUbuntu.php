<?php

Namespace Model;

class LinuxCompilerUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "LinuxCompiler";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "c++", "build-essential", "make")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "c++", "build-essential", "make")) ),
        );
        $this->programDataFolder = "/opt/LinuxCompiler"; // command and app dir name
        $this->programNameMachine = "ubuntucompiler"; // command and app dir name
        $this->programNameFriendly = "Linux Comp!"; // 12 chars
        $this->programNameInstaller = "Linux Compiler";
        $this->initialize();
    }

    public function askStatus() {
        $stat1 = $this->askStatusByArray( array("make") ) ;
        $aptFactory = new Apt();
        $aptModel = $aptFactory->getModel($this->params) ;
        $stat2 = $aptModel->isInstalled("build-essential") ;
        return ($stat1==true && $stat2==true) ? true : false ;
    }

    public function askWhetherToInstallFromArchive($pageVars){
        return $this->installFromArchive($pageVars);
    }

    public function askWhetherToInstallFromDirectory($pageVars){
        return $this->installFromDirectory($pageVars);
    }

    private function installFromArchive() {

        $archive = $this->params["archive"] ;

        var_dump(strrpos($archive, 'tgz')) ;
        die();

        if (strrpos($archive, 'tgz') === '0') {

        }

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