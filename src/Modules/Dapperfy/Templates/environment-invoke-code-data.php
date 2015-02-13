<?php

/*************************************
 *      Generated Autopilot file      *
 *     ---------------------------    *
 *Autopilot Generated By PTDeploy *
 *     ---------------------------    *
 *************************************/

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
                    "log-message" => "Lets begin invoking Code, Database Data and Data Configuration on environment <%tpl.php%>env_name</%tpl.php%>"
                ), ) ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "First lets SFTP over our Dapper Autopilot"
                ), ) ),
                array ( "SFTP" => array( "put" => array(
                    "guess" => true,
                    "source" => getcwd()."/build/config/ptdeploy/dapperfy/autopilots/generated/<%tpl.php%>env_name</%tpl.php%>-node-install-code-data.php",
                    "target" => "<%tpl.php%>gen_env_tmp_dir</%tpl.php%><%tpl.php%>env_name</%tpl.php%>-node-install-code-data.php",
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ) , ) , ) ,
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Lets run that autopilot"
                ), ) ),
                array ( "Invoke" => array( "data" =>  array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "Invoking Code, Database Data and Data Configuration on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ), ) ),
            );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
cd <%tpl.php%>gen_env_tmp_dir</%tpl.php%>
sudo ptdeploy autopilot execute --autopilot-file="<%tpl.php%>env_name</%tpl.php%>-node-install-code-data.php"
sudo rm <%tpl.php%>env_name</%tpl.php%>-node-install-code-data.php
SSHDATA;
        return $sshData ;
    }

}
