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

    private $jenkinsOriginalJobFolderName;
    private $jenkinsNewJobFolderName;
    private $jenkinsFSFolder = "/var/lib/jenkins";
    private $tempFolder = "/projectTemp/";
    private $projectContainerDirectory;
    private $newJobName ;

    public function askWhetherToInitializeProject() {
        return $this->performProjectInitialize();
    }

    public function askWhetherToInitializeProjectContainer() {
        return $this->performProjectContainerInitialize();
    }

    public function askWhetherToInstallBuildInProject() {
        return $this->performInstallBuildInProject();
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

    private function performInstallBuildInProject() {
        $projInit = $this->askForProjModifyToScreen("To Install Build");
        if ($projInit!=true) { return false; }
        if ( !$this->checkIsPharoahProject() ) { return "No papyrusfile found. Try: \ndapperstrano proj init\n"; }
        $projInit = $this->askForProjBuildInstallToScreen();
        if (!$projInit) { return false; }
        $this->jenkinsOriginalJobFolderName = $this->selectJenkinsFolderInProject();
        $this->jenkinsFSFolder = $this->selectJenkinsFolderInFileSystem();
        $this->newJobName = $this->askForTargetJobName() ;
        $this->getNewJobFolderIfJenkinsFolderExistsInFileSystem();
        $this->tryToCreateTempFolder();
        $this->projectBuildInstall();
        $this->changeNewJenkinsJobFolderPermissions();
        $this->changeNewJenkinsJobFolderOwner();
        $this->changeNewJenkinsJobFolderGroup();
        $this->ctlJenkins("stop");
        $this->ctlJenkins("start");
        return "Seems Fine...";
    }

    private function askForProjModifyToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to Modify Project Settings?';
        return self::askYesOrNo($question);
    }

    private function askForProjInitToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to initialize this as a dapperstrano project?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerModifyToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to Modify Project Container Settings?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerInitToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to initialize this as a dapperstrano project Container?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerDirectory() {
        if (isset($this->params["proj-container"])) { return $this->params["proj-container"] ; }
        $question = 'What is your Project Container directory?';
        return self::askForInput($question, true);
    }

    private function askForTargetJobName() {
        if (isset($this->params["target-job-name"])) { return $this->params["target-job-name"] ; }
        $question = 'What is the target Job Name?';
        return self::askForInput($question, true);
    }

    private function selectJenkinsFolderInFileSystem(){
        if (isset($this->params["jenkins-fs-dir"])) { return $this->params["jenkins-fs-dir"] ; }
        $question = 'What is your Jenkins home?';
        if ($this->detectJenkinsHomeFolderExistence()) {
            if (isset($this->params["guess"])) { return $this->jenkinsFSFolder ; }
            $question .= ' Found "'. $this->jenkinsFSFolder.'" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->jenkinsFSFolder : $input ;  }
        return self::askForInput($question, true);
    }

    private function askForProjBuildInstallToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to install the Jenkins build for this project?';
        return self::askYesOrNo($question);
    }

    private function projectInitialize() {
        if ($this->checkIsPharoahProject() == false) {
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

    public static function checkIsPharoahProject($dir = null) {
        if ($dir == null) {
          return file_exists('papyrusfile'); }
        else {
          return file_exists($dir.DIRECTORY_SEPARATOR.'papyrusfile'); }
    }

    private function selectJenkinsFolderInProject(){
        if (isset($this->params["original-build-dir"])) { return $this->params["original-build-dir"] ; }
        $projBuildsDir = getcwd().'/'."build/config/jenkins/projects" ;
        $results = (file_exists($projBuildsDir)) ? scandir($projBuildsDir) : array() ;
        $availableDirs = array() ;
        $question = "Please Choose Jenkins Directory in Project:\n";
        $i=0;
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            if (is_dir("build/config/jenkins" . '/' . $result)) {
                $availableDirs[] = $result;
                $question .= "($i) $result\n";
                $i++; } }
        $validChoice = false;
        $i=0;
        if (count($availableDirs)>0) {
            while ($validChoice == false) {
                if ($i==1) { $question = "That's not a valid option, ".$question; }
                $input = self::askForDigit($question) ;
                if ( array_key_exists($input, $availableDirs) ){
                    $validChoice = true;}
                $i++; }
            return $projBuildsDir.'/'.$availableDirs[$input] ; }
        else {
            echo "No options available, enter path:\n" ;
            return $this->askForInput($question, true) ; }
    }

    private function getNewJobFolderIfJenkinsFolderExistsInFileSystem(){
        if ( $this->detectJenkinsJobExistence() ) {
            if (isset($this->params["new-job-dir"])) { return $this->params["new-job-dir"] ; }
            $question = 'Job "'.$this->jenkinsOriginalJobFolderName.'" already exists. Enter new Job folder.';
            $this->jenkinsNewJobFolderName = self::askForInput($question, true); }
        else { $this->jenkinsNewJobFolderName = $this->jenkinsOriginalJobFolderName ; }
    }

    private function tryToCreateTempFolder(){
        if (!file_exists('/tmp/'.$this->tempFolder)) {
          mkdir ('/tmp/'.$this->tempFolder, 0777, true);}
    }

    private function projectBuildInstall(){
        $command  = 'sudo cp -r '.$this->jenkinsOriginalJobFolderName.' ' ;
        $command .= $this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Copying Files...");
    }

    private function changeNewJenkinsJobFolderPermissions(){
        $command  = 'sudo chmod -R 755 '.$this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Changing Folder Permissions...");
    }

    private function changeNewJenkinsJobFolderOwner(){
        $command  = 'sudo chown -R jenkins '.$this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Changing Folder Owner...");
    }

    private function changeNewJenkinsJobFolderGroup(){
        $command  = 'sudo chgrp -R jenkins '.$this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Changing Folder Group...");
    }

    private function ctlJenkins($action){
        $command  = 'sudo service jenkins '.$action;
        self::executeAndOutput($command, ucfirst($action)."ing Jenkins...");
    }

    private function detectJenkinsHomeFolderExistence(){
        return file_exists($this->jenkinsFSFolder);
    }

    private function detectJenkinsJobExistence(){
        return file_exists($this->jenkinsFSFolder.'/jobs/'.$this->jenkinsOriginalJobFolderName);
    }

}