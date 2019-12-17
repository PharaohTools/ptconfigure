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

    protected $jenkinsOriginalJobFolderName;
    protected $jenkinsNewJobFolderName;
    protected $jenkinsFSFolder = "/var/lib/jenkins";
    protected $tempFolder = "/projectTemp/";
    protected $newJobName ;
    protected $projectContainerDirectory;
    protected $dataHandlingType;

    protected $environments ;
    protected $environmentReplacements ;
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

    public function askToScreenWhetherToBuilderfy() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Builderfy This?';
        return self::askYesOrNo($question, true);
    }

    public function performInstallBuildInProject() {
        $projectFactory = new \Model\Project();
        $projectModel = $projectFactory->getModel($this->params);
        if ( !$projectModel::checkIsPharaohProject() ) {
            $loggingFactory = new \Model\Logging() ;
            $log = $loggingFactory->getModel($this->params) ;
            $log->log("Error: No papyrusfile found") ;
            $log->log("Try: \"ptdeploy proj init\" to initialize your project.") ;
            $this->result = false ;
            return false; }
        $this->dataHandlingType = $this->selectBuildDataHandlingType() ;
        $this->jenkinsOriginalJobFolderName = $this->selectSourceTemplateDirectory();
        $this->jenkinsFSFolder = $this->selectJenkinsFolderInFileSystem();
        $this->newJobName = $this->askForTargetJobName() ;
        $this->getNewJobFolderIfJenkinsFolderExistsInFileSystem();
        $this->getEnvironments();
        if (!isset($this->params["no-autopilots"])) {
            if (method_exists($this, "templateAutopilots")) { $this->templateAutopilots() ; } }
        if (isset($this->params["only-autopilots"])) {
            $this->result = true ;
            return "Seems Fine..."; }
        $this->projectBuildInstall();
        $this->templateConfiguration();
        $this->changeNewJenkinsJobFolderPermissions();
        $this->changeNewJenkinsJobFolderOwner();
        $this->changeNewJenkinsJobFolderGroup();
        $this->result = true ;
        return "Seems Fine...";
    }

    public function getEnvironments() {
        $this->environments = \Model\AppConfig::getProjectVariable("environments");
    }

    protected function askForTargetJobName() {
        if (isset($this->params["target-job-name"])) { return $this->params["target-job-name"] ; }
        $question = 'What is the target Job Name?';
        return self::askForInput($question, true);
    }

    protected function selectJenkinsFolderInFileSystem(){
        if (isset($this->params["jenkins-home"])) { return $this->params["jenkins-home"] ; }
        $question = 'What is your Jenkins home?';
        if ($this->detectJenkinsHomeFolderExistence()) {
            if (isset($this->params["guess"])) { return $this->jenkinsFSFolder ; }
            $question .= ' Found "'. $this->jenkinsFSFolder.'" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->jenkinsFSFolder : $input ;  }
        return self::askForInput($question, true);
    }

    protected function selectBuildDataHandlingType()  {
        if (isset($this->params["data-handling-type"])) { return $this->params["data-handling-type"] ; }
        $buildTypes = array("code", "replication", "capture") ;
        $dht = self::askForArrayOption("Enter the data handling type", $buildTypes, true) ;
        return $dht ;
    }

    protected function selectSourceTemplateDirectory() {
        if (isset($this->params["source-build-dir"])) { return $this->params["source-build-dir"] ; }
        if (in_array($this->params["action"], array("developer", "manual-staging", "continuous-staging",
            "manual-production", "continuous-staging-to-production"))) {
            $templatesDir = str_replace("Model", "Templates/builds", dirname(__FILE__) ) ;
            $dir = $templatesDir.'/'.$this->dataHandlingType ;
            $dir = $dir.'/'.$this->params["action"] ;
            return $dir ; }
    }

    protected function getNewJobFolderIfJenkinsFolderExistsInFileSystem() {
        if ( $this->detectJenkinsJobExistence() ) {
            if (isset($this->params["new-job-dir"])) { return $this->params["new-job-dir"] ; }
            $question = 'Job "'.$this->jenkinsOriginalJobFolderName.'" already exists. Enter new Job folder.';
            $this->jenkinsNewJobFolderName = self::askForInput($question, true); }
        else { $this->jenkinsNewJobFolderName = $this->jenkinsOriginalJobFolderName ; }
    }

    protected function projectBuildInstall() {
        $command  = 'sudo cp -r '.$this->jenkinsOriginalJobFolderName.' ' ;
        $command .= $this->jenkinsFSFolder.'/jobs/'.$this->newJobName;
        self::executeAndOutput($command, "Copying Files...");
    }

    protected function templateConfiguration() {
        $templatorFactory = new \Model\Templating();
        $templator = $templatorFactory->getModel($this->params);

        //@todo URGENT the source and targt cant be the fuking same???
        $data = file_get_contents($this->jenkinsFSFolder.'/jobs/'.$this->newJobName.'/config.xml') ;
        $targetLocation = $this->jenkinsFSFolder.'/jobs/'.$this->newJobName.'/config.xml' ;
        $templator->template(
            $data,
            $this->getBuildConfigVars(),
            $targetLocation );
    }

    public function getServerArrayText($serversArray) {
        $serversText = "";
        foreach($serversArray as $serverArray) {
            $serversText .= 'array(';
            $serversText .= '"target" => "'.$serverArray["target"].'", ';
            $serversText .= '"user" => "'.$serverArray["user"].'", ';
            $serversText .= '"pword" => "'.$serverArray["password"].'", ';
            $serversText .= '),'."\n"; }
        return $serversText;
    }

    protected function changeNewJenkinsJobFolderPermissions() {
        $command  = 'sudo chmod -R 755 '.$this->jenkinsFSFolder.'/jobs/'.$this->newJobName;
        self::executeAndOutput($command, "Changing Folder Permissions...");
    }

    protected function changeNewJenkinsJobFolderOwner() {
        $command  = 'sudo chown -R jenkins '.$this->jenkinsFSFolder.'/jobs/'.$this->newJobName;
        self::executeAndOutput($command, "Changing Folder Owner...");
    }

    protected function changeNewJenkinsJobFolderGroup() {
        $command  = 'sudo chgrp -R jenkins '.$this->jenkinsFSFolder.'/jobs/'.$this->newJobName;
        self::executeAndOutput($command, "Changing Folder Group...");
    }

    protected function detectJenkinsHomeFolderExistence() {
        return file_exists($this->jenkinsFSFolder);
    }

    protected function detectJenkinsJobExistence(){
        return file_exists($this->jenkinsFSFolder.'/jobs/'.$this->jenkinsOriginalJobFolderName);
    }

}