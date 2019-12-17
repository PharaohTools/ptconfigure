<?php

Namespace Model;

class CukeConfAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $cukeFileData;

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
            return "You don't appear to be in a ptdeploy project. Try: \nptdeploy proj init\n"; }
        $uri = $this->askForCukefileUrl();
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
            return "You don't appear to be in a ptdeploy project. Try: \nptdeploy proj init\n"; }
        $this->loadCurrentCukeFile();
        $this->cukeFileReverseDataChange();
        $this->checkCukeFileOkay();
        $this->createCukeFile();
        $this->removeOldCukeFile();
        $this->moveCukeFile();
        return "Seems Okay\n";
    }

    private function checkIsDHProject(){
        return file_exists('dhproj');
    }

    private function askForCukeModToScreen(){
        if (isset($this->params["yes"])) { return true ; }
        $question = 'Do you want to modify cucumber features URI?';
        return self::askYesOrNo($question);
    }

    private function askForCukeResetToScreen(){
        if (isset($this->params["yes"])) { return true ; }
        $question = 'Do you want to reset cucumber features?';
        return self::askYesOrNo($question);
    }

    private function askForCukefileUrl(){
        if (isset($this->params["cucumber-url"])) { return $this->params["cucumber-url"] ; }
        if (isset($this->params["cuke-url"])) { return $this->params["cuke-url"] ; }
        $question = 'What URL do you want to add to the Cuke File?';
        return self::askForInput($question, true);
    }

    private function loadCurrentCukeFile() {
        $command = 'cat build/tests/features/support/env.rb';
        $this->cukeFileData = self::executeAndLoad($command);
    }

    private function cukeFileDataChange($uri){
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
        if (isset($this->params["yes"])) { return true ; }
        $question = 'Please check cuke file: '.$this->cukeFileData."\n\nIs this Okay?";
        return self::askYesOrNo($question);
    }

    private function createCukeFile() {
        $tmpDir = self::$tempDir.DIRECTORY_SEPARATOR.'cukefile';
        if (!file_exists($tmpDir)) { mkdir ($tmpDir, 0777, true); }
        return file_put_contents($tmpDir.'/env.rb', $this->cukeFileData);
    }

    private function removeOldCukeFile(){
        $command = 'rm build'.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'features'.DIRECTORY_SEPARATOR.
          'support'.DIRECTORY_SEPARATOR.'env.rb';
        self::executeAndOutput($command);
    }

    private function moveCukeFile(){
        $command = 'mv '.self::$tempDir.DIRECTORY_SEPARATOR.'cukefile'.DIRECTORY_SEPARATOR.'env.rb build' .
          DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'features'.DIRECTORY_SEPARATOR.'support'.DIRECTORY_SEPARATOR.
          'env.rb';
        self::executeAndOutput($command);
    }

}