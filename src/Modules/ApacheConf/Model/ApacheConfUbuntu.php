<?php

Namespace Model;

//@todo finish off the template vars
class ApacheConfUbuntu extends BaseTemplater {

  // Compatibility
  public $os = array("Linux") ;
  public $linuxType = array("Debian") ;
  public $distros = array("Ubuntu") ;
  public $versions = array("12.04", "12.10") ;
  public $architectures = array("any") ;

  // Model Group
  public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "setDefaultReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setOverrideReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplateFile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplate", "params" => array()) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/ApacheConf"; // command and app dir name
        $this->programNameMachine = "apacheconf"; // command and app dir name
        $this->programNameFriendly = "Apache Conf!"; // 12 chars
        $this->programNameInstaller = "Apache Conf";
        $this->targetLocation = "/etc/apache2/apache2.conf" ;
        $this->initialize();
    }

    protected function setDefaultReplacements() {
        // set array with default values
        $this->replacements = array(
            "LockFile" => '${APACHE_LOCK_DIR}/accept.lock',
            "PidFile" => '${APACHE_PID_FILE}',
            "Timeout" => '300',
            "KeepAlive" => 'On',
            "MaxKeepAliveRequests" => '100',
            "KeepAliveTimeout" => '5',
        ) ;
    }

    protected function setTemplateFile() {
        $this->templateFile = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        $this->templateFile .= DIRECTORY_SEPARATOR."apache2.conf" ;
    }

}