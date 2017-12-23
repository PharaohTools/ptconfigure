<?php

Namespace Model;

class RunCommandWindows extends BaseWindowsApp {

    // Compatibility
    public $os = array("Windows", 'WINNT') ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $runUser ;
    protected $command ;
    protected $background ;
    protected $nohup ;


    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "RunCommand";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForUserName", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "askForCommand", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "askForBackground", "params" => array() ) ) ,
//            array("method"=> array("object" => $this, "method" => "askForNohup", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "runCommand", "params" => array()) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "";
        $this->programNameMachine = "runcommand"; // command and app dir name
        $this->programNameFriendly = "Run Command"; // 12 chars
        $this->programNameInstaller = "Run a Command";
        $this->initialize();
    }

    protected function runCommand() {
        $commandRay = array() ;
        $commandRay[] = "cd ".getcwd() ;
        if (isset($this->runUser) && !is_null($this->runUser))  {
            $commandRay[] = "su  ".$this->runUser ; }
//        if (isset($this->nohup) && strlen($this->nohup)>0)  {
//            $this->command = "nohup ".$this->command ; }
        if (isset($this->background) && !is_null($this->background))  {
            $commandRay[] = $this->command.' &' ; }
        else  {
            $commandRay[] = $this->command ; }
        if (isset($this->runUser) && !is_null($this->runUser))  {
            $commandRay[] = "exit" ; }
        // @todo only show this under verbose output
//        foreach ($commandRay as $command) { echo $command."\n" ; }
        $rc = $this->executeAsShell($commandRay) ;
//        var_dump('cray: ', $commandRay) ;
        if ($rc == 0) { return true; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Run Command failed", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
        return false;
    }

    public function askForUserName() {
        if (isset($this->params["run-as-user"])) {
            if (isset($this->params["run-as-user"]) && strlen($this->params["run-as-user"])>0) {
                $this->runUser = $this->params["run-as-user"] ; }
            else if (isset($this->params["run-as-user"]) && strlen($this->params["run-as-user"])==0) {
                $this->runUser = null ; }
            else {
                $this->runUser = null ; } }
    }

    public function askForCommand() {
        $question = "Enter Command to run:";
        $this->command = (isset($this->params["command"])) ? $this->params["command"] : self::askForInput($question);
    }

    public function askExec() {
        return $this->askInstall() ;
    }

    public function askForNohup() {
        if ( (isset($this->params["nohup"]) && $this->params["nohup"]===false) ||
            (isset($this->params["nohup"]) && $this->params["nohup"]=="false")) {
            $useNoHup = false ;
            $this->nohup = $useNoHup ;
            return ; }
        if (isset($this->params["nohup"]) && $this->params["nohup"]===true) {
            $useNoHup = (strlen($this->params["nohup"]) > 0) ? true : false ;
            $this->nohup = $useNoHup ;
            return ; }
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->nohup = false ;
            return ; }
        $question = "Use NoHup?:";
        $this->nohup = self::askYesOrNo($question);
    }

    public function askForBackground() {
        if (isset($this->params["background"]) && strlen($this->params["background"])>0) {
            $this->background = true ; }
        else if (isset($this->params["background"]) && $this->params["background"]===true) {
            $this->background = true ; }
        else if (isset($this->params["background"]) && strlen($this->params["background"])==0) {
            $this->background = null ; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->background = null ;
            return ; }
        else {
            $this->background = null; }
    }

}