<?php

Namespace Model;

//@todo postgres server mac model isnt finished
class PostgresServerMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("32") ;

    // Model Group
    public $modelGroup = array("Default") ;


    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PostgresServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForPostgresHost", "params" => array()) ),
            array("command"=> $this->getInstallCommands() ),
        );
        $this->uninstallCommands = array(
            ""
        );
        $this->programDataFolder = "/opt/PostgresServer"; // command and app dir name
        $this->programNameMachine = "postgresserver"; // command and app dir name
        $this->programNameFriendly = "Postgres Server!"; // 12 chars
        $this->programNameInstaller = "Postgres Server";
        $this->initialize();
    }

    private function getNewRootPass() {
        if (isset($this->params["postgres-root-pass"])) {
            $newRootPass = $this->params["postgres-root-pass"] ; }
        else if (AppConfig::getProjectVariable("postgres-default-root-pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("postgres-default-root-pass") ; }
        else {
            $newRootPass = "cleopatra" ; }
        return $newRootPass;
    }

    public function getInstallCommands() {

        $newRootPass = $this->getNewRootPass();
        return array(
            // First thing, lets download the dmg
            "",
            // package install the dmg
            "",
            // add dir to path
            'echo "/usr/local/postgres/bin" >> /etc/paths',
            // update the root password
            "postgresadmin -uroot password $newRootPass",
            // remove the dmg
            "rm /tmp/") ;
    }

}