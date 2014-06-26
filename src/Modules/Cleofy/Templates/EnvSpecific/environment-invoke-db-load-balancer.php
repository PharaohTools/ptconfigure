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
                    "log-message" => "Lets begin invoking Configuration of a HA Proxy based MySQL Load Balancer on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ) ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "First lets SFTP over our Papyrusfile, for environment details to confgure HA Proxy with",
                ), ), ),
                array ( "SFTP" => array( "put" =>  array(
                    "source" => getcwd()."/papyrusfile",
                    "target" => "/tmp/papyrusfile",
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>",
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Next lets SFTP over our DB Load Balancer CM Autopilot",
                ), ), ),
                array ( "SFTP" => array( "put" =>  array(
                    "source" => getcwd()."/build/config/cleopatra/cleofy/autopilots/generated/<%tpl.php%>env_name</%tpl.php%>-cm-db-load-balancer.php",
                    "target" => "/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-db-load-balancer.php",
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
                    "log-message" => "Invoking Configuration of a HA Proxy based MySQL Load Balancer on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ), ),
            );

    }


    private function setSSHData() {
        $sshData = <<<"SSHDATA"
cd /tmp
sudo cleopatra autopilot execute --autopilot-file="/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-db-load-balancer.php"
rm /tmp/<%tpl.php%>env_name</%tpl.php%>-cm-db-load-balancer.php
rm /tmp/papyrusfile
SSHDATA;
        return $sshData ;
    }

}
