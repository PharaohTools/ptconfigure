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

    protected $runUser ;
    protected $command ;
    protected $background ;


    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "RunCommand";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForUserName", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "askForCommand", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "askForBackground", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "setRunCommand", "params" => array() ) ) ,
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "";
        $this->programNameMachine = "runcommand"; // command and app dir name
        $this->programNameFriendly = "Run Command"; // 12 chars
        $this->programNameInstaller = "Run a Command";
        $this->initialize();
    }

    public function setRunCommand() {
        $this->installCommands[] = array("command" => $this->getCommandToRun() ) ;
    }

    protected function getCommandToRun() {
        $commandRay = array() ;
        if (isset($this->runUser))  {
            $commandRay[] = "su ".$this->runUser ; }
        if (isset($this->background))  {
            $commandRay[] = $this->command.' &' ; }
        else  {
            $commandRay[] = $this->command ; }
        return $commandRay ;
    }

    public function askForUserName() {
        $question = "Enter User to run as:";
        $this->runUser = (isset($this->params["run-as-user"])) ? $this->params["run-as-user"] : self::askForInput($question);
    }

    public function askForCommand() {
        $question = "Enter Command to run:";
        $this->command = (isset($this->params["command"])) ? $this->params["command"] : self::askForInput($question);
    }

    public function askForBackground() {
        $question = "Run in Background?";
        $this->background = (isset($this->params["background"])) ? true : self::askYesOrNo($question);
    }

}