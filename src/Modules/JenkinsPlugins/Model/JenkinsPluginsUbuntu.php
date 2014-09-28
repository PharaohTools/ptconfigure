<?php

Namespace Model;

class JenkinsPluginsUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "JenkinsPlugins";
    $this->installCommands = array(
        array("command" => array(
                "cd /tmp" ,
            "rm -rf /tmp/jplugins",
                "git clone https://github.com/phpengine/jenkins-php-plugins jplugins",
                "rm -rf ****PROGDIR****",
                "mkdir -p ****PROGDIR****",
                "mv /tmp/jplugins/* ****PROGDIR****",
                "chmod -R 775 ****PROGDIR****/*",
                "chown -R jenkins ****PROGDIR****",
                "rm -rf /tmp/jplugins",
                "service jenkins restart" ) )
        );
    $this->uninstallCommands = array(
        array("command" => array(
                "rm -rf ****PROGDIR****".DIRECTORY_SEPARATOR."*",
                "service jenkins restart" ) )
        );
    $this->programDataFolder = "/var/lib/jenkins/plugins"; // command and app dir name
    $this->programNameMachine = "jenkinsplugins"; // command and app dir name
    $this->programNameFriendly = "Jenkns Plgs!"; // 12 chars
    $this->programNameInstaller = "Jenkins Plugins";
    $this->initialize();
  }

}