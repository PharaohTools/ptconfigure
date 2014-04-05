<?php

Namespace Info;

class InvokeInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "SSH Invocation Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Invoke" => array_merge(parent::routesAvailable(), array("cli", "script", "autopilot") ) );
    }

    public function routeAliases() {
      return array("invoke" => "Invoke", "inv" => "Invoke");
    }

    public function autoPilotVariables() {
      return array(
        "InvokeSSH" => array(
          "sshInvokeSSHScriptExecute" => array(
            "sshInvokeSSHScriptExecute" => "boolean",
            "sshInvokeSSHScriptFile" => "string",
            "sshInvokeServers" => "string-array", ) ,
          "sshInvokeSSHDataExecute" => array(
            "sshInvokeSSHDataExecute" => "boolean",
            "sshInvokeSSHDataData" => "string",
            "sshInvokeServers" => array("target", "user", "pword"), ) ,
        ) ,
      );
    }

  public function generatorCodeInjection($step=null) {
    $inject = <<<'HELPDATA'
      /*
      //
      // This function will set the sshInvokeSSHDataData variable with the data that
      // you need in it. Call this in your constructor
      //
      private function setSSHData() {
        $step =
HELPDATA
    ;
    $inject .= ' "'.$step.'";' ;
    $inject .= <<<'HELPDATA'

        $this->steps[$step]["InvokeSSH"]["sshInvokeSSHDataData"] = <<<"SSHDATA"
script line 1
script line 2
script line 3
SSHDATA
;
      }
      */
HELPDATA
    ;
    return $inject ;
  }


  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles SSH Connection Functions.

  Invoke, invoke, inv

        - cli
        Will ask you for details for servers, then open a shell for you to execute on multiple servers
        example: cleopatra invoke shell

        - script
        Will ask you for details for servers, then execute each line of a provided script file on the remote/s
        example: cleopatra invoke script script-file

        - autopilot
        execute each line of a script file, multiple script files, or php variable data on one or more remotes
        example: cleopatra invoke autopilot autopilot-file

HELPDATA;
      return $help ;
    }

}