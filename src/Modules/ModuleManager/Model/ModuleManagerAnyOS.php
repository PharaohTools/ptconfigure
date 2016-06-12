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

    public function initialize() {
        $this->populateTitle();
        $ms = $this->getModuleSource() ;
        // $this->versionInstalledCommand = $this->executorPath." log --git-dir=".$this->getModuleDirectory().".git --work-tree=".$this->getModuleDirectory()." --pretty=format:'%H' -n 1" ;
        $this->versionInstalledCommand = $this->executorPath." --git-dir '".$this->getModuleDirectory().DS.".git".DS."' log --pretty=format:'%H' -n 1" ;
        $this->versionRecommendedCommand = $this->executorPath.' ls-remote '.$ms.' | head -1 | sed "s/HEAD//"';
        $this->versionLatestCommand = $this->executorPath.' ls-remote '.$ms.' | head -1 | sed "s/HEAD//"';
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
        return false ;
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

    // @todo refactor this into multiple methods
    public function askStatus() {
        // @todo also use install flag status from methods setInstallFlagStatus getInstallFlagStatus
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $mn = $this->getNameOfModuleToManage() ;
        $md = $this->getModuleDirectory() ;
        $logging->log("Looking for Module Directory {$md}", $this->getModuleName()) ;
        if ( file_exists($md)  ) {
            $logging->log("Looking for Module Directory {$md}", $this->getModuleName()) ;
            if ( is_dir($md)  ) {
                $logging->log("{$md} is a Directory as expected", $this->getModuleName()) ;
                $mok = $this->moduleIsOkay() ;
                if ($mok === false) {
                    $logging->log("Unable to verify this module is in a usable state", $this->getModuleName()) ; }
                return $mok ; }
            else {
                $logging->log("{$md} is not a Directory", $this->getModuleName()) ;
                $status = false ; } }
        else {
            $logging->log("Unable to find Module Directory {$md}", $this->getModuleName()) ;
            $status = false ; }
        $inst = ($status == true) ? "Installed" : "Not Installed, or Not Installed correctly " ;
        $logging->log("ModuleManager Reports that Module ".$mn." is {$inst}", $this->getModuleName()) ;
        return $status ;
    }

    protected function moduleIsOkay() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $mn = $this->getNameOfModuleToManage() ;
        $md = $this->getModuleDirectory() ;
        $entries = scandir($md) ;
        $logging->log("Checking Validity of Module ".$mn, $this->getModuleName()) ;
        $status = true ;
        if (in_array('Controller', $entries)) {
            $logging->log("Found Controller Directory as expected ".$mn, $this->getModuleName()) ; }
        else {
            $logging->log("Unable to find Controller Directory as expected ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            $status = false ; }
        if (in_array('Model', $entries)) {
            $logging->log("Found Model Directory as expected ".$mn, $this->getModuleName()) ; }
        else {
            $logging->log("Unable to find Model Directory as expected ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            $status = false ; }
        if (in_array('info.'.$mn.'.php', $entries)) {
            $logging->log("Found Info file as expected ".$mn, $this->getModuleName()) ; }
        else {
            $logging->log("Unable to find Info file as expected ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            $status = false ; }
        $logging->log("Module structure valid", $this->getModuleName()) ;
        return $status ;
    }

    protected function getModuleDirectory() {
        $mn = $this->getNameOfModuleToManage() ;
        $app_root = dirname(dirname(dirname(__DIR__))) ;
        $mod_dir = $app_root.DS.'Extensions'.DS.$mn ;
        return $mod_dir ;
    }

    public function versionInstalledCommandTrimmer($text) {
//        $done = trim($text, "\n") ;
//        $done = trim($done, "\r") ;
        $done = trim($text) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
//        $done = trim($text, "\n") ;
//        $done = trim($done, "\r") ;
        $done = trim($text) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
//        $done = trim($text, "\n") ;
//        $done = trim($done, "\r") ;
        $done = trim($text) ;
        return $done ;
    }
}