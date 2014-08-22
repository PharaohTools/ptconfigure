<?php

Namespace Model;

//@todo finish off the template vars
class PHPCIDefaultConfUbuntu extends BaseTemplater {

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
        $this->autopilotDefiner = "PHPCIDefaultConf";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "setDefaultReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setOverrideReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplateFile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplate", "params" => array()) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/PHPCIDefaultConf"; // command and app dir name
        $this->programNameMachine = "phpconf"; // command and app dir name
        $this->programNameFriendly = "PHP Conf!"; // 12 chars
        $this->programNameInstaller = "PHP Conf";
        $this->targetLocation = "/opt/phpci/phpci/PHPCI/config.yml" ;
        $this->initialize();
    }

    protected function setDefaultReplacements() {
        // set array with default values
        $this->replacements = array(
            "db_read_host" => "127.0.0.1",
            "db_write_host" => "127.0.0.1",
            "db_name" => "phpci",
            "db_username" => "phpci",
            "db_pass" => "phpci_pass",
            "phpci_url" => "http://www.phpci.local",
        ) ;
    }

    protected function setTemplateFile() {
        $this->templateFile = str_replace("Model", "Templates".DIRECTORY_SEPARATOR."PHPCI", dirname(__FILE__) ) ;
        $this->templateFile .= DIRECTORY_SEPARATOR."config.yml" ;
    }

}