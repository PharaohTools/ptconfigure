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
        array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array(
            "Apt", array("libreadline6-dev", "libyaml-dev", "libsqlite3-dev", "sqlite3", "libxml2-dev", "libxslt1-dev",
                "bison", "libffi-dev", "libmysqlclient-dev", "libmysql-ruby", "libgdbm-dev", "libncurses5-dev", "g++",
                "libssl-dev", "autoconf", "automake", "libtool") ) ) ),
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
        array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array(
            "Apt", array("libreadline6-dev", "libyaml-dev", "libsqlite3-dev", "sqlite3", "libxml2-dev", "libxslt1-dev",
                "bison", "libffi-dev", "libmysqlclient-dev", "libmysql-ruby", "libgdbm-dev", "libncurses5-dev", "g++",
                "libssl-dev", "autoconf", "automake", "libtool") ) ) ),
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