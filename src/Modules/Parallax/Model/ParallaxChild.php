<?php

Namespace Model;

class ParallaxChild extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Child") ;

    private $commandData;
    private $finishedOutputFile;

    public function __construct($params) {
        parent::__construct($params) ;
    }

    public function askWhetherToDoCommandExecution($pageVars){
        $this->setCmdLineParams($pageVars["route"]["extraParams"]);
        return $this->performCommandExecution();
    }

    private function performCommandExecution() {
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

    private function askForWhetherToExecuteCommandToScreen(){
        if (isset($this->params["yes"])) { return true; }
        $question = 'Do you want to execute a command?';
        return self::askYesOrNo($question);
    }

    private function askForCommand(){
        $question = 'What Command would you like to execute?';
        return self::askForInput($question, true);
    }

    private function checkCommandOkay(){
        $question = 'Please check command: '.$this->commandData."\n\nIs this Okay? ";
        return self::askYesOrNo($question);
    }

    private function spawnCommand() {
        // // create a temp file for program raw output
        // // create a temp file for parallax formatted output (with complete: 0 and exit: <blank> as first two lines)
        // // execute the command, on completion, re open the formatted file and place raw output in it from line 3 onwards
        // // change the value of exit in formatted file to whatever exit exec gives us
        // // change the value of complete to 1
        // // destroy the raw output file
        // show the filename of the processed file as program output,
        // exit parallax
        $tempOutputFile = $this->getFileToWrite("temp");
        $finishedOutputFile = $this->finishedOutputFile;
        file_put_contents($finishedOutputFile, "COMMAND: ".$this->commandData."\n");
        file_put_contents($finishedOutputFile, "COMPLETE: 0\n", FILE_APPEND);
        file_put_contents($finishedOutputFile, "EXIT_STATUS: \n", FILE_APPEND);
        system($this->commandData .' > '.$tempOutputFile, $exit_status);
        // @todo whats this this is for turning commands into shell, used in cli model
        if (substr($this->commandData, 0, 2)=="sh") {
            $actual_command = file_get_contents(substr($this->commandData, 3)); }
        else {
            $actual_command = $this->commandData;}
        file_put_contents($finishedOutputFile, "COMMAND: ".$actual_command."\n");
        file_put_contents($finishedOutputFile, "COMPLETE: 1\n", FILE_APPEND);
        file_put_contents($finishedOutputFile, "EXIT_STATUS: $exit_status\n", FILE_APPEND);
        file_put_contents($finishedOutputFile, "OUTPUT: ".file_get_contents($tempOutputFile)."\n\n", FILE_APPEND);
        unlink($tempOutputFile);
        return $finishedOutputFile ;
    }

    private function getFileToWrite($file_type) {
        $random = BASE_TEMP_DIR.mt_rand(100, 99999999999);
        if ($file_type == "temp") { return $random.'temp.txt'; }
        if ($file_type == "final") { return $random.'final.txt'; }
        else { return null ; }
    }
}