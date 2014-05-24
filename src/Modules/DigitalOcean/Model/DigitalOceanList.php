<?php

Namespace Model;

class DigitalOceanList extends BaseDigitalOceanAllOS {

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
        return $this->performDigitalOceanListData();
    }

    protected function performDigitalOceanListData(){
        if ($this->askForListExecute() != true) { return false; }
        $this->apiKey = $this->askForDigitalOceanAPIKey();
        $this->clientId = $this->askForDigitalOceanClientID();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromDigitalOcean($dataToList);
    }

    private function askForListExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    private function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        $options = array("droplets", "sizes", "images", "domains", "regions", "ssh_keys");
        if (isset($this->params["digital-ocean-list-data-type"]) &&
            in_array($this->params["digital-ocean-list-data-type"], $options)) {
            return $this->params["digital-ocean-list-data-type"] ; }
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromDigitalOcean($dataToList){
        $callVars = array();
        $curlUrl = "https://api.digitalocean.com/$dataToList/" ;
        return $this->digitalOceanCall($callVars, $curlUrl);
    }

}