<?php

Namespace Info;

class ProxmoxShellInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Proxmox Server Management Functions - API Version 2";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ProxmoxShell" => array_merge(parent::routesAvailable(), array('get', 'create',
          'set', 'delete', 'ls') ) );
    }

    public function routeAliases() {
      return array(
          "pvesh"=>"ProxmoxShell", "proxmoxshell"=>"ProxmoxShell",
          "proxshell"=>"ProxmoxShell", "proxapi"=>"ProxmoxShell");
    }

    public function boxProviderName() {
        return "Proxmox";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Servers on Proxmox.

    ProxmoxShell, proxmoxshell, pvesh, proxshell, proxapi

        - get, create, delete, set, ls
        Will destroy box/es in an environment for you, and remove them from the papyrus file
        example:
        
          ptconfigure pvesh get --path=""
        
        
          
                    --yes
                    --guess
                    --env=testenv # name of the environment to destroy boxes in
                    --destroy-box-id=3 # ID of single box in environment to destroy. This or below param must be set
                    --destroy-all-boxes # Destroy all boxes in environment. This or above param must be set

HELPDATA;
      return $help ;
    }

}