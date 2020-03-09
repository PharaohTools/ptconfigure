<?php

//
// Created by Pharaoh Virtualize at 18:44:55 06/03/2020
//


Namespace Model ;

class Virtufile extends VirtufileBase {

    public $config ;

    public function __construct() {
        $this->setConfig();
    }

    private function setConfig() {
        $this->setDefaultConfig();
        $this->config["vm"]["name"] = "<%tpl.php%>vm_name</%tpl.php%>" ;
        $this->config["vm"]["gui_mode"] = "gui" ;
        $this->config["vm"]["box"] = "<%tpl.php%>box</%tpl.php%>" ;
        $this->config["vm"]["box_url"] = "<%tpl.php%>box_url</%tpl.php%>" ;


        # Shared folder - This should map to the workstation environment vhost path parent...
        $this->config["vm"]["shared_folders"][] =
            array(
                "name" => "host_www",
                "host_path" => getcwd().DS,
                "guest_path" => "/var/www/hostshare/",
                'symlinks' => 'enable'
            ) ;

        # Provisioning
        $this->config["vm"]["defaults"] = ['ga', 'mountshares', 'ptc'] ;

        $this->config["vm"]["post_up_message"] = "Your Virtualize Box has been brought up. This box is configured to be " .
            "provisioned by PTConfigure's default Virtualize provisioning.";
    }

}