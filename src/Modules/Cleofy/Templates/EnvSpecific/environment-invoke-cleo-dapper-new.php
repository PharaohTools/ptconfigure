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
                "log-message" => "Lets begin invoking Configuration of a new Cleo and Dapper on environment <%tpl.php%>env_name</%tpl.php%>"
            ), ), ),
            array ( "Logging" => array( "log" => array(
                "log-message" => "First lets SFTP over our Cleo Dapper CM Autopilot"
            ), ) ),
            array ( "SFTP" => array( "put" => array(
                "source" => getcwd()."/build/config/cleopatra/cleofy/autopilots/generated/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php",
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
                "log-message" => "Invoking a new Cleo and Dapper on environment <%tpl.php%>env_name</%tpl.php%> complete"
            ), ) ),
        );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
cd /tmp
git clone http://git.pharaoh-tools.com/git/phpengine/cleopatra.git
sudo php cleopatra/install-silent
sudo cleopatra autopilot execute --autopilot-file="/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php"
rm /tmp/<%tpl.php%>env_name</%tpl.php%>-cm-cleo-dapper.php
SSHDATA;
        return $sshData ;
    }

}