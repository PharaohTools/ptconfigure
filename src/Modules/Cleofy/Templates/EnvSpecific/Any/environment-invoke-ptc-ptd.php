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
                "log-message" => "Lets begin invoking Configuration of a new Pharaoh Configure and Pharaoh Deploy on environment <%tpl.php%>env_name</%tpl.php%>"
            ), ), ),
            array ( "Logging" => array( "log" => array(
                "log-message" => "First lets SFTP over our Pharaoh Configure Pharaoh Deploy CM Autopilot"
            ), ) ),
            array ( "SFTP" => array( "put" => array(
                "guess" => true,
                "source" => getcwd()."/build/config/ptconfigure/cleofy/autopilots/generated/<%tpl.php%>env_name</%tpl.php%>-cm-ptc-ptd.php",
                "target" => "/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-ptc-ptd.php",
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
                "log-message" => "Invoking a new Pharaoh Configure and Pharaoh Deploy on environment <%tpl.php%>env_name</%tpl.php%> complete"
            ), ) ),
        );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
cd /tmp
git clone https://github.com/PharaohTools/ptconfigure.git
sudo php ptconfigure/install-silent
sudo ptconfigure autopilot execute --autopilot-file="/tmp/<%tpl.php%>env_name</%tpl.php%>-cm-ptc-ptd.php"
rm /tmp/<%tpl.php%>env_name</%tpl.php%>-cm-ptc-ptd.php
SSHDATA;
        return $sshData ;
    }

}