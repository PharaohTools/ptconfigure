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

    protected $projectContainerDirectory;

    public function askWhetherToInitializeProject() {
        return $this->performProjectInitialize();
    }

    public function askWhetherToInitializeProjectContainer() {
        return $this->performProjectContainerInitialize();
    }

    protected function performProjectInitialize() {
        $projInit = $this->askForProjModifyToScreen("To initialise Project");
        if ($projInit != true) { return false ; }
        $projInit = $this->askForProjInitToScreen();
        if (!$projInit) { return false ; }
        $this->projectInitialize() ;
        return "Seems Fine...";
    }

    protected function performProjectContainerInitialize() {
        $projContInit = $this->askForProjContainerModifyToScreen();
        if ($projContInit!=true) { return false; }
        $projContInit = $this->askForProjContainerInitToScreen();
        if (!$projContInit) { return false; }
        $this->projectContainerDirectory = $this->askForProjContainerDirectory();
        $this->projectContainerInitialize();
        return "Seems Fine...";
    }

    protected function askForProjModifyToScreen($extra = "") {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to Modify Project Settings '.$extra.'?';
        return self::askYesOrNo($question);
    }

    protected function askForProjInitToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to initialize this as a ptdeploy project?';
        return self::askYesOrNo($question);
    }

    protected function askForProjContainerModifyToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to Modify Project Container Settings?';
        return self::askYesOrNo($question);
    }

    protected function askForProjContainerInitToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to initialize this as a ptdeploy project Container?';
        return self::askYesOrNo($question);
    }

    protected function askForProjContainerDirectory() {
        if (isset($this->params["proj-container"])) { return $this->params["proj-container"] ; }
        $question = 'What is your Project Container directory?';
        return self::askForInput($question, true);
    }

    protected function projectInitialize() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params) ;
        if ($this->checkIsPharaohProject() == false) {
            file_put_contents('papyrusfile', "");
            $logging->log("Project Container file created", $this->getModuleName()); }
    }

    protected function projectContainerInitialize() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params) ;
        $command = 'mkdir -p '.$this->projectContainerDirectory;
        self::executeAndOutput($command, "Project Container directory created");
        $cur_dir = getcwd() ;
        chdir($this->projectContainerDirectory);
        echo getcwd().' space '.$this->projectContainerDirectory;
        $logging->log("Moving to Container", $this->getModuleName());
        $command = 'pwd '.$this->projectContainerDirectory;
        self::executeAndOutput($command, "Showing Container Directory");
        $command = 'touch dhprojc';
        self::executeAndOutput($command, "Project Container file created");
        chdir($cur_dir) ;
    }

    public static function checkIsPharaohProject($dir = null) {
        if ($dir == null) {
          return file_exists('papyrusfile'); }
        else {
          return file_exists($dir.DIRECTORY_SEPARATOR.'papyrusfile'); }
    }

}