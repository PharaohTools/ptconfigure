<?php

Namespace Model;

class RubyRVMUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "RubyRVM";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForInstallUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForInstallUserHomeDir", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libreadline6-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libyaml-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libsqlite3-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "sqlite3") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libxml2-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libxslt1-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "bison") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libffi-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libmysqlclient-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libmysql-ruby") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libgdbm-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libncurses5-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "g++") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libssl-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "autoconf") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "automake") ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libtool") ) ),
            array ("command" =>
                "cd ****INSTALL USER HOME DIR****",
                "curl -L -o /tmp/rubyinstall.sh https://get.rvm.io",
                "chown ****INSTALL USER NAME**** /tmp/rubyinstall.sh ",
                "chmod 777 /tmp/rubyinstall.sh ",
                "chmod u+x /tmp/rubyinstall.sh ",
                "su ****INSTALL USER NAME**** -c'/tmp/rubyinstall.sh' ",
                "rm /tmp/rubyinstall.sh " ) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForInstallUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForInstallUserHomeDir", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libreadline6-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libyaml-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libsqlite3-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "sqlite3") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libxml2-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libxslt1-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "bison") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libffi-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libmysqlclient-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libmysql-ruby") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libgdbm-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libncurses5-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "g++") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libssl-dev") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "autoconf") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "automake") ) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libtool") ) ),
            array ("command" =>
                "cd ****INSTALL USER HOME DIR****/",
                "rm -rf .rvm" ) );
        $this->programDataFolder = "";
        $this->programNameMachine = "ruby"; // command and app dir name
        $this->programNameFriendly = " !Ruby RVM!!"; // 12 chars
        $this->programNameInstaller = "Ruby RVM";
        $this->initialize();
    }

}