<?php

Namespace Model;

class CukeConf extends Base {

    private $cukeFileData;

    public function runAutoPilot($autoPilot){
        $this->runAutoPilotAddition($autoPilot);
        $this->runAutoPilotDeletion($autoPilot);
        return true;
    }

    public function askWhetherToCreateCuke(){
        return $this->performCukeConfigure();
    }

    public function askWhetherToResetCuke(){
        return $this->performCukeReset();
    }

    private function performCukeConfigure(){
        $cukeFileEntry = $this->askForCukeModToScreen();
        if (!$cukeFileEntry) { return false; }
        if ( !$this->checkIsDHProject() ) {
            return "You don't appear to be in a dapperstrano project. Try: \ndapperstrano proj init\n"; }
        $uri = $this->askForCukefileUri();
        $this->loadCurrentCukeFile();
        $this->cukeFileDataChange( $uri);
        $this->checkCukeFileOkay();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return "Seems Okay\n";
    }

    private function performCukeReset(){
        $cukeFileEntry = $this->askForCukeResetToScreen();
        if (!$cukeFileEntry) { return false; }
        if ( !$this->checkIsDHProject() ) {
            return "You don't appear to be in a dapperstrano project. Try: \ndapperstrano proj init\n"; }
        $this->loadCurrentCukeFile();
        $this->cukeFileReverseDataChange();
        $this->checkCukeFileOkay();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return "Seems Okay\n";
    }

    public function runAutoPilotAddition($autoPilot){
        if ( !isset($autoPilot["cukeConfAdditionExecute"]) || $autoPilot["cukeConfAdditionExecute"]==false ) { return false; }
      if ( !$this->checkIsDHProject() ) {
        return "You don't appear to be in a dapperstrano project. Try: \ndapperstrano proj init\n"; }
        $uri = $autoPilot["cukeConfAdditionURI"];
        $this->loadCurrentCukeFile();
        $this->cukeFileDataChange( $uri);
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return true;
    }

    public function runAutoPilotDeletion($autoPilot){
        if ( !isset($autoPilot["cukeConfDeletionExecute"]) || $autoPilot["cukeConfDeletionExecute"]==false) { return false; }
        if ( !$this->checkIsDHProject() ) {
            return "You don't appear to be in a dapperstrano project. Try: \ndapperstrano proj init\n"; }
        $this->loadCurrentCukeFile();
        $this->cukeFileReverseDataChange();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return true;
    }

    private function checkIsDHProject(){
        return file_exists('dhproj');
    }

    private function askForCukeModToScreen(){
        $question = 'Do you want to modify cucumber features URI?';
        return self::askYesOrNo($question);
    }

    private function askForCukeResetToScreen(){
        $question = 'Do you want to reset cucumber features?';
        return self::askYesOrNo($question);
    }

    private function askForCukefileUri(){
        $question = 'What URI do you want to add to the Cuke File?';
        return self::askForInput($question, true);
    }

    private function loadCurrentCukeFile() {
        $command = 'cat build/tests/features/support/env.rb';
        $this->cukeFileData = self::executeAndLoad($command);
    }

    private function cukeFileDataChange( $uri){
        $replacements =  array('****FEATURES URI****'=>$uri);
        $this->cukeFileData = strtr($this->cukeFileData, $replacements);
    }

    private function cukeFileReverseDataChange(){
        $settingsFileLines = explode("\n", $this->cukeFileData);
        $replacements =  array('ENV["APPLICATION_HOST"]'=>'ENV["APPLICATION_HOST"] || "****FEATURES URI****"');
        foreach ( $settingsFileLines as &$settingsFileLine ) {
            foreach ( $replacements as $searchFor=>$replaceWith ) {
                if (substr_count($settingsFileLine, $searchFor)==1) {
                    $settingsFileLine = $replaceWith; } } }
        $this->cukeFileData = implode("\n", $settingsFileLines);
    }

    private function checkCukeFileOkay(){
        $question = 'Please check cuke file: '.$this->cukeFileData."\n\nIs this Okay?";
        return self::askYesOrNo($question);
    }

    private function createCukeFile() {
        $tmpDir = $this->baseTempDir.DIRECTORY_SEPARATOR.'cukefile';
        if (!file_exists($tmpDir)) { mkdir ($tmpDir, 0777, true); }
        return file_put_contents($tmpDir.'/env.rb', $this->cukeFileData);
    }

    private function removeOldCukeFile(){
        $command = 'rm build'.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'features'.DIRECTORY_SEPARATOR.
          'support'.DIRECTORY_SEPARATOR.'env.rb';
        self::executeAndOutput($command);
    }

    private function moveCukeFile(){
        $command = 'mv '.$this->baseTempDir.DIRECTORY_SEPARATOR.'cukefile'.DIRECTORY_SEPARATOR.'env.rb build' .
          DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'features'.DIRECTORY_SEPARATOR.'support'.DIRECTORY_SEPARATOR.
          'env.rb';
        self::executeAndOutput($command);
    }

}