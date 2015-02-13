<?php

Namespace Model;

class BuilderfyLinuxContinuousStaging extends BuilderfyLinux {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("ContinuousStaging") ;

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
            "autopilot-test-invoke-install-file" => $this->getTestInstallAutopilot() ,
            "autopilot-test-invoke-dbconf-install-file" => $this->getTestDBConfInstallAutopilot() ,
            "autopilot-test-invoke-dbinstall-install-file" => $this->getTestDBInstallInstallAutopilot() ,
            "data-handling" => $this->selectBuildDataHandlingType() ,
            "error-email" => $this->getErrorEmail() ,
            "build_type" => $this->params["action"] ,
        ) ;
        return $bcv ;
    }

    protected function getProjectDescription() {
        if (isset($this->params["project-description"])) {
            return $this->params["project-description"] ; }
        if (isset($this->params["use-defaults"]) ) {
            $this->params["project-description"] = "Your Project Description" ;
            return $this->params["project-description"] ; }
        $papVersion = \Model\AppConfig::getProjectVariable("description");
        if (isset($this->params["guess"])  && !is_null($papVersion) ) {
            $this->params["project-description"] = $papVersion ;
            return $this->params["project-description"] ;}
        $question = 'Enter a description for your project' ;
        $this->params["project-description"] = self::askForInput($question) ;
        return $this->params["project-description"] ;
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

    protected function getErrorEmail() {
        if (isset($this->params["error-email"])) {
            return $this->params["error-email"] ; }
        if (isset($this->params["guess"])) {
            $this->params["error-email"] = "10" ;
            return $this->params["error-email"] ; }
        $question = 'Enter build failure Email address. Whitespace-separated list of recipient addresses' ;
        $this->params["error-email"] = self::askForInput($question) ;
        return $this->params["error-email"] ;
    }

    protected function getTestInstallAutopilot() {
        if (isset($this->params["autopilot-test-invoke-install-file"])) {
            return $this->params["autopilot-test-invoke-install-file"] ; }
        if (isset($this->params["guess"])) {
            $this->params["autopilot-test-invoke-install-file"] = "build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" ;
            return $this->params["autopilot-test-invoke-install-file"] ; }
        $question = 'Enter the path of the autopilot test environment invoke install file (Relative to project root)' ;
        $this->params["autopilot-test-invoke-install-file"] = self::askForInput($question) ;
        return $this->params["autopilot-test-invoke-install-file"] ;
    }

    protected function getTestDBConfInstallAutopilot() {
        if (isset($this->params["autopilot-test-invoke-dbconf-install-file"])) {
            return $this->params["autopilot-test-invoke-dbconf-install-file"] ; }
        if (isset($this->params["guess"])) {
            $this->params["autopilot-test-invoke-dbconf-install-file"] = "build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" ;
            return $this->params["autopilot-test-invoke-dbconf-install-file"] ; }
        $question = 'Enter the path of the autopilot test environment invoke with DB Config install file (Relative to project root)' ;
        $this->params["autopilot-test-invoke-dbconf-install-file"] = self::askForInput($question) ;
        return $this->params["autopilot-test-invoke-dbconf-install-file"] ;
    }

    protected function getTestDBInstallInstallAutopilot() {
        if (isset($this->params["autopilot-test-invoke-dbinstall-install-file"])) {
            return $this->params["autopilot-test-invoke-dbinstall-install-file"] ; }
        if (isset($this->params["guess"])) {
            $this->params["autopilot-test-invoke-dbinstall-install-file"] = "build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" ;
            return $this->params["autopilot-test-invoke-dbinstall-install-file"] ; }
        $question = 'Enter the path of the autopilot test environment invoke with DB Install file (Relative to project root)' ;
        $this->params["autopilot-test-invoke-dbinstall-install-file"] = self::askForInput($question) ;
        return $this->params["autopilot-test-invoke-dbinstall-install-file"] ;
    }

    protected function templateAutopilots() {
        $templatesDir = str_replace("Model", "Templates/Autopilots", dirname(__FILE__) ) ;
        $template = $templatesDir . "/environment-invoke-continuous-staging.php" ;
        $servers = (isset($environment["servers"])) ? $environment["servers"] : array() ;
        foreach ($this->environments as $environment) {
            $defaultReplacements =
                array(
                    "gen_srv_array_text" => $this->getServerArrayText($servers) ,
                    "env_name" => $environment["any-app"]["gen_env_name"],
                    "gen_env_tmp_dir" => $environment["any-app"]["gen_env_tmp_dir"] ) ;
            $replacements = array_merge($defaultReplacements, $this->getBuildConfigVars()) ;
            $jenks =
                array(
                    "target-job-name" => $this->newJobName ,
                    "jenkins-home" => $this->jenkinsFSFolder , ) ;
            $replacements = array_merge($replacements, $jenks) ;
            $templatorFactory = new \Model\Templating();
            $templator = $templatorFactory->getModel($this->params);
            $newFileName = str_replace("environment", $environment["any-app"]["gen_env_name"], $template ) ;
            $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'ptdeploy'.
                DIRECTORY_SEPARATOR.'builderfy'.DIRECTORY_SEPARATOR.'autopilots'.DIRECTORY_SEPARATOR.'generated';
            $newFileName = basename($newFileName);
            $targetLocation = $autosDir.DIRECTORY_SEPARATOR.$newFileName ;
            $templator->template(
                file_get_contents($template),
                $replacements,
                $targetLocation ); }
    }

}