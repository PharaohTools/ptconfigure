<?php

Namespace Model;

class PapyrusEditorUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Editor") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PapyrusEditor";
        $this->installCommands = array( "apt-get install -y php-apc" );
        $this->uninstallCommands = array( "apt-get remove -y php-apc" );
        $this->programDataFolder = "/opt/PapyrusEditor"; // command and app dir name
        $this->programNameMachine = "papyruseditor"; // command and app dir name
        $this->programNameFriendly = "Papyrus Editor!"; // 12 chars
        $this->programNameInstaller = "Papyrus Editor";
        $this->initialize();
    }

}
