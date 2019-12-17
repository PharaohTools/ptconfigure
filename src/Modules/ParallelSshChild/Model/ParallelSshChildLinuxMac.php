<?php

Namespace Model;

class ParallelSshChildLinuxMac extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $commandData;
    private $inputSshFile;
    private $finishedOutputFile;

    public function runAutoPilot($autoPilot){
        $auto1 = $this->runAutoPilotParallelSshChildCommand($autoPilot);
        return  ( $auto1==true ) ? true : false ;
    }

    public function askWhetherToDoParallelSshChildCommand($pageVars){
        $this->setCmdLineParams($pageVars["route"]["extraParams"]);
        return $this->performParallelSshChildCommand();
    }

    private function performParallelSshChildCommand() {
        if (isset($this->params["input-ssh-file"]) && $this->params["input-ssh-file"] != "") {
            $this->inputSshFile = $this->params["input-ssh-file"];
            $srcFolder =  str_replace("/Model", "", dirname(__FILE__) ) ;
            $ssh2File = $srcFolder.'/Libraries/seclib/Net/SSH2.php' ;
            require_once($ssh2File) ;
            $cryptRsaFile = $srcFolder."/Crypt/RSA.php" ;
            require_once($cryptRsaFile) ;
            $sshObject = unserialize(file_get_contents($this->inputSshFile)); }
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
        var_dump("is ob: ", is_object($sshObject["ssh2Object"])) ;
        var_dump("me: ", method_exists($sshObject["ssh2Object"], "write")) ;
        ob_start();
        var_dump($sshObject) ;
        file_put_contents("/tmp/daves-output", ob_get_clean() ) ;
        $commandOutputFilePath = $this->spawnCommand($sshObject["ssh2Object"], $this->commandData);
        return $commandOutputFilePath ;
    }

//    public function runAutoPilotParallelSshChildCommand($autoPilot){
//        $commandExecution =
//        (isset($autoPilot["commandExecExecute"]) && $autoPilot["commandExecExecute"]==true)
//          ? true : false;
//        if (!$commandExecution) { return false; }
//        $this->commandData = $autoPilot["commandData"];
//        $commandOutputFilePath = $this->spawnCommand($sshObject, $this->commandData);
//        return $commandOutputFilePath ;
//    }

    private function askForWhetherToExecuteCommandToScreen(){
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

    private function spawnCommand($sshObject, $command, $first=null) {
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

        file_put_contents ($tempOutputFile, $this->doSSHCommand( $sshObject, $command, null ) );

        file_put_contents($finishedOutputFile, "COMMAND: ".$command."\n");
        file_put_contents($finishedOutputFile, "COMPLETE: 1\n", FILE_APPEND);
        file_put_contents($finishedOutputFile, "OUTPUT: ".file_get_contents($tempOutputFile)."\n\n", FILE_APPEND);
        unlink($tempOutputFile);
        return $finishedOutputFile ;
    }

    private function getFileToWrite($file_type) {
        $random = self::$tempDir.DIRECTORY_SEPARATOR.mt_rand(100, 99999999999);
        if ($file_type == "temp") { return $random.'temp.txt'; }
        if ($file_type == "final") { return $random.'final.txt'; }
        else { return null ; }
    }


    private function doSSHCommand( $sshObject, $command, $first=null ) {
        $returnVar = ($first==null) ? "" : $sshObject->read("DAPPERSTRANOPROMPT") ;
        $sshObject->write("$command\n") ;
        $returnVar .= $sshObject->read("DAPPERSTRANOPROMPT") ;
        return str_replace("DAPPERSTRANOPROMPT", "", $returnVar) ;
    }

}