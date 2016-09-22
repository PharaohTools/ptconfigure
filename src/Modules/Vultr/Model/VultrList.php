<?php

Namespace Model;

class VultrList extends BaseVultrAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function __construct($params) {
        parent::__construct($params) ;
    }

    public function askWhetherToListData() {
        return $this->performVultrListData();
    }

    protected function performVultrListData(){
        if ($this->askForListExecute() != true) { return false; }
        $this->accessToken = $this->askForVultrAccessToken();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromVultr($dataToList, array("per_page" =>100));
    }

    private function askForListExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    private function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        $options = array("servers", "sizes", "images", "domains", "regions", "ssh_keys", "os");
        if (isset($this->params["type"]) &&
            in_array($this->params["type"], $options)) {
            return $this->params["type"] ; }
        $ao = self::askForArrayOption($question, $options, true) ;
        $this->params["type"] = $ao ;
        return $ao;
    }

    public function getDataListFromVultr($dataToList, $callVars = array()){
        if ($dataToList == "ssh_keys") {$dataToList = "sshkey/list";}
        if ($dataToList == "images") {$dataToList = "iso/list";}
        if ($dataToList == "servers") {$dataToList = "server/list"; }
        if ($dataToList == "os") {$dataToList = "os/list"; }
        $curlUrl = $this->_apiURL."$dataToList" ;
        $httpType = "GET" ;
        return $this->digitalOceanV2Call($callVars, $curlUrl, $httpType);
    }

}
