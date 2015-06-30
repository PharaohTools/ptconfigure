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
                    "log-message" => "Lets begin invoking Configuration of an Updated Pharaoh Configure and Pharaoh Deploy on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "First lets SFTP over our Pharaoh Configure Pharaoh Deploy CM Autopilot"
                ), ) ),
                array ( "SFTP" => array( "put" => array(
                    "guess" => true,
                    "source" => getcwd()."/build/config/ptconfigure/cleofy/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php",
                    "target" => "/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php",
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>",
                ) , ) , ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Lets run that autopilot"
                ), ) ),
                array ( "Invoke" => array( "data" =>  array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Invoking an update of Pharaoh Configure and Pharaoh Deploy on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ) ),
            );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo ptconfigure ptconfigure install --yes --guess
sudo ptconfigure ptdeploy install --yes --guess
SSHDATA;
        return $sshData ;
    }

}