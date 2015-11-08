<?php

Namespace Model;

class PHPLdapAdminUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPLdapAdmin";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "phpldapadmin")) ),
            array("method"=> array("object" => $this, "method" => "copyVHostConfig", "params" => array())),
            array("method"=> array("object" => $this, "method" => "templateVHostConfig", "params" => array())),
            array("method"=> array("object" => $this, "method" => "enableVhost", "params" => array())),
            array("method"=> array("object" => $this, "method" => "templateLDAPAdminConfig", "params" => array())),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "phpldapadmin")) ),
            array("method"=> array("object" => $this, "method" => "delInitScript", "params" => array())),
            array("method"=> array("object" => $this, "method" => "haproxyRestart", "params" => array())),
        );
        $this->programDataFolder = "/opt/PHPLdapAdmin"; // command and app dir name
        $this->programNameMachine = "phpldapadmin"; // command and app dir name
        $this->programNameFriendly = "PHP LDAP Admin!"; // 12 chars
        $this->programNameInstaller = "PHP LDAP Admin";
        $this->statusCommand = SUDOPREFIX."phpldapadmin -v" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy phpldapadmin" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy phpldapadmin" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy phpldapadmin" ;
        $this->initialize();
    }

    public function templateLDAPAdminConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $fileFactory = new \Model\File() ;
        $logging->log("Updating PHP LDAP Admin Configuration", $this->getModuleName()) ;
//        $params = $this->params ;
//        $params["file"] = "/etc/phpldapadmin/config.php" ;
//        $params["search"] = "php_admin_value[session.save_path] = /tmp/ " ;
//        $params["after-line"] = "[global]" ;
//        $file = $fileFactory->getModel($params) ;
//        $res[] = $file->performShouldHaveLine();
//        return in_array(false, $res)==false ;
        return true ;
    }

    public function copyVHostConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Copying Virtual Host into Directory", $this->getModuleName()) ;
        $cparams = $this->params ;
        $cparams["source"] = "/etc/phpldapadmin/apache.conf" ;
        $cparams["target"] = "/etc/apache2/sites-available/".$this->getVheUrl().".conf" ;
        $copyFactory = new \Model\Copy();
        $copy = $copyFactory->getModel($cparams) ;
        $res[] = $copy->performCopyPut() ;
        return in_array(false, $res)==false ;
    }

    public function getVheUrl() {
        if (isset($this->params["vhe-url"])) { return $this->params["vhe-url"] ; }
        $this->params["vhe-url"] = "localhost" ;
        return $this->params["vhe-url"] ;
    }

    public function templateVHostConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Updating Virtual Host Configuration", $this->getModuleName()) ;
        $templatingFactory = new \Model\Templating() ;
        $templating = $templatingFactory->getModel($this->params) ;
        $res[] = $templating->template(
            file_get_contents(dirname(dirname(__FILE__)).DS."Templates".DS."vhost.conf"),
            array(
                "server_ip_port" => $this->getVheIp() ,
                "server_name" => $this->getVheUrl(),
                "server_admin" => $this->getVheServerAdmin() ,
            ),
            "/etc/apache2/sites-available/".$this->getVheUrl().".conf"  );
        return in_array(false, $res)==false ;
    }

    public function getVheIp() {
        if (isset($this->params["vhe-ip-port"])) {
            return $this->params["vhe-ip-port"] ; }
        $question = "Enter Virtual Host IP:Port " ;
        $this->params["vhe-ip-port"] = $this->askForInput($question) ;
        return $this->params["vhe-ip-port"] ;
    }

    public function getVheServerAdmin() {
        if (isset($this->params["vhe-admin"])) {
            return $this->params["vhe-admin"] ; }
        $question = "Enter Virtual Host Admin E-Mail" ;
        $this->params["vhe-admin"] = $this->askForInput($question) ;
        return $this->params["vhe-admin"] ;
    }

    public function enableVhost() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Enabling Virtual Host", $this->getModuleName()) ;
        $comm = SUDOPREFIX."ptdeploy vhe enable -yg --vhost=".$this->getVheUrl() ;
        $res = $this->executeAndGetReturnCode($comm) ;
        return true ;
    }


//    public function addInitScript() {
//        $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
//        $templateSource = $templatesDir.'/haproxy';
//        $templatorFactory = new \Model\Templating();
//        $templator = $templatorFactory->getModel($this->params);
//        $newFileName = "/etc/default/haproxy" ;
//        $templator->template(
//            file_get_contents($templateSource),
//            array(),
//            $newFileName );
//        echo "HA Proxy Init script config file $newFileName added\n";
//    }
//
//    public function delInitScript() {
//        unlink("/etc/default/haproxy");
//        echo "HA Proxy Init script config file /etc/default/haproxy removed\n";
//    }

//    public function haproxyRestart() {
//        $serviceFactory = new Service();
//        $serviceManager = $serviceFactory->getModel($this->params) ;
//        $serviceManager->setService("haproxy");
//        $serviceManager->restart();
//    }

//    public function versionInstalledCommandTrimmer($text) {
//        $done = substr($text, 22, 8) ;
//        return $done ;
//    }
//
//    public function versionLatestCommandTrimmer($text) {
//        $done = substr($text, 44, 8) ;
//        return $done ;
//    }
//
//    public function versionRecommendedCommandTrimmer($text) {
//        $done = substr($text, 44, 8) ;
//        return $done ;
//    }

    public function askStatus() {
        $pmf = new \Model\Apt();
        $pm = $pmf->getModel($this->params);
        $pax = array( "phpldapadmin" ) ;
        $res = $pm->isInstalled($pax) ;
        return $res ;
    }

}