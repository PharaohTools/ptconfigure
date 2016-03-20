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
//        var_dump($this->params) ;
        $this->autopilotDefiner = "ModuleManager";
        $this->fileSources = $this->getFileSources() ;
        $this->programNameMachine = "modulemanager"; // command and app dir name
        $this->programNameFriendly = " ModuleManager "; // 12 chars
        $this->programNameInstaller = "ModuleManager";
        $this->initialize();
    }

    protected function setParameterOverrides() {
        $ext_dir = dirname(dirname(dirname(dirname(__FILE__)))) ;
        $ext_dir .= DS."Extensions".DS.$this->getNameOfModuleToManage() ;
        $this->params["program-data-directory"] = $ext_dir ;
        $this->params["module-manager"] = true ;
        $this->params["no-executor"] = true ;
    }

    protected function getFileSources() {
        $ms = $this->getModuleSource() ;
        $fileSources[] =
            array(
                $ms,
                $this->getNameOfModuleToManage(),
                null, // branch, can be null
                true
            );
        return $fileSources ;
    }

    protected function getNameOfModuleToManage() {
        if (isset($this->params['module-name'])) { return $this->params['module-name'] ; }
        if (isset($this->params['name'])) { return $this->params['name'] ; }
    }

    protected function getModuleSource() {
        if (isset($this->params['module-source'])) { return $this->params['module-source'] ; }
        if (isset($this->params['source'])) { return $this->params['source'] ; }
        if (isset($this->params['repository'])) { return $this->params['repository'] ; }
        if (isset($this->params['repo'])) { return $this->params['repo'] ; }
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