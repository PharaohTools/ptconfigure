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
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Lets SSH our PHP and Git status initializer on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Invoke" => array( "data" => array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Logging" => array( "log" => array(
                     "log-message" => "Invoking a PHP initial install on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ), ),
            );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
cat /etc/os-release | grep ID="ubuntu" && sudo apt-get update
cat /etc/os-release | grep ID="ubuntu" && sudo apt-get install php5 git -y
cat /etc/os-release | grep ID="centos" && sudo yum update
cat /etc/os-release | grep ID="centos" && sudo yum install php git -y
cat /etc/os-release | grep ID="fedora" && sudo yum update
cat /etc/os-release | grep ID="fedora" && sudo yum install php git -y
cat /etc/os-release | grep ID="rhel" && sudo yum update
cat /etc/os-release | grep ID="rhel" && sudo yum install php git -y
SSHDATA;
        return $sshData ;
    }
}
