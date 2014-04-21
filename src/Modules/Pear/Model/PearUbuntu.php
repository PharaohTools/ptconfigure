<?php

Namespace Model;

class PearUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "pear";

    public $actionsToMethods =
        array(
            "pkg-install" => "performInstall",
            "pkg-ensure" => "performInstall",
            "pkg-remove" => "performRemove",
            "pkg-exists" => "performExistenceCheck",
            "channel-discover" => "channelDiscover",
            "channel-delete" => "channelDelete",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Pear";
        $this->programDataFolder = "";
        $this->programNameMachine = "pear"; // command and app dir name
        $this->programNameFriendly = "!Pear!!"; // 12 chars
        $this->programNameInstaller = "Pear";
        $this->initialize();
    }

    public function isInstalled($packageName) {
        $out = $this->executeAndLoad("sudo pear list -i | grep {$packageName}") ;
        return (strpos($out, $packageName) != false) ? true : false ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo pear install -f $packageName");
        if (!is_int(strpos($out, "install ok"))) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Adding Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo pear uninstall $packageName");
        if (!is_int(strpos($out, "uninstall ok"))) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Removing Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function channelDiscover() {
        $channel = $this->setChannel();
        $out = $this->executeAndLoad("sudo pear channel-discover $channel");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        // var_dump($out, 'Channel "'.$channel.'" is already initialized') ;
        $initString = 'Channel "'.$channel.'" is already initialized'."\n" ;
        if ($out == $initString) {
            $logging->log("Not adding Channel $channel in Packager Pear as it is already initialized") ;
            return true ; }
        else if (strpos($out, "Adding Channel \"".$channel."\" succeeded") == false ||
            strpos($out, "Discovery of channel \"".$channel."\" succeeded") == false ) {
            $logging->log("Discovering Channel $channel in Packager Pear did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function channelDelete() {
        $channel = $this->setChannel();
        $out = $this->executeAndLoad("sudo pear channel-del $channel");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $initString = 'channel-delete: channel "'.$channel.'" does not exist'."\n" ;
        if ($out == $initString) {
            $logging->log("Not removing Channel $channel in Packager Pear as it is already initialized") ;
            return true ; }
        else if (strpos($out, "Channel \"".$channel."\" deleted") == false ) {
            $logging->log("Discovering Channel $channel in Packager Pear did not execute correctly") ;
            return false ; }
        return true ;
    }

    protected function setChannel($channel = null) {
        if (isset($channel)) {  }
        else if (isset($this->params["pear-channel"])) {
            $channel = $this->params["pear-channel"]; }
        else {
            $channel = self::askForInput("Enter Pear Channel:", true); }
        return $channel ;
    }

}