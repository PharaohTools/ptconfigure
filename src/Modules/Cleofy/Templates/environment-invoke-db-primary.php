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
                    "log-message" => "Lets begin invoking Configuration of a MySQL Master DB Server on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ) ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "First lets SFTP over our Mysql Master CM Autopilot",
                ), ), ),
                array ( "SFTP" => array( "put" =>  array(
                    "source" => getcwd()."/build/config/cleopatra/autopilots/<%tpl.php%>env_name</%tpl.php%>-cm-db-primary.php",
                    "target" => "/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-db-primary.php",
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
                    "log-message" => "Invoking Configuration of Mysql Master DB Server on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ), ),
            );

    }


    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo cleopatra autopilot install /tmp/<%tpl.php%>env_name</%tpl.php%>-cm-db-primary.php
SSHDATA;
        return $sshData ;
    }

}
