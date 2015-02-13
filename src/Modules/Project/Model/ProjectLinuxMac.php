<?php

Namespace Model;

class ProjectLinuxMac extends Base  {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $projectContainerDirectory;

    public function askWhetherToInitializeProject() {
        return $this->performProjectInitialize();
    }

    public function askWhetherToInitializeProjectContainer() {
        return $this->performProjectContainerInitialize();
    }

    private function performProjectInitialize() {
        $projInit = $this->askForProjModifyToScreen("To initialise Project");
        if ($projInit != true) { return false ; }
        $projInit = $this->askForProjInitToScreen();
        if (!$projInit) { return false ; }
        $this->projectInitialize() ;
        return "Seems Fine...";
    }

    private function performProjectContainerInitialize() {
        $projContInit = $this->askForProjContainerModifyToScreen();
        if ($projContInit!=true) { return false; }
        $projContInit = $this->askForProjContainerInitToScreen();
        if (!$projContInit) { return false; }
        $this->projectContainerDirectory = $this->askForProjContainerDirectory();
        $this->projectContainerInitialize();
        return "Seems Fine...";
    }

    private function askForProjModifyToScreen($extra = "") {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to Modify Project Settings '.$extra.'?';
        return self::askYesOrNo($question);
    }

    private function askForProjInitToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to initialize this as a ptdeploy project?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerModifyToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to Modify Project Container Settings?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerInitToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to initialize this as a ptdeploy project Container?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerDirectory() {
        if (isset($this->params["proj-container"])) { return $this->params["proj-container"] ; }
        $question = 'What is your Project Container directory?';
        return self::askForInput($question, true);
    }

    private function projectInitialize() {
        if ($this->checkIsPharaohProject() == false) {
            $command = 'touch papyrusfile';
            self::executeAndOutput($command, "Project file created"); }
    }

    private function projectContainerInitialize() {
        $command = 'mkdir -p '.$this->projectContainerDirectory;
        self::executeAndOutput($command, "Project Container directory created");
        chdir($this->projectContainerDirectory);
        echo getcwd().' space '.$this->projectContainerDirectory;
        $command = 'cd '.$this->projectContainerDirectory;
        self::executeAndOutput($command, "Moving to Container");
        $command = 'pwd '.$this->projectContainerDirectory;
        self::executeAndOutput($command, "Showing Container Directory");
        $command = 'touch dhprojc';
        self::executeAndOutput($command, "Project Container file created");
    }

    public static function checkIsPharaohProject($dir = null) {
        if ($dir == null) {
          return file_exists('papyrusfile'); }
        else {
          return file_exists($dir.DIRECTORY_SEPARATOR.'papyrusfile'); }
    }

    private function tryToCreateTempFolder(){
        if (!file_exists('/tmp/'.$this->tempFolder)) {
          mkdir ('/tmp/'.$this->tempFolder, 0777, true);}
    }

}