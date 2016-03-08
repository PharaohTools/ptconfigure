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
        $name = $this->getNameOfModuleToManage();
        $ext_dir = dirname(dirname(dirname(dirname(__FILE__)))) ;
        $ext_dir .= DS."Extensions".DS.$name ;
        $this->params["program-data-directory"] = $ext_dir ;
    }

    protected function getFileSources() {
        $this->setParameterOverrides() ;
        $ms = $this->getModuleSource() ;
        $name = substr($ms, strrpos($ms, "/"));
        $fileSources[] =
            array(
                $ms,
                $name,
                null // can be null for none
            );
        return $fileSources ;
    }

    protected function getNameOfModuleToManage() {
        if (isset($this->params['module-name'])) { return $this->params['module-name'] ; }
    }

    protected function getModuleSource() {
        if (isset($this->params['module-source'])) { return $this->params['module-source'] ; }
        // @todo this breaks webface
        // $question = "Enter the Git Repository URL of your module:";
        // return self::askForInput($question, true);
    }

    public function disableModule() {
        $appConfig = new \Model\AppConfig() ;
        $disabled_modules = $appConfig->getAppVariable("disabled_modules") ;
        if (!in_array($this->params['module-disable'], $disabled_modules)) {
            $appConfig->setAppVariable("disabled_modules", $this->params['module-disable'], true) ; }
        return true ;
    }

    public function enableModule() {
        $appConfig = new \Model\AppConfig() ;
        $disabled_modules = $appConfig->getAppVariable("disabled_modules") ;
        $new_disabled_modules = array_diff($disabled_modules, array($this->params['module-enable'])) ;
        $appConfig->setAppVariable("disabled_modules", $new_disabled_modules) ;
        return true ;
    }

}