<?php

Namespace Model;

class PharoahToolsUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "PharoahTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "ensurePharoah", "params" => array("Dapperstrano")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharoah", "params" => array("Testingkamen")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharoah", "params" => array("Cleopatra")) ),
            array("method"=> array("object" => $this, "method" => "ensurePharoah", "params" => array("JRush")) ),
        );
        // @todo cleopatra wont uninstall itself, that sounds wrong and is unlikely to work anyway
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "removePharoah", "params" => array("Testingkamen")) ),
            array("method"=> array("object" => $this, "method" => "removePharoah", "params" => array("Dapperstrano")) ),
            array("method"=> array("object" => $this, "method" => "removePharoah", "params" => array("JRush")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "pharoahtools"; // command and app dir name
        $this->programNameFriendly = "Pharoah Tools"; // 12 chars
        $this->programNameInstaller = "Pharoah Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray(array( "cleopatra", "dapperstrano", "testingkamen", "jrush" )) ;
    }

    public function ensurePharoah($pharoah) {
        $cname = '\Model\\'.$pharoah;
        $pharoahFactory = new $cname();
        $pharoahModel = $pharoahFactory->getModel($this->params);
        $pharoahModel->ensureInstalled();
    }
}