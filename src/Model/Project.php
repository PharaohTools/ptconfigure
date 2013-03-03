<?php

Namespace Model;

class Project extends Base  {

    private $jenkinsOriginalJobFolderName;
    private $jenkinsNewJobFolderName;
    private $jenkinsFSFolder = "/var/lib/jenkins";
    private $tempFolder = "/tmp/tempbuild/";

    public function askWhetherToInitializeProject() {
        return $this->performProjectInitialize();
    }

    public function askWhetherToInstallBuildInProject() {
        return $this->performInstallBuildInProject();
    }

    public function runAutoPilotInit($autoPilot) {
        $projInit = $autoPilot->projectInitializeExecute;
        if (!$projInit) { return false; }
        $this->projectInitialize();
        return true;
    }

    public function runAutoPilotBuildInstall($autoPilot) {
        $projBuildInstall = $autoPilot->projectBuildInstallExecute;
        if (!$projBuildInstall) { return false; }
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
        $question = 'Do you want to Modify Project Settings? (Y/N)';
        return self::askYesOrNo($question);
    }

    private function askForProjInitToScreen() {
        $question = 'Do you want to initialize this as a devhelper project? (Y/N)';
        return self::askYesOrNo($question);
    }

    private function askForProjBuildInstallToScreen() {
        $question = 'Do you want to install the Jenkins build for this project? (Y/N)';
        return self::askYesOrNo($question);
    }

    private function projectInitialize() {
        if ($this->checkIsDHProject()) {
            $command = 'touch dhproj';
            self::executeAndOutput($command, "Project file created"); }
    }

    private function checkIsDHProject() {
        return file_exists('dhproj');
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

    private function selectJenkinsFolderInFileSystem(){
        $question = 'What is your Jenkins home?';
        if ($this->detectJenkinsHomeFolderExistence()) {
            $question .= ' Found "'. $this->jenkinsFSFolder.'" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->jenkinsFSFolder : $input ;  }
        return self::askForInput($question, true);
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