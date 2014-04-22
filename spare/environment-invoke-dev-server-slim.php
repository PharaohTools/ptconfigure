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
                array( "log-message" => "Lets begin invoking Configuration of Dev Server Slim on environment <%tpl.php%>env_name</%tpl.php%>"),
                ) ),
                array ( "Invoke" => array( "data" =>
                array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                array( "log-message" => "Invoking Dev Server Slim on environment <%tpl.php%>env_name</%tpl.php%> complete"),
                ) ),
            );

    }


    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo cleopatra install-package dev-server-slim --yes=true
SSHDATA;
        return $sshData ;
    }

}