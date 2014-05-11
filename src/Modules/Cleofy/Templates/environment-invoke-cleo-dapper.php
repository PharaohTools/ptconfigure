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
                "log-message" => "Lets begin invoking Configuration of Cleo and Dapper on environment <%tpl.php%>env_name</%tpl.php%>"
            ), ), ),
            array ( "Logging" => array( "log" => array(
                "log-message" => "First lets SFTP over our Cleo Dapper CM Autopilot"
            ), ) ),
            array ( "SFTP" => array( "put" => array(
                "source" => "build/config/cleopatra/autopilots/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php" ),
                "target" => "/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php",
                "environment-name" => "<%tpl.php%>env_name</%tpl.php%>",
            ) , ) ,
            array ( "Logging" => array( "log" =>
                array( "log-message" => "Lets run that autopilot"),
            ) ),
            array ( "Invoke" => array( "data" =>
                array("ssh-data" => $this->setSSHData() ),
                array("environment-name" => "<%tpl.php%>env_name</%tpl.php%>" ),
            ) , ) ,
            array ( "Logging" => array( "log" =>
                array( "log-message" => "Invoking Cleo and Dapper on environment <%tpl.php%>env_name</%tpl.php%> complete"),
            ) ),
        );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo cleopatra autopilot install /tmp/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php
SSHDATA;
        return $sshData ;
    }

}