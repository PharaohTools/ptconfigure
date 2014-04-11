<?php

Namespace Model;

class RunCommandUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $username ;
    protected $command ;
    protected $background ;


    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "RunCommand";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForUserName", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "askForCommand", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "askForBackground", "params" => array() ) ) ,
            array("command" => $this->getCommandToRun() )
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "";
        $this->programNameMachine = "runcommand"; // command and app dir name
        $this->programNameFriendly = "Run Command"; // 12 chars
        $this->programNameInstaller = "Run a Command";
        $this->initialize();
    }

    private function getCommandToRun() {
        $commandRay = array() ;
        if (isset($this->params["run-as-user"]))  {
            $commandRay[] = "su ".$this->params["run-as-user"] ; }
        if (isset($this->params["background"]))  {
            $commandRay[] = $this->params["command"].' &' ; }
        else  {
            $commandRay[] = $this->params["command"] ; }
        return $commandRay ;
    }

    public function askForUserName() {
        $question = "Enter User to run as:";
        $this->params["run-as-user"] = (isset($this->params["run-as-user"])) ? $this->params["run-as-user"] : self::askForInput($question);
    }

    public function askForCommand() {
        $question = "Enter Command to run:";
        $this->params["command"] = (isset($this->params["command"])) ? $this->params["command"] : self::askForInput($question);
    }

    public function askForBackground() {
        $question = "Run in Background?";
        $this->params["background"] = (isset($this->params["background"])) ? true : self::askYesOrNo($question);
    }

}