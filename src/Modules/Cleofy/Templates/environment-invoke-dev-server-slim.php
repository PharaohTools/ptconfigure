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
                array ( "Invoke" => array( "data" =>
                    array("ssh-data" => $this->setSSHData() ),
                    array("servers" => array(<%tpl.php%>gen_srv_array_text</%tpl.php%>), ),
                ) , ) ,
        );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo cleopatra install-package dev-server-slim --yes=true
SSHDATA;
    }

}