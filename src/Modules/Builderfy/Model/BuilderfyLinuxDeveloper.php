<?php

Namespace Model;

class BuilderfyLinuxDeveloper extends BuilderfyLinux {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Developer") ;

    public function __construct($params) {
        parent::__construct($params);
    }

    protected function getBuildConfigVars() {
        $bcv =
        array(
            "project_description" => $this->getProjectDescription() ,
            "github_url" => $this->getPrimaryScmUrl() ,
            "source_branch_spec" => $this->getSourceBranchSpec() ,
            "source_scm_url" => $this->getSourceScmUrl() ,
            "days_to_keep" => $this->getDaysToKeep() ,
            "num_to_keep" => $this->getAmountToKeep() ,
            "autopilot_install" => $this->getInstallAutopilot() ,
            "autopilot_uninstall" => $this->getUninstallAutopilot() ,
            "data-handling" => $this->selectBuildDataHandlingType() ,
            "build_type" => $this->params["action"] ,
            "target_scm_url" => $this->getTargetScmUrl() ,
            "target_branch" => $this->getTargetBranch() ,
        ) ;
        return $bcv ;
    }

    protected function getProjectDescription() {
        if (isset($this->params["project-description"])) {
            return $this->params["project-description"] ; }
        if (isset($this->params["guess"]) ) {
            $this->params["project-description"] = "Your Project Description" ;
            return $this->params["project-description"] ; }
        $papVersion = \Model\AppConfig::getProjectVariable("description");
        if (isset($this->params["guess"])  && !is_null($papVersion) ) {
            $this->params["project-description"] = $papVersion ;
            return $this->params["project-description"] ; }
        $question = 'Enter a description for your project' ;
        return self::askForInput($question) ;
    }

    protected function getPrimaryScmUrl() {
        if (isset($this->params["primary-scm-url"])) {
            return $this->params["primary-scm-url"] ; }
        $papVersion = \Model\AppConfig::getProjectVariable("primary-scm-url");
        if (isset($this->params["guess"])  && !is_null($papVersion) ) {
            $this->params["primary-scm-url"] = $papVersion ;
            return $papVersion ; }
        $question = 'Enter a Primary SCM URL for your project' ;
        $this->params["primary-scm-url"] = self::askForInput($question) ;
        return $this->params["primary-scm-url"] ;
    }

    protected function getSourceScmUrl() {
        if (isset($this->params["source-scm-url"])) {
            return $this->params["source-scm-url"] ; }
        $papVersion = \Model\AppConfig::getProjectVariable("source-scm-url");
        if (isset($this->params["guess"])) {
            $this->params["source-scm-url"] = getcwd() ;
            return $this->params["source-scm-url"] ; }
        $question = 'Enter a Source SCM URL for your project' ;
        $this->params["source-scm-url"] = self::askForInput($question) ;
        return $this->params["source-scm-url"] ;
    }

    protected function getSourceBranchSpec() {
        if (isset($this->params["source-branch-spec"])) {
            return $this->params["source-branch-spec"] ; }
        if (isset($this->params["guess"])) {
            $this->params["source-branch-spec"] = "origin/master" ;
            return $this->params["source-branch-spec"] ; }
        $question = 'Enter a Source Branch Spec for your project' ;
        $this->params["source-branch-spec"] = self::askForInput($question) ;
        return self::askForInput($question) ;
    }

    protected function getDaysToKeep() {
        if (isset($this->params["days-to-keep"])) {
            return $this->params["days-to-keep"] ; }
        if (isset($this->params["guess"])) {
            $this->params["days-to-keep"] = "-1" ;
            return $this->params["days-to-keep"] ; }
        $question = 'Enter the number of days to keep builds for' ;
        $this->params["days-to-keep"] = self::askForInput($question) ;
        return $this->params["days-to-keep"] ;
    }

    protected function getAmountToKeep() {
        if (isset($this->params["amount-to-keep"])) {
            return $this->params["amount-to-keep"] ; }
        if (isset($this->params["guess"])) {
            $this->params["amount-to-keep"] = "10" ;
            return $this->params["amount-to-keep"] ; }
        $question = 'Enter the max number of builds results to keep' ;
        $this->params["amount-to-keep"] = self::askForInput($question) ;
        return $this->params["amount-to-keep"] ;
    }

    protected function getTargetBranch() {
        if (isset($this->params["target-branch"])) {
            return $this->params["target-branch"] ; }
        if (isset($this->params["guess"])) {
            $this->params["target-branch"] = "master" ;
            return $this->params["target-branch"] ; }
        $question = 'Enter a target branch for your project' ;
        $this->params["target-branch"] = self::askForInput($question) ;
        return $this->params["target-branch"] ;
    }

    protected function getTargetScmUrl() {
        if (isset($this->params["target-scm-url"])) {
            return $this->params["target-scm-url"] ; }
        if (isset($this->params["guess"])) {
            $this->params["target-scm-url"] = $this->getPrimaryScmUrl();
            return $this->params["target-scm-url"] ; }
        $question = 'Enter a Target SCM URL for your project' ;
        $this->params["target-scm-url"] = self::askForInput($question) ;
        return $this->params["target-scm-url"] ;
    }

    protected function getInstallAutopilot() {
        if (isset($this->params["autopilot-install-file"])) {
            return $this->params["autopilot-install-file"] ; }
        if (isset($this->params["guess"])) {
            $this->params["autopilot-install-file"] = "build/config/ptdeploy/autopilots/autopilot-dev-jenkins-install.php" ;
            return $this->params["autopilot-install-file"] ; }
        $question = 'Enter the path of the autopilot install file (Relative to project root)' ;
        $this->params["autopilot-install-file"] = self::askForInput($question) ;
        return $this->params["autopilot-install-file"] ;
    }

    protected function getUninstallAutopilot() {
        if (isset($this->params["autopilot-uninstall"])) {
            return $this->params["autopilot-uninstall"] ; }
        if (isset($this->params["guess"])) {
            $this->params["autopilot-uninstall"] = "build/config/ptdeploy/autopilots/autopilot-dev-jenkins-uninstall.php" ;
            return $this->params["autopilot-uninstall"] ; }
        $question = 'Enter the path of the autopilot uninstall file (Relative to project root)' ;
        $this->params["autopilot-uninstall"] = self::askForInput($question) ;
        return $this->params["autopilot-uninstall"] ;
    }

}