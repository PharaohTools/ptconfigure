<?php

/*************************************
 *      Generated Autopilot file      *
 *     ---------------------------    *
 *Autopilot Generated By PTDeploy *
 *     ---------------------------    *
 *************************************/

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
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Lets begin invoking Continuous Build installation on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ) ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Lets ssh over the build install command"
                ), ) ),
                array ( "Invoke" => array( "data" =>  array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Invoking Continuous Build installation on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ) ),
            );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
cd <%tpl.php%>gen_env_tmp_dir</%tpl.php%>
sudo rm -rf <%tpl.php%>jenkins-home</%tpl.php%>/jobs/<%tpl.php%>target-job-name</%tpl.php%>*
sudo ptdeploy builderfy continuous --yes --jenkins-home="<%tpl.php%>jenkins-home</%tpl.php%>" --target-job-name="<%tpl.php%>target-job-name</%tpl.php%>" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="<%tpl.php%>days_to_keep</%tpl.php%>" --amount-to-keep="<%tpl.php%>num_to_keep</%tpl.php%>" --autopilot-test-invoke-install-file="<%tpl.php%>autopilot-test-invoke-install-file</%tpl.php%>" --autopilot-prod-invoke-install-file="<%tpl.php%>autopilot-prod-invoke-install-file</%tpl.php%>" --error-email="<%tpl.php%>error-email</%tpl.php%>"
sudo service jenkins restart
SSHDATA;
        return $sshData ;
    }

}
