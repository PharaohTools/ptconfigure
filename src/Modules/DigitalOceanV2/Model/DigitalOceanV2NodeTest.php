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

    protected function performDigitalOceanV2TestNode(){
        if ($this->askForTestNodeExecute() != true) { return false; }
        $this->accessToken = $this->askForDigitalOceanV2AccessToken();
        $this->params["id"] = $this->askForNodeID();
        $this->params["name"] = $this->askForNodeName();
        $this->params["image"] = $this->askForNodeImage();
        $processed = $this->getDataTestNodeFromDigitalOceanV2();
        return $processed ;
    }

    private function askForTestNodeExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Test Node Data?';
        return self::askYesOrNo($question);
    }

    private function askForNodeID(){
        if (isset($this->params["id"])) { return $this->params["id"] ; }
        $question = 'Enter Node ID';
        return self::askForInput($question, true);
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
            is_int($this->params["name"]) &&
            (!isset($this->params["id"]) || $this->params["id"] == "" ) ) {
            $tx = "Name Provided, Finding Node ID From Name" ;
            $logging->log($tx, $this->getModuleName()) ;
            $node_id = $this->findIDFromName($this->params["name"]) ; }

        if (isset($this->params["id"]) &&
            is_int($this->params["id"]) &&
            $this->params["id"]>0) {
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
        $logging->log("Collecting data from cloud node", $this->getModuleName()) ;

        $ret = array() ;
        $ret["id"] = $data->droplet->id ;
        $ret["image"] = $data->droplet->image->id ;
        $ret["target_ip"] = $data->droplet->networks->v4[0]->ip_address ;
        $ret["name"] = $data->droplet->name ;
        $ret["size"] = $data->droplet->size_slug ;
        $ret["region"] = $data->droplet->region->slug ;
        $ret["region_name"] = $data->droplet->region->name ;

        return $ret ;
    }

    private function checkNodeImageFromData($data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($data->droplet->image->id == $this->params["image"]) {
            $logging->log("Node reports expected image id {$data->droplet->image->id}", $this->getModuleName()) ;
            return true ; }
        $logging->log("Node reports unexpected image id of {$data->droplet->image->id}", $this->getModuleName()) ;
        return false ;
    }

    private function checkNodeActiveFromData($data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($data->droplet->status == "active") {
            $logging->log("Node reports expected active status", $this->getModuleName()) ;
            return true ; }
        $logging->log("Node reports unexpected status of {$data->status} ", $this->getModuleName()) ;
        return false ;
    }

    private function findIDFromName($name) {
        $droplets = $this->getAllDroplets() ;
        foreach ($droplets as $droplet) {
            if ($droplet->name == $name) {
                return $droplet->ID ; } }
        return false ;
    }

    private function getAllDroplets() {
        $curlUrl = $this->_apiURL."/v2/droplets" ;
        $httpType = "GET" ;
        return $this->digitalOceanV2Call(array(), $curlUrl, $httpType);
    }

}