<?php

Namespace Model;

class PharaohToolsAllOS extends BaseLinuxApp {

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
        $this->autopilotDefiner = "PharaohTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("PTVirtualize")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("PTBuild")) ),
            //array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("PTConfigure")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("PTDeploy")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("PTTest")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("JRush")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("PTVirtualize")) ),
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("PTBuild")) ),
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("PTDeploy")) ),
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("PTTest")) ),
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("JRush")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "pharaohtools"; // command and app dir name
        $this->programNameFriendly = "Pharaoh Tools"; // 12 chars
        $this->programNameInstaller = "Pharaoh Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray(array(
            "ptbuild", "ptconfigure", "ptdeploy", "pttest", "pttrack", "ptvirtualize", "jrush"
        ) ) ;
    }

    public function ensurePharaoh($pharaoh) {
        $cname = '\Model\\'.$pharaoh;
        $pharaohFactory = new $cname();
        $pharaohModel = $pharaohFactory->getModel($this->params);
        $pharaohModel->ensureInstalled();
    }
}