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
            array("method"=> array("object" => $this, "method" => "getFastCGITar", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "templateMakefile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "fastCgiMake", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "copyInSoFile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "ensureConfFastCgi", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "libxml2-dev")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "libapache2-mod-php5")) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->programDataFolder = "/opt/ApacheFastCGIModules"; // command and app dir name
        $this->programNameMachine = "apachefastcgimodules"; // command and app dir name
        $this->programNameFriendly = "Apache Fast CGI !"; // 12 chars
        $this->programNameInstaller = "Apache Fast CGI Module";
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

    public function apacheRestart() {
        $serviceFactory = new \Model\Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("httpd");
        $serviceManager->restart();
    }

    public function getFastCGITar() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (is_dir('/tmp/mod_fastcgi-2.4.6')) {
            $comm = 'rm -rf /tmp/mod_fastcgi-2.4.6' ;
            $logging->log("Removing already existing temp directory", $this->getModuleName()) ;
            $this->executeAndGetReturnCode($comm, true, true) ;  }
        $logging->log("Downloading Fast CGI Source.", $this->getModuleName()) ;
        $comm = 'cd /tmp/
curl http://www.fastcgi.com/dist/mod_fastcgi-2.4.6.tar.gz -o /tmp/mod_fastcgi-2.4.6.tar.gz
tar -xvzf /tmp/mod_fastcgi-2.4.6.tar.gz
cd /tmp/mod_fastcgi-2.4.6
cp /tmp/mod_fastcgi-2.4.6/Makefile.AP2 /tmp/mod_fastcgi-2.4.6/Makefile ' ;
        /*
         * sudo ln -s XcodeDefault.xctoolchain /Applications/Xcode.app/Contents/Developer/Toolchains/OSX$(sw_vers -productVersion).xctoolchain
         */
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res = ($rc["rc"] == true) ? true : false ;
        return $res ;
    }

    public function templateMakefile() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Updating Fast CGI Makefile.", $this->getModuleName()) ;
        $params = $this->params ;
        $params["file"] = "/tmp/mod_fastcgi-2.4.6/Makefile" ;
        $params["search"] = "= /usr/local/apache2" ;
        $params["replace"] = "= /usr/share/httpd" ;
        $fileFactory = new \Model\File() ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performReplaceText();
        $params = $this->params ;
        $params["file"] = "/tmp/mod_fastcgi-2.4.6/Makefile" ;
        $params["search"] = "CFLAGS=-arch x86_64." ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

    public function ensureXCode() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring XCode", $this->getModuleName()) ;
        $params = $this->params ;
        $params["file"] = "/tmp/mod_fastcgi-2.4.6/Makefile" ;
        $params["search"] = "= /usr/local/apache2" ;
        $params["replace"] = "= /usr/share/httpd" ;
        $fileFactory = new \Model\File() ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performReplaceText();
        $params = $this->params ;
        $params["file"] = "/tmp/mod_fastcgi-2.4.6/Makefile" ;
        $params["search"] = "CFLAGS=-arch x86_64." ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

    public function fastCgiMake() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Fast CGI Make", $this->getModuleName()) ;
        chdir("/tmp/mod_fastcgi-2.4.6/");
        $sys = new \Model\SystemDetectionAllOS(); // $sysFac->getModel($this->params) ;
        $version = $sys->version ;
        if (substr_count($sys->version, ".")==2) {
            $rpos = strrpos($sys->version, ".") ;
            $version = substr($sys->version, 0, $rpos) ; }
        var_dump("syssvers", $version) ;
        $comm = SUDOPREFIX."ln -s XcodeDefault.xctoolchain /Applications/Xcode.app/Contents/Developer/Toolchains/OSX{$version}.xctoolchain" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res[] = ($rc["rc"] == true) ? true : false ;
        $comm = "pwd" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res[] = ($rc["rc"] == true) ? true : false ;
        $comm = "make" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res[] = ($rc["rc"] == true) ? true : false ;
        return (!in_array(false, $res)) ;
    }

    public function copyinSoFile() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Copy In our .so file", $this->getModuleName()) ;
        $comm = "cp /tmp/mod_fastcgi-2.4.6/libs/mod_fastcgi.so /usr/libexec/apache2" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res = ($rc["rc"] == true) ? true : false ;
        return $res ;
    }

    public function ensureConfFastCgi() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Updating Httpd.conf", $this->getModuleName()) ;
        $params = $this->params ;
        $fileFactory = new \Model\File() ;
        $params["file"] = "/private/etc/apache2/httpd.conf" ;
        $params["search"] = "LoadModule fastcgi_module libexec/apache2/mod_fastcgi.so" ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

}