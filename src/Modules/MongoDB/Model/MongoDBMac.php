<?php

Namespace Model;

//@todo mongodb server mac model isnt finished
class MongoDBMac extends BaseLinuxApp {

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
        $this->autopilotDefiner = "MongoDB";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForMongoDBHost", "params" => array()) ),
            array("command"=> $this->getInstallCommands() ),
        );
        $this->uninstallCommands = array(
            ""
        );
        $this->programDataFolder = "/opt/MongoDB"; // command and app dir name
        $this->programNameMachine = "mongodbserver"; // command and app dir name
        $this->programNameFriendly = "MongoDB Server!"; // 12 chars
        $this->programNameInstaller = "MongoDB Server";
        $this->initialize();
    }

    private function getNewRootPass() {
        if (isset($this->params["mongodb-root-pass"])) {
            $newRootPass = $this->params["mongodb-root-pass"] ; }
        else if (AppConfig::getProjectVariable("mongodb-default-root-pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("mongodb-default-root-pass") ; }
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
            'echo "/usr/local/mysql/bin" >> /etc/paths',
            // update the root password
            "mysqladmin -uroot password $newRootPass",
            // remove the dmg
            "rm /tmp/") ;
    }

}