<?php

Namespace Model;

class XCodeMac extends BaseLinuxApp {

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
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "xcodeInstall", "params" => array()) ),
        );
        $this->uninstallCommands = array( );
        $this->programDataFolder = "/opt/XCode"; // command and app dir name
        $this->programNameMachine = "xcode"; // command and app dir name
        $this->programNameFriendly = "XCode on OSx"; // 12 chars
        $this->programNameInstaller = "XCode for OSx";
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = 'httpd -M';
        $modsText = $this->executeAndLoad($modsTextCmd) ;
        $modsToCheck = array("fastcgi_module" ) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($modsToCheck as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("Apache Module {$modToCheck} does not exist.", $this->getModuleName()) ;
                $passing = false ; } }
        return $passing ;
    }

//    public function ensureXCode() {
//        $loggingFactory = new \Model\Logging();
//        $logging = $loggingFactory->getModel($this->params);
//        $logging->log("Ensuring XCode", $this->getModuleName()) ;
//        $params = $this->params ;
//        $params["file"] = "/tmp/mod_fastcgi-2.4.6/Makefile" ;
//        $params["search"] = "= /usr/local/apache2" ;
//        $params["replace"] = "= /usr/share/httpd" ;
//        $fileFactory = new \Model\File() ;
//        $file = $fileFactory->getModel($params) ;
//        $res[] = $file->performReplaceText();
//        $params = $this->params ;
//        $params["file"] = "/tmp/mod_fastcgi-2.4.6/Makefile" ;
//        $params["search"] = "CFLAGS=-arch x86_64." ;
//        $file = $fileFactory->getModel($params) ;
//        $res[] = $file->performShouldHaveLine();
//        return in_array(false, $res)==false ;
//    }
//
//    public function fastCgiMake() {
//        $loggingFactory = new \Model\Logging();
//        $logging = $loggingFactory->getModel($this->params);
//        $logging->log("Fast CGI Make", $this->getModuleName()) ;
//        chdir("/tmp/mod_fastcgi-2.4.6/");
//        $sys = new \Model\SystemDetectionAllOS(); // $sysFac->getModel($this->params) ;
//        $version = $sys->version ;
//        if (substr_count($sys->version, ".")==2) {
//            $rpos = strrpos($sys->version, ".") ;
//            $version = substr($sys->version, 0, $rpos) ; }
//        var_dump("syssvers", $version) ;
//        $comm = SUDOPREFIX."ln -s XcodeDefault.xctoolchain /Applications/Xcode.app/Contents/Developer/Toolchains/OSX{$version}.xctoolchain" ;
//        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
//        $res[] = ($rc["rc"] == true) ? true : false ;
//        $comm = "pwd" ;
//        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
//        $res[] = ($rc["rc"] == true) ? true : false ;
//        $comm = "make" ;
//        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
//        $res[] = ($rc["rc"] == true) ? true : false ;
//        return (!in_array(false, $res)) ;
//    }

    public function xcodeInstall() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Installing XCode CLI Tools", $this->getModuleName()) ;
        $comm = dirname(dirname(__FILE__))."/Templates/xcode-cli-tools-install.sh" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res = ($rc["rc"] == true) ? true : false ;
        return $res ;
    }

}