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
sudo apt-get install -y php5 git
git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install-silent
sudo cleopatra dapperstrano install --yes=true
SSHDATA;
    }

}
