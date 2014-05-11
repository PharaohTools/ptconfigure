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
                    "log-message" => "Lets begin invoking Configuration of Standalone App/DB Server on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ) ),
                array ( "SFTP" => array( "put" =>  array(
                    "source" => getcwd()."/build/config/cleopatra/autopilots/<%tpl.php%>env_name</%tpl.php%>-cm-git.php",
                    "target" => "/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-git.php",
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>",
                ), ), ),
                array ( "Logging" => array( "log" =>array(
                    "log-message" => "Lets run that autopilot"
                ), ), ),
                array ( "Invoke" => array( "data" => array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>",
                ), ), ),

                array ( "Logging" => array( "log" => array(
                    "log-message" => "Invoking Configuration of Standalone App/DB Server on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ), ),
            );

    }


    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo cleopatra install-package prod --yes=true
SSHDATA;
        return $sshData ;
    }

}
