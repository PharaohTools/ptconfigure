<?php

Namespace Model;

class PharaohToolsUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04", "13.10", "14.04", "14.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PharaohTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("Dapperstrano")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("Testingkamen")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("Cleopatra")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharaoh", "params" => array("JRush")) ),
        );
        // @todo cleopatra wont uninstall itself, that sounds wrong and is unlikely to work anyway
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("Testingkamen")) ),
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("Dapperstrano")) ),
            array("method"=> array("object" => $this, "method" => "removePharaoh", "params" => array("JRush")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "pharaohtools"; // command and app dir name
        $this->programNameFriendly = "Pharaoh Tools"; // 12 chars
        $this->programNameInstaller = "Pharaoh Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray(array( "cleopatra", "dapperstrano", "testingkamen", "jrush" )) ;
    }

    public function ensurePharaoh($pharaoh) {
        $cname = '\Model\\'.$pharaoh;
        $pharaohFactory = new $cname();
        $pharaohModel = $pharaohFactory->getModel($this->params);
        $pharaohModel->ensureInstalled();
    }
}