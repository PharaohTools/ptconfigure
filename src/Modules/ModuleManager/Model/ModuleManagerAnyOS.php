<?php

Namespace Model;

class ModuleManagerAnyOS extends BasePHPApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->setParameterOverrides() ;
        $this->autopilotDefiner = "ModuleManager";
        $this->fileSources = $this->getFileSources() ;
        $this->programNameMachine = "modulemanager"; // command and app dir name
        $this->programNameFriendly = " ModuleManager "; // 12 chars
        $this->programNameInstaller = "ModuleManager";
        $this->initialize();
    }

    protected function setParameterOverrides() {
        $this->params["module-manager"] = true ;
        $this->params["no-executor"] = true ;
        $ext_dir = dirname(dirname(dirname(__FILE__))) ;
        $ext_dir .= DIRECTORY_SEPARATOR."Extensions" ;
        $this->params["program-data-directory"] = $ext_dir ;
        var_dump($this->params) ;
    }

    protected function getFileSources() {
        $fileSources =
            array(
                array(
                    $this->getModuleSource(),
                    "module",
                    null // can be null for none
                )
            );
        return $fileSources ;
    }

    protected function getModuleSource() {
        if (isset($this->params['module-source'])) { return $this->params['module-source'] ; }
        $question = "Enter the Git Repository URL of your module:";
        return "" ; //self::askForInput($question, true);
    }

}