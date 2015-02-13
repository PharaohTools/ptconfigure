<?php

Namespace Model;

class DigitalOceanV2BoxAdd extends BaseDigitalOceanV2AllOS {

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
        $this->accessToken = $this->askForDigitalOceanV2AccessToken();
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
                            $serverData["sshKeyIds"] = $this->getSshKeyIds();

                            $response = $this->getNewServerFromDigitalOceanV2($serverData) ;
                            $this->addServerToPapyrus($envName, $response); } } } }

                return true ; }
        else {
            \Core\BootStrap::setExitCode(1) ;
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
        $question = 'Enter Image ID';
        return self::askForInput($question, true);
    }

    private function getServerGroupSizeID() {
        if (isset($this->params["size-id"])) {
            return $this->params["size-id"] ; }
        $question = 'Enter size ID';
        return self::askForInput($question, true);
    }

    private function getServerGroupRegionID() {
        if (isset($this->params["region-id"])) {
            return $this->params["region-id"] ; }
        $question = 'Enter Region ID';
        return self::askForInput($question, true);
    }

    private function getServerGroupBoxAmount() {
        if (isset($this->params["box-amount"])) {
            return $this->params["box-amount"] ; }
        $question = 'Enter number of boxes to add to Environment';
        $this->params["box-amount"] = self::askForInput($question, true);
        return $this->params["box-amount"] ;
    }

    private function askForSSHKeyIds() {
        $question = 'Enter SSH Key ID\'s, comma separated';
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
        return self::askForInput($question, true) ;
    }

    private function getSSHKeyLocation() {
        if (isset($this->params["key-path"])) {
            return $this->params["key-path"] ; }
        $question = 'Enter file path of private SSH Key';
        return self::askForInput($question, true) ;
    }

    private function getNewServerFromDigitalOceanV2($serverData) {
        $callVars = array() ;
        $callVars["name"] = $serverData["name"];
        $callVars["size"] = $serverData["sizeID"];
        $callVars["image"] = $serverData["imageID"];
        $callVars["region"] = $serverData["regionID"];
        $callVars["ssh_keys"] = $serverData["sshKeyIds"] ;
        $curlUrl = $this->_apiURL."/v2/droplets/" ;
        $httpType = "POST" ;
        $callOut = $this->digitalOceanV2Call($callVars, $curlUrl, $httpType);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Request for {$callVars["name"]} complete") ;
        return $callOut ;
    }

    private function addServerToPapyrus($envName, $data) {

        $dropletData = $this->getDropletData($data->droplet->id);
        if (!isset($dropletData->droplet->networks->v4[0]->ip_address) && isset($this->params["wait-for-box-info"])) {
            $dropletData = $this->waitForBoxInfo($data->droplet->id); }
        if (($dropletData->droplet->status != "active") && isset($this->params["wait-until-active"])) {
            $dropletData = $this->waitUntilActive($data->droplet->id); }
        $server = array();

        $server["target"] = $dropletData->droplet->networks->v4[0]->ip_address;

        $server["user"] = $this->getUsernameOfBox() ;
        $server["password"] = $this->getSSHKeyLocation() ;
        $server["provider"] = "DigitalOceanV2";
        $server["id"] = $data->droplet->id;
        $server["name"] = $data->droplet->name;
        // file_put_contents("/tmp/outloc", getcwd()) ;
        // file_put_contents("/tmp/outsrv", $server) ;
        $environments = \Model\AppConfig::getProjectVariable("environments");
        // file_put_contents("/tmp/outenv1", serialize($environments)) ;
        for ($i= 0 ; $i<count($environments); $i++) {
            if ($environments[$i]["any-app"]["gen_env_name"] == $envName) {
                $environments[$i]["servers"][] = $server; } }
        // file_put_contents("/tmp/outenv2", serialize($environments)) ;
        \Model\AppConfig::setProjectVariable("environments", $environments);
    }

    private function getSshKeyIds() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if (isset($this->params["ssh-key-ids"])) {
            $logging->log("Found param --ssh-key-ids with value {$this->params["ssh-key-ids"]} for SSH Keys") ;
            $ray = explode(",", $this->params["ssh-key-ids"]) ;
            foreach ($ray as &$sun) { $sun = "{$sun->id}" ; }
            return $ray ; }

        if (isset($this->params["ssh-key-id"])) {
            $logging->log("Found param --ssh-key-id with value {$this->params["ssh-key-id"]} for SSH Key") ;
            return array("{$this->getSshKeyInfoByKeyId($this->params["ssh-key-id"])}") ; }

        if (isset($this->params["ssh-key-fingerprint"])) {
            $logging->log("Found param --ssh-key-fingerprint with value {$this->params["ssh-key-fingerprint"]} for SSH Keys") ;
            return array("{$this->getSshKeyInfoByKeyFingerprint($this->params["ssh-key-fingerprint"])}") ; }
        if (isset($this->params["ssh-key-name"])) {
            $id = $this->getSshKeyIdFromName($this->params["ssh-key-name"]) ;
            $logging->log("Found param --ssh-key-name with value {$this->params["ssh-key-name"]} and id {$id} for SSH Keys") ;
            return array("$id") ; }
        if (isset($this->params["guess"]) || isset($this->params["use-all-ssh-keys"])) {
            $logging->log("Using all available SSH Keys") ;
            $ray = $this->getAllSshKeyIdsArray() ;
            foreach ($ray as &$sun) { $sun = "{$sun->id}" ; }
            return $ray ; }

        else {
            return $this->askForSSHKeyIds();
        }
    }

    /**
     * Get key information via ssh-key-id
     * @param $keyID
     * @return mixed
     */
    private function getSshKeyInfoByKeyId($keyID){
        $curlUrl = $this->_apiURL."/v2/account/keys/".$keyID;
        $sshKeysObject =  $this->digitalOceanV2Call(array(), $curlUrl);

        return $sshKeysObject;
    }

    /**
     * Get key information via ssh-key-fingerprint
     * @param $keyFingerprint
     * @return mixed
     */
    private function getSshKeyInfoByKeyFingerprint($keyFingerprint){
        $curlUrl = $this->_apiURL."/v2/account/keys/".$keyFingerprint;
        $sshKeysObject =  $this->digitalOceanV2Call(array(), $curlUrl);

        return $sshKeysObject;
    }

    /**
     * Get all ssh key from a account
     * @return array
     */
    private function getAllSshKeyIdsArray() {
        if (isset($this->params["ssh-key-ids"])) {
            return $this->params["ssh-key-ids"] ;
        }
        $curlUrl = $this->_apiURL."/v2/account/keys" ;
        $sshKeysObject =  $this->digitalOceanV2Call(array(), $curlUrl);
        return $sshKeysObject->ssh_keys;
    }

    private function getSshKeyIdFromName($name) {
        $curlUrl = $this->_apiURL."/v2/account/keys";
        $sshKeysObject =  $this->digitalOceanV2Call(array(), $curlUrl);
        foreach($sshKeysObject->ssh_keys as $sshKey) {
            if ($sshKey->name == $name) {
                return $sshKey->id ; } }
        return null;
    }

    /**
     * Get droplet information via droplet-id
     * @param $dropletId
     * @return mixed
     */
    private function getDropletData($dropletId) {
        $curlUrl = $this->_apiURL."/v2/droplets/$dropletId" ;
        $dropletObject =  $this->digitalOceanV2Call(array(), $curlUrl);

        return $dropletObject;
    }

    private function waitForBoxInfo($dropletId) {
        $maxWaitTime = (isset($this->params["max-box-info-wait-time"])) ? $this->params["max-box-info-wait-time"] : "300" ;
        $i2 = 1 ;
        for($i=0; $i<=$maxWaitTime; $i=$i+10){
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Attempt $i2 for droplet $dropletId box info...") ;
            $dropletData = $this->getDropletData($dropletId);
            if (isset($dropletData->droplet->networks->v4[0]->ip_address)) {
                return $dropletData ; }
            sleep (10);
            $i2++; }
        return null;
    }

    private function waitUntilActive($dropletId) {
        $maxWaitTime = (isset($this->params["max-active-wait-time"])) ? $this->params["max-active-wait-time"] : "300" ;
        $i2 = 1 ;
        for($i=0; $i<=$maxWaitTime; $i=$i+10){
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Attempt $i2 for droplet $dropletId to become active...") ;
            $dropletData = $this->getDropletData($dropletId);
            if (isset($dropletData->droplet->status) && $dropletData->droplet->status=="active") {
                return $dropletData ; }
            sleep (10);
            $i2++; }
        return null;
    }

}