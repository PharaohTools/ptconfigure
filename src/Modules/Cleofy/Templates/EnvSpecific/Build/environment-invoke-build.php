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
                    "log-message" => "Lets begin invoking Configuration of a build server on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "First lets SFTP over our Build Server CM Autopilot"
                ), ), ),
                array ( "SFTP" => array( "put" => array(
                    "guess" => true,
                    "source" => getcwd()."/build/config/ptconfigure/cleofy/<%tpl.php%>env_name</%tpl.php%>-cm-build.php" ,
                    "target" => "/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-build.php",
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>",
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Next lets SFTP over our Papyrus File"
                ), ), ),
                array ( "SFTP" => array( "put" => array(
                    "guess" => true,
                    "source" => getcwd()."/papyrusfile" ,
                    "target" => "/tmp/papyrusfile",
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>",
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Lets run that autopilot"
                ), ), ),
                array ( "Invoke" => array( "data" => array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Invoking a build server on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ), ),
            );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
cd /tmp
sudo ptconfigure autopilot execute --autopilot-file="/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-build.php"
rm /tmp/<%tpl.php%>env_name</%tpl.php%>-cm-build.php
SSHDATA;
        return $sshData ;
    }

}
