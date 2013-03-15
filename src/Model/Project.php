<?php

Namespace Model;

class Project extends Base  {

    private $jenkinsOriginalJobFolderName;
    private $jenkinsNewJobFolderName;
    private $jenkinsFSFolder = "/var/lib/jenkins";
    private $tempFolder = "/tmp/tempbuild/";
    private $projectContainerDirectory;

    public function askWhetherToInitializeProject() {
        return $this->performProjectInitialize();
    }

    public function askWhetherToInitializeProjectContainer() {
        return $this->performProjectContainerInitialize();
    }

    public function askWhetherToInstallBuildInProject() {
        return $this->performInstallBuildInProject();
    }

    public function runAutoPilotProjectContInit($autoPilot) {
        $projContInit = $autoPilot->projectContainerInitExecute;
        if ($projContInit != true) { return false; }
        $this->projectContainerDirectory = $autoPilot->projectContainerDirectory;
        $this->projectContainerInitialize();
        return true;
    }

    public function runAutoPilotInit($autoPilot) {
        $projInit = $autoPilot->projectInitializeExecute;
        if ($projInit != true) { return false; }
        $this->projectInitialize();
        return true;
    }

    public function runAutoPilotBuildInstall($autoPilot) {
        $projBuildInstall = $autoPilot->projectBuildInstallExecute;
        if ($projBuildInstall != true) { return false; }
        if ( !$this->checkIsDHProject() ) { return "No Devhelper project file found. Try: \ndevhelper proj init\n"; }
        $this->jenkinsOriginalJobFolderName = $autoPilot->projectJenkinsOriginalJobFolderName;
        $this->jenkinsFSFolder = $autoPilot->projectJenkinsFSFolder;
        $this->jenkinsNewJobFolderName = $autoPilot->projectJenkinsNewJobFolderName ;
        $this->tryToCreateTempFolder();
        $this->projectBuildInstall();
        $this->changeNewJenkinsJobFolderPermissions();
        $this->changeNewJenkinsJobFolderOwner();
        $this->changeNewJenkinsJobFolderGroup();
        $this->ctlJenkins("stop");
        $this->ctlJenkins("start");
        return true;
    }

    private function performProjectInitialize() {
        $projInit = $this->askForProjModifyToScreen();
        if (!$projInit) { return false; }
        $projInit = $this->askForProjInitToScreen();
        if (!$projInit) { return false; }
        $this->projectInitialize();
        return "Seems Fine...";
    }

    private function performProjectContainerInitialize() {
        $projContInit = $this->askForProjContainerModifyToScreen();
        if (!$projContInit) { return false; }
        $projContInit = $this->askForProjContainerInitToScreen();
        if (!$projContInit) { return false; }
        $this->projectContainerDirectory = $this->askForProjContainerDirectory();
        $this->projectContainerInitialize();
        return "Seems Fine...";
    }

    private function performInstallBuildInProject() {
        $projInit = $this->askForProjModifyToScreen();
        if (!$projInit) { return false; }
        if ( !$this->checkIsDHProject() ) { return "No Devhelper project file found. Try: \ndevhelper proj init\n"; }
        $projInit = $this->askForProjBuildInstallToScreen();
        if (!$projInit) { return false; }
        $this->jenkinsOriginalJobFolderName = $this->selectJenkinsFolderInProject();
        $this->jenkinsFSFolder = $this->selectJenkinsFolderInFileSystem();
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
        $question = 'Do you want to Modify Project Settings?';
        return self::askYesOrNo($question);
    }

    private function askForProjInitToScreen() {
        $question = 'Do you want to initialize this as a devhelper project?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerModifyToScreen() {
        $question = 'Do you want to Modify Project Container Settings?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerInitToScreen() {
        $question = 'Do you want to initialize this as a devhelper project Container?';
        return self::askYesOrNo($question);
    }

    private function askForProjContainerDirectory(){
        $question = 'What is your Project Container directory?';
        return self::askForInput($question, true);
    }

    private function selectJenkinsFolderInFileSystem(){
        $question = 'What is your Jenkins home?';
        if ($this->detectJenkinsHomeFolderExistence()) {
            $question .= ' Found "'. $this->jenkinsFSFolder.'" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->jenkinsFSFolder : $input ;  }
        return self::askForInput($question, true);
    }

    private function askForProjBuildInstallToScreen() {
        $question = 'Do you want to install the Jenkins build for this project?';
        return self::askYesOrNo($question);
    }

    private function projectInitialize() {
        if ($this->checkIsDHProject() == false) {
            $command = 'touch dhproj';
            self::executeAndOutput($command, "Project file created"); }
    }

    private function projectContainerInitialize() {
        if ($this->checkIsDHProjectContainer() == false) {
            $command = 'mkdir -p '.$this->projectContainerDirectory;
            self::executeAndOutput($command, "Project Container directory created");
            chdir($this->projectContainerDirectory);
            echo getcwd();
            self::executeAndOutput($command, "Moving to Container");
            $command = 'cd '.$this->projectContainerDirectory;
            self::executeAndOutput($command, "Moving to Container");
            $command = 'pwd '.$this->projectContainerDirectory;
            self::executeAndOutput($command, "Showing Container Directory");
            $command = 'touch dhprojc';
            self::executeAndOutput($command, "Project Container file created"); }
    }

    private function checkIsDHProject() {
        return file_exists('dhproj');
    }

    private function checkIsDHProjectContainer() {
        return file_exists('dhprojc');
    }

    private function selectJenkinsFolderInProject(){
        $results = scandir(getcwd().'/'."build/config/jenkins");
        $availableDirs = array();
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
        while ($validChoice == false) {
            if ($i==1) { $question = "That's not a valid option, ".$question; }
            $input = self::askForDigit($question) ;
            if ( array_key_exists($input, $availableDirs) ){
                $validChoice = true;}
            $i++; }
        return $availableDirs[$input] ;
    }

    private function getNewJobFolderIfJenkinsFolderExistsInFileSystem(){
        if ( $this->detectJenkinsJobExistence() ) {
            $question = 'Job "'.$this->jenkinsOriginalJobFolderName.'" already exists. Enter new Job folder.';
            $this->jenkinsNewJobFolderName = self::askForInput($question, true); }
        else { $this->jenkinsNewJobFolderName = $this->jenkinsOriginalJobFolderName ; }
    }

    private function tryToCreateTempFolder(){
        if (!file_exists($this->tempFolder)) { mkdir ($this->tempFolder);}
    }

    private function projectBuildInstall(){
        $command  = 'sudo cp -r build/config/jenkins'.'/'.$this->jenkinsOriginalJobFolderName.' ' ;
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