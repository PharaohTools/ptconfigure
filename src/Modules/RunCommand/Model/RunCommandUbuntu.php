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
        if (isset($this->params["install-user-name"]))  {
            $commandRay[] = "su ".$this->params["install-user-name"] ; }
        if (isset($this->params["background"]))  {
            $commandRay[] = $this->params["command"].' &' ; }
        else  {
            $commandRay[] = $this->params["command"] ; }
        return $commandRay ;
    }

    public function askForUserName() {
        $question = "Enter User to run as:";
        $input = (isset($this->params["run-as-user"])) ? $this->params["run-as-user"] : self::askForInput($question);
        $this->username = $input ;
    }

    public function askForCommand() {
        $question = "Enter Command to run:";
        $input = (isset($this->params["command"])) ? $this->params["command"] : self::askForInput($question);
        $this->username = $input ;
    }

    public function askForBackground() {
        $question = "Run in Background?";
        $input = (isset($this->params["background"])) ? true : self::askYesOrNo($question);
        $this->background = $input ;
    }

}