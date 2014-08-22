<?php

Namespace Model;

//@todo if we can use a wget/binary method like selenium or gitbucket then we can easily use across other linux os
class PHPCIUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPCI";
        $this->installCommands = $this->getInstallCommands();
        $this->uninstallCommands = array( "apt-get remove -y phpci" );
        $this->programDataFolder = "/opt/phpci"; // command and app dir name
        $this->programNameMachine = "phpci"; // command and app dir name
        $this->programNameFriendly = " ! PHPCI !"; // 12 chars
        $this->programNameInstaller = "PHPCI";
        $this->statusCommand = "sudo phpci -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy phpci" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy phpci" ;
        $this->versionLatestCommand = "sudo apt-cache policy phpci" ;
        $this->initialize();
    }

    protected function getInstallCommands() {
        $whoami = self::executeAndLoad("whoami") ;
        $whoami = str_replace("\n", "", $whoami) ;
        $whoami = str_replace("\r", "", $whoami) ;
        //@too need to put php version here
        // add php mcrypt
        $ray = array(
            array("method"=> array(
                "object" => $this, "method" => "packageAdd", "params" => array("Apt", array("php5-mcrypt"))) ),
            // mcrypt is not automatically installed for cli in 14.04 @todo only do this in 14.04+
            array("command" => array( "sudo ln -s /etc/php5/mods-available/mcrypt.ini /etc/php5/cli/conf.d/888-mcrypt.ini" ))
        ) ;

        // mod rewrite/alternates if needed
        if (isset($this->params["use-nginx"]) && $this->params["use-nginx"]==true) {
            // nginx version of mod rewrite if needed;
        }
        else if (isset($this->params["use-lighttpd"]) && $this->params["use-lighttpd"]==true) {
            // lighttpd version of mod rewrite if needed;
        }
        else {
            $mrw = array("command" => array( "sudo a2enmod rewrite") ) ;
            // enable mod rewrite
            array_push($ray, $mrw) ; }

        // ensure composer is installed and then install phpci
        $ray2 = array("method"=> array( "object" => $this, "method" => "ensureComposer", "params" => array()) );
        $ray3 = array("command" => array(
                    "sudo rm -rf /opt/phpci" ,
                    "sudo mkdir -p /opt/phpci" ,
                    "sudo chown -R $whoami /opt/phpci" ,
                    "cd /opt/phpci" ,
                    "composer create-project block8/phpci phpci --keep-vcs --no-dev" ,
                    // "phpci/console phpci:install"
                    ) ) ;
        array_push($ray, $ray2) ;
        array_push($ray, $ray3) ;

        // get the relevant dapper autopilot path
        if (isset($this->params["use-nginx"]) && $this->params["use-nginx"]==true) {
            $dapperAuto = $this->getDapperAutoPath("nginx") ; }
        else if (isset($this->params["use-lighttpd"]) && $this->params["use-lighttpd"]==true) {
            $dapperAuto = $this->getDapperAutoPath("lighttpd") ; }
        else {
            $dapperAuto = $this->getDapperAutoPath("apache") ; }

        // add the dapper step
        $dpc = array("command" => array( "sudo dapperstrano autopilot execute --autopilot-file=$dapperAuto" ) ) ;
        array_push($ray, $dpc) ;

        return $ray ;
    }

    public function ensureComposer() {
        // @todo add logging
        $composerFactory = new \Model\Composer();
        $composer = $composerFactory->getModel($this->params);
        $composer->ensureInstalled();
    }

    public function ensureMySQL() {
        // @todo add logging
        $mysqlFactory = new \Model\MysqlServer();
        $mysql = $mysqlFactory->getModel($this->params);
        $mysql->ensureInstalled();
    }

    private function getDapperAutoPath($webServer) {
        if ($webServer == "apache") {
            // @todo  use system detection factory to check if we're on +=14.04 and load a different vhost if so
            $path = dirname(dirname(__FILE__)).'/Autopilots/Dapperstrano/Apache/listen-all-ports.php'; }
        else if ($webServer == "nginx") {
            $path = dirname(dirname(__FILE__)).'/Autopilots/Dapperstrano/NGinx/listen-all-ports.php' ; }
        else if ($webServer == "lighttpd") {
            $path = dirname(dirname(__FILE__)).'/Autopilots/Dapperstrano/Lighttpd/listen-all-ports.php' ; }
        return $path ;
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