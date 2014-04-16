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
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets begin invoking a build server on environment <%tpl.php%>env_name</%tpl.php%>"),
                ) ),
                array ( "Invoke" => array( "data" =>
                    array("ssh-data" => $this->setSSHData() ),
                    array("environment-name" => "<%tpl.php%>env_name</%tpl.php%>" ),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Invoking a build server on environment <%tpl.php%>env_name</%tpl.php%> complete"),
                ) ),
        );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo cleopatra cleopatra install --yes=true
SSHDATA;
        return $sshData ;
    }

}
