<?php

Namespace Model;

class CukeConf extends Base {

    private $cukeFileData;

    public function askWhetherToModifyCuke(){
        return $this->performCukeConfigure();
    }

    private function performCukeConfigure(){
        $cukeFileEntry = $this->askForCukeModToScreen();
        if (!$cukeFileEntry) { return false; }
        if ( !$this->checkIsDHProject() ) {
            return "You don't appear to be in a devhelper project. Try: \ndevhelper proj init\n"; }
        $uri = $this->askForCukefileUri();
        $this->loadCurrentCukeFile();
        $this->cukeFileDataChange( $uri);
        $this->checkCukeFileOkay();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return "Seems Okay";
    }

    private function performCukeReset(){
        $cukeFileEntry = $this->askForCukeModToScreen();
        if (!$cukeFileEntry) { return false; }
        if ( !$this->checkIsDHProject() ) {
            return "You don't appear to be in a devhelper project. Try: \ndevhelper proj init\n"; }
        $uri = $this->askForCukefileUri();
        $this->loadCurrentCukeFile();
        $this->cukeFileReverseDataChange();
        $this->checkCukeFileOkay();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return "Seems Okay";
    }

    private function runAutoPilotPerformCukeConfigure(){
        $cukeFileEntry = $this->cukeConfAdditionExecute();
        if (!$cukeFileEntry) { return false; }
        if ( !$this->checkIsDHProject() ) {
            return "You don't appear to be in a devhelper project. Try: \ndevhelper proj init\n"; }
        $uri = $this->cukeConfAdditionURI();
        $this->loadCurrentCukeFile();
        $this->cukeFileDataChange( $uri);
        $this->checkCukeFileOkay();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return "Seems Okay";
    }

    private function runAutoPilotPerformCukeReset(){
        $cukeFileEntry = $this->$cukeConfDeletionExecute();
        if (!$cukeFileEntry) { return false; }
        if ( !$this->checkIsDHProject() ) {
            return "You don't appear to be in a devhelper project. Try: \ndevhelper proj init\n"; }
        $this->loadCurrentCukeFile();
        $this->cukeFileReverseDataChange();
        $this->checkCukeFileOkay();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return "Seems Okay";
    }

    private function checkIsDHProject(){
        return file_exists('dhproj');
    }

    private function askForCukeModToScreen(){
        $question = 'Do you want to modify cucumber features? (Y/N)';
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
        $replacements =  array('****FEATURES URI****'=>'        ENV["APPLICATION_HOST"] || "****FEATURES URI****"');
        foreach ( $settingsFileLines as &$settingsFileLine ) {
            foreach ( $replacements as $searchFor=>$replaceWith ) {
                if (strpos($settingsFileLine, $searchFor)) {
                    $settingsFileLine = $replaceWith; } } }
        $this->cukeFileData = implode("\n", $settingsFileLines);
    }

    private function checkCukeFileOkay(){
        $question = 'Please check cuke file: '.$this->cukeFileData."\n\nIs this Okay? (Y/N)";
        return self::askYesOrNo($question);
    }

    private function createCukeFile() {
        $tmpDir = '/tmp/cukefile/';
        if (!file_exists($tmpDir)) { mkdir ($tmpDir); }
        return file_put_contents($tmpDir.'/env.rb', $this->cukeFileData);
    }

    private function removeOldCukeFile(){
        $command = 'rm build/tests/features/support/env.rb';
        self::executeAndOutput($command);
    }

    private function moveCukeFile(){
        $command = 'mv /tmp/cukefile/env.rb build/tests/features/support/env.rb';
        self::executeAndOutput($command);
    }

}