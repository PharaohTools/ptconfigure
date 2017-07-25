<?php

Namespace Model;

class VirtualboxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Virtualbox";
        $this->installCommands = $this->getInstallCommands() ;
        // http://download.virtualbox.org/virtualbox/4.3.28/VirtualBox-4.3.28-100309-OSX.dmg
        $this->uninstallCommands = array(
            array("command" => array( SUDOPREFIX."apt-get remove -y virtualbox") ) ) ;
        $this->programDataFolder = "/var/lib/virtualbox"; // command and app dir name
        $this->programNameMachine = "virtualbox"; // command and app dir name
        $this->programNameFriendly = " ! Virtualbox !"; // 12 chars
        $this->programNameInstaller = "Virtualbox";
        $this->statusCommand = "command vboxmanage" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->initialize();
    }

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $dmgFile = BASE_TEMP_DIR."virtualbox.dmg" ;
        $ray = array(
            array("command" => array( SUDOPREFIX."rm -rf $dmgFile") ),
            array("command" => array( 'curl "http://download.virtualbox.org/virtualbox/5.1.22/VirtualBox-5.1.22-115126-OSX.dmg" -o "'.$dmgFile.'"') ),
//            array("command" => array( 'curl "http://download.virtualbox.org/virtualbox/4.3.28/VirtualBox-4.3.28-100309-OSX.dmg" -o "'.$dmgFile.'"') ),
            array("command" => array( SUDOPREFIX."hdiutil attach $dmgFile") ),
            array("command" => array( SUDOPREFIX.'installer -pkg /Volumes/VirtualBox/VirtualBox.pkg -target /') ),
            array("method"=> array("object" => $this, "method" => "ensureDefaultHostOnlyNetwork", "params" => array()) ),
            array("command" => array( SUDOPREFIX."hdiutil unmount /Volumes/VirtualBox/VirtualBox.pkg") ),
        ) ;
        if (isset($this->params["with-guest-additions"]) && $this->params["with-guest-additions"]==true) {
            $logging->log("Virtualbox Guest additions have been requested by parameter, but are installed by default on OSx", $this->getModuleName()) ;
//            array_push($ray, array("command" => array( SUDOPREFIX."apt-get install -y virtualbox-guest-additions-iso") ) ) ;
        }
        return $ray ;
    }

    public function ensureDefaultHostOnlyNetwork() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $comm = VBOXMGCOMM.'list hostonlyifs' ;
        $out = $this->executeAndLoad($comm);
//        $out = str_replace("\n", "", $out) ;
//        $out = str_replace("\r", "", $out) ;
        if (strpos($out, "vboxnet0") !== false) {
            $logging->log("Default host only network vboxnet0 was found, no need to create.", $this->getModuleName()) ;  }

        else {
            $logging->log("Default host only network vboxnet0 was not found, attempting to create.", $this->getModuleName()) ;
            $c1 = VBOXMGCOMM.'hostonlyif create' ;
            $out = $this->executeAndGetReturnCode($c1, true, true);
            if ($out["rc"]!==0) { $logging->log("Possible error during vboxnet0 creation.", $this->getModuleName()) ; }
            $c2 = VBOXMGCOMM.'hostonlyif ipconfig vboxnet0 --ip 192.168.56.1' ;
            $out = $this->executeAndGetReturnCode($c2, true, true);
            if ($out["rc"]!==0) { $logging->log("Possible error during vboxnet0 creation.", $this->getModuleName()) ; }
            $comm = VBOXMGCOMM.'list hostonlyifs' ;
            $out = $this->executeAndLoad($comm);
            if (strpos($out, "vboxnet0") === false) {
                \Core\BootStrap::setExitCode(1);
                $logging->log("Unable to create Default host only network vboxnet0 ", $this->getModuleName()) ;
                return false ; }
            $logging->log("Successfully created Default host only network vboxnet0 ", $this->getModuleName()) ; }

        $c1 = VBOXMGCOMM.'dhcpserver add --ifname vboxnet0 --ip 192.168.56.1 --netmask 255.255.255.0 --lowerip 192.168.56.100 --upperip 192.168.56.200' ;
        $out = $this->executeAndGetReturnCode($c1, true, true);
        if ($out["rc"]!==0) { $logging->log("Possible error while creating DHCP server for vboxnet0.", $this->getModuleName()) ; }
        $c2 = VBOXMGCOMM.'dhcpserver modify --ifname vboxnet0 --enable' ;
        $out = $this->executeAndGetReturnCode($c2, true, true);
        if ($out["rc"]!==0) { $logging->log("Possible error while adding interface vboxnet0 to DHCP server.", $this->getModuleName()) ; }
        $logging->log("Successfully added DHCP server to Default host only network vboxnet0.", $this->getModuleName()) ;
        return true ;
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 23, 15) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 42, 23) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 42, 23) ;
        return $done ;
    }

}