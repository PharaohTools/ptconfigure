<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin creating Autopilots to install a remote continuous build"),),),

                array ( "Dapperfy" => array("joomla-tiny" => array(
                    "jenkins-home" => "/var/lib/jenkins",
                    // "target-job-name" => "my-project-continuous",
                    // "project-description" => "This is the Continuous Delivery build for My Project",
                    // "primary-scm-url" => "http://146.185.129.66:8080/git/root/first-pharaoh-cd.git",
                    "source-branch-spec" => "origin/master",
                    // "source-scm-url" => "http://146.185.129.66:8080/git/root/first-pharaoh-cd.git",
                    "days-to-keep" => "-1",
                    "amount-to-keep" => "10",
                    "autopilot-test-invoke-install-file" => "build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php",
                    "autopilot-prod-invoke-install-file" => "build/config/ptdeploy/autopilots/tiny-prod-invoke-code-no-dbconf.php",
                    "error-email" => "",
                    "template-directory" => "",
                    "only-autopilots" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating Autopilots to install a remote continuous build complete"),),),

        );

    }

}
