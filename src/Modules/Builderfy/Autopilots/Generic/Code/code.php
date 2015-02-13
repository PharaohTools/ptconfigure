<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct($params = array()) {
        parent::__construct($params);
        $this->getDefaults();
        $this->setSteps();
    }

    /* Steps */
    protected function setSteps() {

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin creating Autopilots for multiple build types"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Lets create Autopilots to install a continuous staging to production build complete"),),),
                array ( "Builderfy" => array("continuous-staging-to-production" => array(
                    "jenkins-home" => "/var/lib/jenkins",
                    "target-job-name" => "{$this->params["job-name"]}-continuous-staging-to-production",
                    "new-job-dir" => "{$this->params["job-name"]}-continuous-staging-to-production",
                    "project-description" => "This is the Continuous Staging to Production build for My Project, {$this->params["project-name"]}",
                    "primary-scm-url" => "{$this->params["scm-url"]}",
                    "source-branch-spec" => "origin/master",
                    "source-scm-url" => "{$this->params["scm-url"]}",
                    "days-to-keep" => "-1",
                    "amount-to-keep" => "10",
                    "autopilot-test-invoke-install-file" => "build/config/ptdeploy/dapperfy/autopilots/generated/tiny-staging-invoke-code-no-dbconf.php",
                    "autopilot-test-invoke-dbconf-install-file" => "",
                    "autopilot-test-invoke-dbinstall-install-file" => "",
                    "autopilot-prod-invoke-install-file" => "build/config/ptdeploy/dapperfy/autopilots/generated/tiny-prod-invoke-code-no-dbconf.php",
                    "autopilot-prod-invoke-dbconf-install-file" => "" ,
                    "autopilot-prod-invoke-dbinstall-install-file" => "" ,
                    "data-handling-type" => "code",
                    "error-email" => "",
                    "only-autopilots" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Creating Autopilots to install a continuous staging to production build complete"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating Autopilots to install a continuous to staging build complete"),),),
                array ( "Builderfy" => array("continuous-staging" => array(
                    "jenkins-home" => "/var/lib/jenkins",
                    "target-job-name" => "{$this->params["job-name"]}-continuous-staging",
                    "new-job-dir" => "{$this->params["job-name"]}-continuous-staging",
                    "project-description" => "This is the Continuous Staging build for My Project, {$this->params["project-name"]}",
                    "primary-scm-url" => "{$this->params["scm-url"]}",
                    "source-branch-spec" => "origin/master",
                    "source-scm-url" => "{$this->params["scm-url"]}",
                    "days-to-keep" => "-1",
                    "amount-to-keep" => "10",
                    "autopilot-test-invoke-install-file" => "build/config/ptdeploy/dapperfy/autopilots/generated/tiny-staging-invoke-code-no-dbconf.php",
                    "autopilot-test-invoke-dbconf-install-file" => "",
                    "autopilot-test-invoke-dbinstall-install-file" => "",
                    "autopilot-prod-invoke-install-file" => "build/config/ptdeploy/dapperfy/autopilots/generated/tiny-prod-invoke-code-no-dbconf.php",
                    "autopilot-prod-invoke-dbconf-install-file" => "" ,
                    "autopilot-prod-invoke-dbinstall-install-file" => "" ,
                    "data-handling-type" => "code",
                    "error-email" => "",
                    "only-autopilots" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Creating Autopilots to install a continuous to staging build complete"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Let's create Autopilots to install a manual production build"),),),
                array ( "Builderfy" => array("manual-production" => array(
                    "jenkins-home" => "/var/lib/jenkins",
                    "target-job-name" => "{$this->params["job-name"]}-manual-production",
                    "new-job-dir" => "{$this->params["job-name"]}-manual-production",
                    "project-description" => "This is the Manual Production build for My Project, {$this->params["project-name"]}",
                    "primary-scm-url" => "{$this->params["scm-url"]}",
                    "source-branch-spec" => "origin/master",
                    "source-scm-url" => "{$this->params["scm-url"]}",
                    "days-to-keep" => "-1",
                    "amount-to-keep" => "10",
                    "autopilot-prod-invoke-install-file" => "build/config/ptdeploy/dapperfy/autopilots/generated/tiny-prod-invoke-code-no-dbconf.php",
                    "autopilot-prod-invoke-dbconf-install-file" => "" ,
                    "autopilot-prod-invoke-dbinstall-install-file" => "" ,
                    "data-handling-type" => "code",
                    "error-email" => "",
                    "only-autopilots" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Creating Autopilots to install a manual production build complete"),),),

        );

    }

    protected function getDefaults() {
        $this->getJobName() ;
        $this->getProjectName() ;
        $this->getSCMUrl() ;
    }

    protected function getJobName() {
        if (isset($this->params["job-name"])) { return $this->params["job-name"] ; }
        $question = 'Enter a Job Name (Jenkins Format) for your project' ;
        $this->params["job-name"] = self::askForInput($question, true) ;
    }

    protected function getProjectName() {
        if (isset($this->params["project-name"])) { return $this->params["project-name"] ; }
        $question = 'Enter a Human readable name for your project' ;
        $this->params["project-name"] = self::askForInput($question, true) ;
    }

    protected function getSCMUrl() {
        if (isset($this->params["scm-url"])) { return $this->params["scm-url"] ; }
        $question = 'Enter an SCM URL for your project' ;
        $this->params["scm-url"] = self::askForInput($question, true) ;
    }


}
