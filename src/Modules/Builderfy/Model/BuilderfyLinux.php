<?php

Namespace Model;

class BuilderfyLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
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
    private $newJobName ;
    private $projectContainerDirectory;

    private $environments ;
    private $environmentReplacements ;
    public $result ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Builderfy";
        $this->installCommands = array(
            // array("method"=> array("object" => $this, "method" => "setLogMessage", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "performInstallBuildInProject", "params" => array()) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/Builderfy"; // command and app dir name
        $this->programNameMachine = "builderfy"; // command and app dir name
        $this->programNameFriendly = "  Builderfy!  "; // 12 chars
        $this->programNameInstaller = "Builderfy";
        $this->initialize();
    }

    public function askWhetherToBuilderfy() {
        if ($this->askToScreenWhetherToBuilderfy() != true) { return false; }
        if ($this->performInstallBuildInProject() == false){
            return false ; }
        $this->setEnvironmentReplacements() ;
        $this->getEnvironments() ;
        $this->doBuilderfy() ;
        return true;
    }

    public function askToScreenWhetherToBuilderfy() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Builderfy This?';
        return self::askYesOrNo($question, true);
    }

    public function performInstallBuildInProject() {
        $projectFactory = new \Model\Project();
        $projectModel = $projectFactory->getModel($this->params);
        if ( !$projectModel::checkIsPharoahProject() ) {
            $loggingFactory = new \Model\Logging() ;
            $log = $loggingFactory->getModel($this->params) ;
            $log->log("Error: No papyrusfile found") ;
            $log->log("Try: \"dapperstrano proj init\" to initialize your project.") ;
            $this->result = false ;
            return; }
        $this->jenkinsOriginalJobFolderName = $this->selectSourceTemplateDirectory();
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
        $this->result = true ;
        return "Seems Fine...";
    }

    protected function askForTargetJobName() {
        if (isset($this->params["target-job-name"])) { return $this->params["target-job-name"] ; }
        $question = 'What is the target Job Name?';
        return self::askForInput($question, true);
    }

    protected function selectJenkinsFolderInFileSystem(){
        if (isset($this->params["jenkins-fs-dir"])) { return $this->params["jenkins-fs-dir"] ; }
        $question = 'What is your Jenkins home?';
        if ($this->detectJenkinsHomeFolderExistence()) {
            if (isset($this->params["guess"])) { return $this->jenkinsFSFolder ; }
            $question .= ' Found "'. $this->jenkinsFSFolder.'" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->jenkinsFSFolder : $input ;  }
        return self::askForInput($question, true);
    }

    protected function selectSourceTemplateDirectory(){
        if (isset($this->params["source-build-dir"])) { return $this->params["source-build-dir"] ; }
        $actsToDirs = array("developer", "staging", "continuous-staging", "production", "continuous-production") ;
        if (in_array($action, array_keys($actsToDirs))) {
            $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
            $dir = $templatesDir.'/'.$action ;
            return $dir ; }
    }

    protected function getNewJobFolderIfJenkinsFolderExistsInFileSystem(){
        if ( $this->detectJenkinsJobExistence() ) {
            if (isset($this->params["new-job-dir"])) { return $this->params["new-job-dir"] ; }
            $question = 'Job "'.$this->jenkinsOriginalJobFolderName.'" already exists. Enter new Job folder.';
            $this->jenkinsNewJobFolderName = self::askForInput($question, true); }
        else { $this->jenkinsNewJobFolderName = $this->jenkinsOriginalJobFolderName ; }
    }

    protected function projectBuildInstall(){
        $command  = 'sudo cp -r '.$this->jenkinsOriginalJobFolderName.' ' ;
        $command .= $this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Copying Files...");
    }

    protected function changeNewJenkinsJobFolderPermissions(){
        $command  = 'sudo chmod -R 755 '.$this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Changing Folder Permissions...");
    }

    protected function changeNewJenkinsJobFolderOwner(){
        $command  = 'sudo chown -R jenkins '.$this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Changing Folder Owner...");
    }

    protected function changeNewJenkinsJobFolderGroup(){
        $command  = 'sudo chgrp -R jenkins '.$this->jenkinsFSFolder.'/jobs/'.$this->jenkinsNewJobFolderName;
        self::executeAndOutput($command, "Changing Folder Group...");
    }

    protected function ctlJenkins($action){
        $command  = 'sudo service jenkins '.$action;
        self::executeAndOutput($command, ucfirst($action)."ing Jenkins...");
    }

    protected function detectJenkinsHomeFolderExistence(){
        return file_exists($this->jenkinsFSFolder);
    }

    protected function detectJenkinsJobExistence(){
        return file_exists($this->jenkinsFSFolder.'/jobs/'.$this->jenkinsOriginalJobFolderName);
    }

}