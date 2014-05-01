<?php

Namespace Model;

class DigitalOceanBoxAdd extends BaseDigitalOceanAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("BoxAdd") ;

    public function askWhetherToBoxAdd($params=null) {
        return $this->addBox($params);
    }

    public function addBox() {
        if ($this->askForBoxAddExecute() != true) { return false; }
        $this->apiKey = $this->askForDigitalOceanAPIKey();
        $this->clientId = $this->askForDigitalOceanClientID();
        $serverPrefix = $this->getServerPrefix();
        $environments = \Model\AppConfig::getProjectVariable("environments");
        $workingEnvironment = $this->getWorkingEnvironment();

        foreach ($environments as $environment) {
            if ($environment["any-app"]["gen_env_name"] == $workingEnvironment) {
                $environmentExists = true ; } }

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if (isset($environmentExists)) {
            foreach ($environments as $environment) {
                if ($environment["any-app"]["gen_env_name"] == $workingEnvironment) {
                    $envName = $environment["any-app"]["gen_env_name"];

                    if (isset($this->params["yes"]) && $this->params["yes"]==true) {
                        $addToThisEnvironment = true ; }
                    else {
                        $question = 'Add Digital Ocean Server Boxes to '.$envName.'?';
                        $addToThisEnvironment = self::askYesOrNo($question); }

                    if ($addToThisEnvironment == true) {
                        for ($i = 0; $i < $this->getServerGroupBoxAmount(); $i++) {
                            $serverData = array();
                            $serverData["prefix"] = $serverPrefix ;
                            $serverData["envName"] = $envName ;
                            $serverData["sCount"] = $i ;
                            $serverData["sizeID"] = $this->getServerGroupSizeID() ;
                            $serverData["imageID"] = $this->getServerGroupImageID() ;
                            $serverData["regionID"] = $this->getServerGroupRegionID() ;
                            $serverData["name"] = (isset( $serverData["prefix"]) && strlen( $serverData["prefix"])>0)
                                ? $serverData["prefix"].'-'.$serverData["envName"].'-'.$serverData["sCount"]
                                : $serverData["envName"].'-'.$serverData["sCount"] ;
                            $response = $this->getNewServerFromDigitalOcean($serverData) ;
                            // var_dump("response", $response) ;
                            $this->addServerToPapyrus($envName, $response); } } } }

                return (isset($callsReturned)) ? $callsReturned : null ; }
        else {
            $logging->log("The environment $workingEnvironment does not exist.") ; }
    }

    private function askForBoxAddExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Add Digital Ocean Server Boxes?';
        return self::askYesOrNo($question);
    }

    private function getServerPrefix() {
        if (isset($this->params["server-prefix"])) {
            return $this->params["server-prefix"] ; }
        $question = 'Enter Prefix for all Servers (None is fine)';
        return self::askForInput($question);
    }

    private function getWorkingEnvironment() {
        if (isset($this->params["environment-name"])) {
            return $this->params["environment-name"] ; }
        $question = 'Enter Environment to add Servers to';
        return self::askForInput($question);
    }

    private function getServerGroupImageID() {
        if (isset($this->params["image-id"])) {
            return $this->params["image-id"] ; }
        $question = 'Enter Image ID for this Server Group';
        return self::askForInput($question, true);
    }

    private function getServerGroupSizeID() {
        if (isset($this->params["size-id"])) {
            return $this->params["size-id"] ; }
        $question = 'Enter size ID for this Server Group';
        return self::askForInput($question, true);
    }

    private function getServerGroupRegionID() {
        if (isset($this->params["region-id"])) {
            return $this->params["region-id"] ; }
        $question = 'Enter Region ID for this Server Group';
        return self::askForInput($question, true);
    }

    private function getServerGroupBoxAmount() {
        if (isset($this->params["box-amount"])) {
            return $this->params["box-amount"] ; }
        $question = 'Enter number of boxes to add to Environment';
        return self::askForInput($question, true);
    }

    private function getUsernameOfBox($boxName = null) {
        if (isset($this->params["box-user-name"])) {
            return $this->params["box-user-name"] ; }
        if (isset($this->params["box-username"])) {
            return $this->params["box-username"] ; }
        $question = (isset($boxName))
            ? 'Enter SSH username of box '.$boxName
            : 'Enter SSH username of remote box';
        return self::askForInput($question, true);
    }

    private function getSSHKeyLocation() {
        if (isset($this->params["private-ssh-key-path"])) {
            return $this->params["private-ssh-key-path"] ; }
        $question = 'Enter file path of private SSH Key';
        return self::askForInput($question, true);
    }

    private function getNewServerFromDigitalOcean($serverData) {
        $callVars = array() ;
        $callVars["name"] = $serverData["name"];
        $callVars["size_id"] = $serverData["sizeID"];
        $callVars["image_id"] = $serverData["imageID"];
        $callVars["region_id"] = $serverData["regionID"];
        $callVars["ssh_key_ids"] = $this->getAllSshKeyIdsString();
        $curlUrl = "https://api.digitalocean.com/droplets/new" ;
        $callOut = $this->digitalOceanCall($callVars, $curlUrl);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Request for {$callVars["name"]} complete") ;
        return $callOut ;
    }

    private function addServerToPapyrus($envName, $data) {
        $environments = \Model\AppConfig::getProjectVariable("environments");
        var_dump($data->droplet);
        $dropletData = $this->getDropletData($data->droplet->id);
        $server = array();
        $server["target"] = $dropletData->ip_address;
        $server["user"] = $this->getUsernameOfBox($data->name) ;
        $server["password"] = $this->getSSHKeyLocation() ;
        $server["provider"] = "DigitalOcean";
        $server["id"] = $data->droplet->id;
        $server["name"] = $data->droplet->name;
        for ($i= 0 ; $i<count($environments); $i++) {
            if ($environments[$i]["any-app"]["gen_env_name"] == $envName) {
                $environments[$i]["servers"][] = $server; } }
        \Model\AppConfig::setProjectVariable("environments", $environments);
    }

    private function getAllSshKeyIdsString() {
        if (isset($this->params["ssh-key-ids"])) {
            return $this->params["ssh-key-ids"] ; }
        $curlUrl = "https://api.digitalocean.com/ssh_keys" ;
        $sshKeysObject =  $this->digitalOceanCall(array(), $curlUrl);
        $sshKeys = array();
        foreach($sshKeysObject->ssh_keys as $sshKey) {
            $sshKeys[] = $sshKey->name ; }
        $keysString = implode(",", $sshKeys) ;
        return $keysString;
    }

    private function getDropletData($dropletId) {
        $curlUrl = "https://api.digitalocean.com/droplets/$dropletId" ;
        $dropletObject =  $this->digitalOceanCall(array(), $curlUrl);
        return $dropletObject;
    }

}