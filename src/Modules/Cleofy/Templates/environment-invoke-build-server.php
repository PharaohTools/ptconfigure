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
                    array( "log-message" => "Lets begin invoking Configuration of a build server on environment <%tpl.php%>env_name</%tpl.php%>"),
                ) ),
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "First lets SFTP over our PHP and Git setup"),
                ) ),
                array ( "SFTP" => array( "copy" =>
                    array("original-file" => "<%tpl.php%>cleo_home</%tpl.php%>/php-git-setup.php" ),
                    array("target-file" => "<%tpl.php%>tmp_dir</%tpl.php%>/php-git-setup.php" ),
                    array("environment-name" => "<%tpl.php%>env_name</%tpl.php%>" ),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets begin invoking Configuration of a build server on environment <%tpl.php%>env_name</%tpl.php%>"),
                ) ),
                array ( "Invoke" => array( "data" =>
                    array("ssh-data" => $this->setSSHData() ),
                    array("environment-name" => "<%tpl.php%>env_name</%tpl.php%>" ),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets begin invoking Configuration of a build server on environment <%tpl.php%>env_name</%tpl.php%>"),
                ) ),
                array ( "Invoke" => array( "data" =>
                    array("ssh-data" => $this->setSSHData() ),
                    array("environment-name" => "<%tpl.php%>env_name</%tpl.php%>" ),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Invoking a build server on environment <%tpl.php%>env_name</%tpl.php%> complete"),
                ) ),
            );

    }

    private function prepGitPHP() {
        $sshData = <<<"SSHDATA"
sudo apt-get install git -y
sudo apt-get install php5 -y
cd <%tpl.php%>tmp_dir</%tpl.php%>
git clone <%tpl.php%>repo_url</%tpl.php%> <%tpl.php%>project_name</%tpl.php%>
cd <%tpl.php%>project_name</%tpl.php%>
sudo cleopatra autopilot execute build/config/cleopatra/autopilots/<%tpl.php%>env_name</%tpl.php%>-cm-build-server.php
SSHDATA;
        return $sshData ;
    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo apt-get install git -y
sudo apt-get install php5 -y
cd <%tpl.php%>tmp_dir</%tpl.php%>
git clone <%tpl.php%>repo_url</%tpl.php%> <%tpl.php%>project_name</%tpl.php%>
cd <%tpl.php%>project_name</%tpl.php%>
sudo cleopatra autopilot execute build/config/cleopatra/autopilots/<%tpl.php%>env_name</%tpl.php%>-cm-build-server.php
SSHDATA;
        return $sshData ;
    }

}
