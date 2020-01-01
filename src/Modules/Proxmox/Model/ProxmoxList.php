<?php

Namespace Model;

class DigitalOceanV2List extends BaseDigitalOceanV2AllOS {

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
        return $this->performDigitalOceanV2ListData();
    }

    protected function performDigitalOceanV2ListData(){
        if ($this->askForListExecute() != true) { return false; }
        $this->accessToken = $this->askForDigitalOceanV2AccessToken();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromDigitalOceanV2($dataToList, array("per_page" =>100));
    }

    private function askForListExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    private function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        $options = array("droplets", "sizes", "images", "domains", "regions", "ssh_keys", "load_balancers");
        if (isset($this->params["type"]) &&
            in_array($this->params["type"], $options)) {
            return $this->params["type"] ; }
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromDigitalOceanV2($dataToList, $callVars = array()){
        if ($dataToList == "ssh_keys") {$dataToList = "account/keys";}
        $curlUrl = $this->_apiURL."/v2/$dataToList" ;
        $httpType = "GET" ;
        return $this->digitalOceanV2Call($callVars, $curlUrl, $httpType);
    }

}