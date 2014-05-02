<?php

Namespace Model;

class BuilderfyLinuxDeveloper extends BuilderfyLinux {

    // Compatibility
    public $os = array("any") ;
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
            "branch_spec" => $this->getSourceBranchSpec() ,
            "primary_scm_url" => $this->getPrimaryScmUrl() ,
            "days_to_keep" => $this->askForParam("days-to-keep", "Number of days to keep build", "-1") ,
            "num_to_keep" => $this->varOrDefault($this->params["build_num_to_keep"], "15") ,
            "autopilot_install" => $this->varOrDefault($this->params["build_autopilot_install"], "build/config/dapperstrano/autopilots/autopilot-dev-jenkins-install.php") ,
            "autopilot_uninstall" => $this->varOrDefault($this->params["build_autopilot_uninstall"], "build/config/dapperstrano/autopilots/autopilot-dev-jenkins-uninstall.php") ,
            "build_type" => $this->params["action"] ,
            "target_branch" => "remote master" ,
            "target_scm_url" ) ;
        return $bcv ;
    }

    protected function getProjectDescription() {
        if (isset($this->params["project-description"])) {
            return $this->params["project-description"] ; }
        if (isset($this->params["use-defaults"]) ) {
            return "Your Project Description" ; }
        $papVersion = \Model\AppConfig::getProjectVariable("description");
        if (isset($this->params["guess"])  && !is_null($papVersion) ) {
            return $papVersion ; }
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

    protected function getSourceBranchSpec() {
        if (isset($this->params["source-branch-spec"])) {
            return $this->params["source-branch-spec"] ; }
        if (isset($this->params["use-defaults"]) ) {
            $this->params["source-branch-spec"] = "origin/master" ;
            return $this->params["source-branch-spec"] ; }
        $papVersion = \Model\AppConfig::getProjectVariable("source-branch-spec");
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
        $papVersion = \Model\AppConfig::getProjectVariable("primary-scm-url");
        if (isset($this->params["guess"])  && !is_null($papVersion) ) {
            $this->params["primary-scm-url"] = $papVersion ;
            return $papVersion ; }
        $question = 'Enter a Primary SCM URL for your project' ;
        $this->params["primary-scm-url"] = self::askForInput($question) ;
        return $this->params["primary-scm-url"] ;
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

}