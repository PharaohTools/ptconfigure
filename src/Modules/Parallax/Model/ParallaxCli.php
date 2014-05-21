<?php

Namespace Model;

class ParallaxCli extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $arrayOfCommands = array();
    private $commandResults = array();

    public function runAutoPilot($autoPilot){
      return $this->runAutopilotParallelCommand($autoPilot);
    }

    public function askWhetherToRunParallelCommand() {
      $doRunParallel = $this->askToScreenWhetherToRunParallelCommand();
      if ($doRunParallel != true) {
        return false ; }
      $this->askForAllCommands() ;
      return $this->executeAllCommandInput() ;
    }

    public function runAutopilotParallelCommand($autoPilot) {
      if ( !isset($autoPilot["cliExecute"]) || $autoPilot["cliExecute"] !== true ) { return false; }
      $this->askForAllCommands($autoPilot) ;
      return $this->executeAllCommandInput() ;
    }

    public function askToScreenWhetherToRunParallelCommand() {
        $question = 'Run Commands in Parallel?';
        return self::askYesOrNo($question, true);
    }

    public function askForAllCommands($autoPilot = null) {
        if ( isset($autoPilot["cliCommands"]) ) {
            $this->arrayOfCommands = $autoPilot["cliCommands"]; }
        else {
          $commandInput = "anything";
          while ($commandInput != "") {
            $question = "Enter Command to include next. Enter none to end." ;
            $commandInput = self::askForInput($question) ;
            if ($commandInput != "") {
              $this->arrayOfCommands[] = $commandInput ; } }
        }
    }

    private function executeAllCommandInput() {
      $allPlxOuts = array();
      foreach ($this->arrayOfCommands as $command) {
        $tempScript = $this->makeCommandFile($command);
        $outfile = $this->getFileToWrite("final");
        $cmd = 'parallax cx execute --command-to-execute="sh '.$tempScript.'" --output-file="'.$outfile.'" > /dev/null &';
        system($cmd, $plxExit);
        $allPlxOuts[] = array($tempScript, $outfile); }
      $copyPlxOuts = $allPlxOuts;
      $fileData = "";
      $ignores = array();
      sleep(3);

      while (count($this->commandResults) < count($allPlxOuts)) {
        for ($i=0; $i<count($copyPlxOuts); $i++) {
          if (in_array($i, $ignores)) {
              continue; }
          $fileToScan = $copyPlxOuts[$i][1];
          $file = new \SplFileObject($fileToScan);
          $file->seek(1);
          $completionStatus = substr($file->current(), 10, 1);
          if ($completionStatus=="1") {
            $file->seek(0);
            echo "Completed task: ".substr($file->current(), 9);
            $file->seek(2);
            $exitStatus = substr($file->current(), 13, 1);
            $this->commandResults[] = $exitStatus;
            $fileData .= file_get_contents($fileToScan);
            $ignores[] = $i; }}
        echo ".";
        sleep(3); }
        $anyFailures = in_array("1", $this->commandResults);
        return array ($fileData, $anyFailures);
    }

    private function makeCommandFile($command) {
        $random = $this->baseTempDir.DIRECTORY_SEPARATOR.mt_rand(100, 99999999999);
        file_put_contents($random.'-parallax-temp.sh', $command);
        return $random.'-parallax-temp.sh';
    }

    private function getFileToWrite($file_type) {
      $random = $this->baseTempDir.DIRECTORY_SEPARATOR.mt_rand(100, 99999999999);
      if ($file_type == "temp") { return $random.'temp.txt'; }
      if ($file_type == "final") { return $random.'final.txt'; }
      else { return null ; }
    }

}