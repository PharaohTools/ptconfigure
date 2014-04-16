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
          array ( "InvokeSSH" => array(
            "sshInvokeSSHDataExecute" => true,
            "sshInvokeSSHDataData" => "",
            "sshInvokeServers" => array(
              <%tpl.php%>gen_srv_array_text</%tpl.php%>
            ),
          ) , ) ,
        );

    }

    private function setSSHData() {
        $sshData = <<<"SSHDATA"
sudo cleopatra install-package prod --yes=true
SSHDATA;
    }

}
