<?php

Namespace Model;

class DigitalOceanV2NodeTest extends BaseDigitalOceanV2AllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("NodeTest") ;

    public function __construct($params) {
        parent::__construct($params) ;
    }

    public function askWhetherToTestNode() {
        return $this->performDigitalOceanV2TestNode();
    }

    public function askWhetherToTestAllEnvNodes() {
        return $this->performDigitalOceanV2TestAllEnvNodes();
    }

    protected function performDigitalOceanV2TestNode(){
        if ($this->askForTestNodeExecute() != true) { return false; }
        $this->accessToken = $this->askForDigitalOceanV2AccessToken();
        $this->params["id"] = $this->askForNodeID();
        $this->params["name"] = $this->askForNodeName();
        $this->params["image"] = $this->askForNodeImage();
        $processed = $this->getDataTestNodeFromDigitalOceanV2();
        return $processed ;
    }

    protected function performDigitalOceanV2TestAllEnvNodes(){
        if ($this->askForTestNodeExecute() != true) { return false; }
        $this->accessToken = $this->askForDigitalOceanV2AccessToken();
        $this->params["environment-name"] = $this->askForEnvironment();
        $doFactory = new \Model\DigitalOceanV2();
        $listParams = array("yes" => true, "guess" => true, "type" => "droplets") ;
        $listParams = array_merge($listParams, $this->params) ;
        $doListing = $doFactory->getModel($listParams, "Listing") ;
        $allBoxes = $doListing->askWhetherToListData();
        $processed = array() ;
        $server_ids = $this->getEnvironmentServerIDs() ;
        foreach($allBoxes->droplets as $box) {
            if (in_array($box->id, $server_ids)) {
                $this->params["id"] = $box->id;
                $this->params["name"] = $box->name;
                $this->params["image"] = $box->image->id;
                $processed[] = $this->getDataTestNodeFromDigitalOceanV2(); } }
        return $processed ;
    }

    private function askForTestNodeExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Test Node Health?';
        return self::askYesOrNo($question);
    }

    private function askForNodeID(){
        if (isset($this->params["id"])) { return $this->params["id"] ; }
        $question = 'Enter Node ID';
        return self::askForInput($question, true);
    }

    private function askForEnvironment(){
        if (isset($this->params["environment-name"])) { return $this->params["environment-name"] ; }
        if (isset($this->params["env"])) { return $this->params["env"] ; }
        $question = 'Enter Environment to test Node Health in:';
        $res = self::askForInput($question, true);
        $this->params["environment-name"] = $res  ;
        return $res ;
    }

    private function askForNodeName (){
        if (isset($this->params["name"])) { return $this->params["name"] ; }
//        $question = 'Enter Node Name';
//        return self::askForInput($question);
        return "" ;
    }

    private function askForNodeImage (){
        if (isset($this->params["image"])) { return $this->params["image"] ; }
//        $question = 'Enter Node Image ID';
//        return self::askForInput($question);
        return "" ;
    }

    public function getDataTestNodeFromDigitalOceanV2(){

        $ret = array() ;

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["name"]) &&
            (!isset($this->params["id"]) || $this->params["id"] == "" ) ) {
            $tx = "Name Provided, Finding Node ID From Name" ;
            $logging->log($tx, $this->getModuleName()) ;
            $this->params["id"] = $this->findIDFromName($this->params["name"]) ; }

        if (isset($this->params["id"]) && $this->params["id"] !== "") {
            $tx = "ID Provided, Finding Node from ID" ;
            $logging->log($tx, $this->getModuleName()) ;
            $node_id = $this->params["id"] ; }

        else {
            $tx = "No ID or Name available" ;
            $logging->log($tx, $this->getModuleName()) ;
            $ret["status"] = false ;
            return $ret ; }

        $curlUrl = $this->_apiURL."/v2/droplets/$node_id" ;
        $httpType = "GET" ;
        $ret["data"] = $this->digitalOceanV2Call(array(), $curlUrl, $httpType);
        $ret["info"] = $this->getInfoFromData($ret["data"]);
        $ret["status"] = $this->checkNodeStatusFromData($ret["data"]) ;
        $logging->log("Calling Digital Ocean Data", $this->getModuleName()) ;

        return $ret ;
    }

    private function checkNodeStatusFromData($data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Beginning node status tests", $this->getModuleName()) ;
        $res[] = $this->checkNodeImageFromData($data) ;
        $res[] = $this->checkNodeActiveFromData($data) ;
        return (array_diff($res, array(true))==array()) ? true : false ;
    }

    private function getInfoFromData($data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ret = array() ;
        if (isset($data->droplet)) {
            $logging->log("Collecting data from cloud node", $this->getModuleName()) ;
            $ret["id"] = $data->droplet->id ;
            $ret["image"] = $data->droplet->image->id ;
            $ret["target_ip"] = $data->droplet->networks->v4[0]->ip_address ;
            $ret["name"] = $data->droplet->name ;
            $ret["size"] = $data->droplet->size_slug ;
            $ret["region"] = $data->droplet->region->slug ;
            $ret["region_name"] = $data->droplet->region->name ; }
        else {
            $logging->log("Unable to collect node data from cloud node", $this->getModuleName()) ; }
        return $ret ;
    }

    private function checkNodeImageFromData($data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($data->droplet)) {
            if ($data->droplet->image->id == $this->params["image"]) {
                $logging->log("Node reports expected image id {$data->droplet->image->id}", $this->getModuleName()) ;
                return true ; }
            $logging->log("Node reports unexpected image id of {$data->droplet->image->id}", $this->getModuleName()) ; }
        else {
            $logging->log("Unable to collect image data from cloud node", $this->getModuleName()) ; }
        return false ;
    }

    private function checkNodeActiveFromData($data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($data->droplet)) {
            if ($data->droplet->status == "active") {
                $logging->log("Node reports expected active status", $this->getModuleName()) ;
                return true ; }
            $logging->log("Node reports unexpected status of {$data->status} ", $this->getModuleName()) ;
            return false ; }
        else {
            $logging->log("Unable to collect active status from cloud node", $this->getModuleName()) ;
            return false ; }
    }

    private function findIDFromName($name) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Finding ID of Node from name {$name}", $this->getModuleName()) ;
        $droplets = $this->getAllDroplets() ;
        foreach ($droplets as $droplet) {
            if ($droplet->name == $name) {
                $logging->log("Found ID of {$droplet->ID} for Node from name {$name}", $this->getModuleName()) ;
                return $droplet->ID ; } }
        return false ;
    }

    private function getAllDroplets() {
        $curlUrl = $this->_apiURL."/v2/droplets" ;
        $httpType = "GET" ;
        return $this->digitalOceanV2Call(array(), $curlUrl, $httpType);
    }

    protected function getEnvironment() {
        $envs = \Model\AppConfig::getProjectVariable("environments");
        foreach ($envs as $env) {
            if ($env["any-app"]["gen_env_name"] == $this->params["environment-name"]) {
                return $env ; } }
        return false ;
    }

    protected function getEnvironmentServerIDs() {
        $envs = $this->getEnvironment();
        $sids = array() ;
        foreach ($envs["servers"] as $server) {
            $sids[$server["any-app"]["gen_env_name"]][] = $server->id ; }
        return false ;
    }

    protected function getEnvironmentServerNames() {
        $envs = \Model\AppConfig::getProjectVariable("environments");
        $snames = array() ;
        foreach ($envs["servers"] as $server) {
            $snames[$server["any-app"]["gen_env_name"]][] = $server->name ; }
        return (count($snames) > 0 ) ? $snames : false ;
    }

}