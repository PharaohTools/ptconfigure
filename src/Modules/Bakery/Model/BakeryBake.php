<?php

Namespace Model;

class BakeryBake extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default", 'Bake') ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Bakery";
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForBakeryInstallDirectory", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForBakeryInstallDirectory", "params" => array()) ),
            array("command" => PTVCOMM." auto x --af=".$this->getModuleVirtualizeAutoPath()." --vars=".$this->getVarsPath() ),
        );
        var_dump(PTVCOMM." auto x --af=".$this->getModuleVirtualizeAutoPath()." --vars=".$this->getVarsPath()) ;
        //@todo uninstall commands of bakery
        $this->uninstallCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForBakeryInstallDirectory", "params" => array()) ),
        );
        $this->programDataFolder = "/var/lib/jvm/jdk1.7";
        $this->programNameMachine = "bakery"; // command and app dir name
        $this->programNameFriendly = "Bakery Images"; // 12 chars
        $this->programNameInstaller = "Bakery Image Creation";
        $this->versionInstalledCommand = 'bakery -version 2>&1' ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy bakery" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy bakery" ;
        $this->initialize();
    }

    protected function askForBakeryInstallDirectory() {
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            return; }
        else if (isset($this->params["bakery-install-dir"])) {
            $this->programDataFolder = $this->params["bakery-install-dir"]; }
        else {
            $question = "Enter Bakery Install Directory (no trailing slash):";
            $this->programDataFolder = self::askForInput($question, true); }
    }

    public function versionInstalledCommandTrimmer($text) {
        $leftQuote = strpos($text, 'bakery version "') + 14 ;
        $rightQuote = strpos($text, '"', $leftQuote) ;
        $difference = $rightQuote - $leftQuote ;
        $done = substr($text, $leftQuote, $difference) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

//    public function initialiseTempVM() {
//        $done = substr($text, 53, 17) ;
//        return $done ;
//    }

//    public function versionRecommendedCommandTrimmer($text) {
//
//        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForBakeryInstallDirectory", "params" => array()) ),
//            array("command" => array(
//                "git clone https://bitbucket.org/phpengine/ptconfigure-oraclebakery7jdk32 /tmp/oraclejdk" ,
//                "cd /tmp/oraclejdk",
//                "tar -xvf jdk-1_7-32bit.tar.gz" ,
//                "mkdir -p ****PROGDIR****" ,
//                "cp -r /tmp/oraclejdk/jdk1.7.0_55/* ****PROGDIR****" ,
//                "rm -rf /tmp/oraclejdk" ,
//                "cd ****PROGDIR****",
//                "chmod a+x ****PROGDIR****",
//                'echo \'JAVA_HOME=****PROGDIR****\' >> /etc/profile',
//                'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/profile',
//                'echo \'export JAVA_HOME\' >> /etc/profile',
//                'echo \'export PATH\' >> /etc/profile',
//                'echo \'JAVA_HOME=****PROGDIR****\' >> /etc/bash.bashrc',
//                'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/bash.bashrc',
//                'echo \'export JAVA_HOME\' >> /etc/bash.bashrc',
//                'echo \'export PATH\' >> /etc/bash.bashrc',
//                SUDOPREFIX.'update-alternatives --install "/usr/bin/bakery" "bakery" "****PROGDIR****/bin/bakery" 1 ',
//                SUDOPREFIX.'update-alternatives --install "/usr/bin/bakeryc" "bakeryc" "****PROGDIR****/bin/bakeryc" 1 ',
//                SUDOPREFIX.'update-alternatives --install "/usr/bin/bakeryws" "bakeryws" "****PROGDIR****/bin/bakeryws" 1 ',
//                SUDOPREFIX.'update-alternatives --set bakery ****PROGDIR****/bin/bakery ',
//                SUDOPREFIX.'update-alternatives --set bakeryc ****PROGDIR****/bin/bakeryc ',
//                SUDOPREFIX.'update-alternatives --set bakeryws ****PROGDIR****/bin/bakeryws ',
//                '. /etc/profile' ) )
//        );
//        //@todo uninstall commands of bakery
//    }



    public function setpostinstallCommands() {
        $ray = array( ) ;
        $ray[]["command"][] = SUDOPREFIX.PTVCOMM." auto x --af=".$this->getModuleVirtualizeAutoPath()." --vars=".$this->getModuleVirtualizeAutoPath() ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }


    public function getModuleVirtualizeAutoPath($type = 'first-app') {
        $path = dirname(__DIR__).DS.'Autopilots'.DS.'PTConfigure'.DS.$type.'-bake.dsl.php' ;
        return $path ;
    }

    public function getVarsPath() {
        $path = dirname(__DIR__).DS.'Autopilots'.DS.'PTConfigure'.DS.'vars.php' ;
        return $path ;
    }


    /*
     *
     * #!/usr/bin/env bash
cd vanubu
ptvirtualize up now --mod --pro
ptvirtualize halt now --die-hard
ptvirtualize box package -yg \
	--name="Standard Ubuntu 14.04.2 64 bit Server Edition" \
	--vmname="vanillaubuntu1404264bitserveredition" \
	--group="ptvirtualize" \
	--description="This is an addition to the vanilla install of Ubuntu 14.04.2, 64Bit Architecture, Server Edition. This box contains the same configuration as that one, and also includes Virtualbox Guest Packages, PHP with some standard modules, and Pharaoh Configure." \
	--target="/opt/ptvirtualize/boxes"
ls -lah /opt/ptvirtualize/boxes/standard*
ptvirtualize destroy now

cd ..
cd /opt/ptvirtualize/boxes/
echo "Starting Rax Upload"
rack files object upload --container phlagrant-boxes --name standardubuntu1404264bitserveredition.box --file standardubuntu1404264bitserveredition.box
     */

}
