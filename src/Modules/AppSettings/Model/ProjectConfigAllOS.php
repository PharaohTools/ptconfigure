<?php

Namespace Model;

class ProjectConfigAllOS extends Base  {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("ProjectConfig") ;

    public function askWhetherToSetConfig() {
        return $this->performConfigSetting();
    }

    public function askWhetherToGetConfig() {
        return $this->performConfigGetting();
    }

    public function askWhetherToListConfigs() {
        return $this->performConfigListing();
    }

    public function askWhetherToDeleteConfig() {
        return $this->performConfigDeletion();
    }

    private function performConfigSetting() {
        $doConf = $this->askForConfAppSettingsToScreen();
        if ($doConf != true) { return false; }
        $confVarToSet = $this->getChosenConfVar();
        $confValueToSet = $this->askForConfValue();
        self::writeSettingToAppFile($confVarToSet, $confValueToSet);
        return "Seems Fine...";
    }

    private function performConfigGetting() {
        $doConf = $this->askForConfAppSettingsToScreen();
        if ($doConf != true) { return false; }
        $confVarToFind = $this->getChosenConfVar();
        $confValueFound = $this->findCurrentConfValue($confVarToFind);
        return $confValueFound;
    }

    private function performConfigListing() {
        $doConf = $this->askForConfAppSettingsToScreen();
        if ($doConf != true) { return false; }
        $allConfVars = $this->findAllSetAndUnsetConfValues();
        return $allConfVars ;
    }

    private function performConfigDeletion() {
        $doConf = $this->askForConfAppSettingsToScreen();
        if ($doConf != true) { return false; }
        $confVarToDelete = $this->getChosenConfVar();
        self::deleteSettingFromAppFile($confVarToDelete);
        return "Seems Fine...";
    }

    private function getChosenConfVar() {
        $question = 'What\'s the App Config Variable?'."\n";
        $allAppConfVars = array_merge(array("**ENTER PLAIN TEXT**"), array_keys($this->getAppConfVarList())) ;
        $varName = self::askForArrayOption($question, $allAppConfVars, true);
        if ($varName=="**ENTER PLAIN TEXT**") {
            $question = 'App Config Variable Name Text?';
            $varName = self::askForInput($question, true); }
        $varName = (isset($varName)) ? $varName : self::askForInput($question, true);
        return $varName ;
    }

    private function askForConfAppSettingsToScreen() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) {
            return true ; }
        $question = 'Do you want to Configure Application Settings?';
        return self::askYesOrNo($question);
    }

    private function getAppConfVarList() {
        $appConfVars = array("mysql-admin-user"=>false, "mysql-admin-host"=>false, "mysql-admin-pass"=>false) ;
        $appConfVars = array_merge($appConfVars, array("linux-user"=>false, "linux-user-dir"=>false, "program-dir"=>false) );
        $appConfVars = array_merge($appConfVars, array("temp-base-dir"=>false, "distro"=>false, "op-sys"=>false) );
        $appConfVars = array_merge($appConfVars, array("linux-type"=>false) );
        $appConfVars = array_merge($appConfVars, array() ); // add more lines like this when needed
        return $appConfVars;
    }

    private function askForConfValue() {
        $question = 'What Value do you want to give this variable?' ;
        return self::askForInput($question, true) ;
    }

    private function findCurrentConfValue($varNameToCheck) {
        if (\Model\AppConfig::getAppVariable($varNameToCheck) != null ){
            $currentConf  = "Variable Name: ".$varNameToCheck."\n";
            $currentConf .= "Variable Value: ".\Model\AppConfig::getAppVariable($varNameToCheck)."\n"; }
        else {
            $currentConf = "There is no value set for variable $varNameToCheck" ; }
        return $currentConf ;
    }

    private function findAllSetAndUnsetConfValues() {
        $allSetAndUnset = array();
        $allSetAndUnset["allSet"] = \Model\AppConfig::getAllAppVariables() ;
        $allSetAndUnset["allTotal"] = $this->getAppConfVarList();
        return $allSetAndUnset ;
    }

    private function writeSettingToAppFile($confVar, $confValue) {
        $allAppConfVars = $this->getAppConfVarList() ;
        $listAdd = $allAppConfVars[$confVar] ;
        \Model\AppConfig::setAppVariable($confVar, $confValue, $listAdd);
    }

    private function deleteSettingFromAppFile($confVar) {
        \Model\AppConfig::deleteAppVariable($confVar);
    }

}