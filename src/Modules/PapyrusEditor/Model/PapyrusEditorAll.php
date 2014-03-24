<?php

Namespace Model;

class PapyrusEditorAll extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Editor") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PapyrusEditor";
        $this->installCommands = array();
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/PapyrusEditor"; // command and app dir name
        $this->programNameMachine = "papyruseditor"; // command and app dir name
        $this->programNameFriendly = "Papyrus Editor!"; // 12 chars
        $this->programNameInstaller = "Papyrus Editor";
        $this->initialize();
    }

    public function getPapyrus() {
        $isDefault = (isset($_REQUEST["papyrus_default_location"]) && $_REQUEST["papyrus_default_location"] != "none") ;
        if ($isDefault) {
            $pfile = $_REQUEST["papyrus_location"] = $_REQUEST["papyrus_default_location"] ; }
        else {
            $pfile = $_REQUEST["papyrus_location"] ; }
        $current = \Model\AppConfig::loadProjectFile($pfile) ;
        return $current ;
    }

    public function savePapyrus() {
        $config = $this->parseRequest() ;
        $pfile = $_REQUEST["papyrus_save_location"] ;
        \Model\AppConfig::saveProjectFile($config, $pfile) ;
        $current = \Model\AppConfig::loadProjectFile($pfile) ;
        return $current ;
    }

    private function parseRequest() {
        $parsed = $_REQUEST ;
        unset($parsed["papyrus_location"]);
        unset($parsed["papyrus_default_location"]);
        unset($parsed["control"]);
        unset($parsed["papyrus_save_location"]);
        unset($parsed["doSave"]);
        unset($parsed["action"]);
        unset($parsed["control"]);
        unset($parsed["output-format"]);
        return $parsed ;
    }

}
