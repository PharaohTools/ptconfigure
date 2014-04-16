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
                array( "log-message" => "Lets begin invoking PHP, Cleo amd Dapper on environment <%tpl.php%>env_name</%tpl.php%>"),
                ) ),
                array ( "Invoke" => array( "data" =>
                array(
                    "guess" => true,
                    "ssh-data" => $this->setSSHData(),
                    "environment-name" => "<%tpl.php%>env_name</%tpl.php%>"
                ),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                array( "log-message" => "Invoking PHP, Cleo and Dapper on environment <%tpl.php%>env_name</%tpl.php%> complete"),
                ) ),
            );

    }


    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo apt-get install -y php5 git
git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install-silent
sudo cleopatra dapperstrano install --yes=true
SSHDATA;
        return $sshData ;
    }

}
