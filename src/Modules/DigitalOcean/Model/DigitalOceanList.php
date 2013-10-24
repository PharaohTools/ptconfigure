<?php

Namespace Model;

class DigitalOceanList extends BaseDigitalOcean {

    public function runAutoPilot($autoPilot){
        $this->runAutoPilotSaveSshKey($autoPilot);
        return true;
    }

    public function askWhetherToListData($params=null) {
        return $this->performDigitalOceanListData($params);
    }

    public function runAutoPilotSaveSshKey($autoPilot) {
        if ( !isset($autoPilot["digitalOceanSshKeyExecute"]) || $autoPilot["digitalOceanSshKeyExecute"] !== true ) {
            return false; }
        $this->apiKey = $this->askForDigitalOceanAPIKey();
        $this->clientId = $this->askForDigitalOceanClientID();
    }

    protected function performDigitalOceanListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->apiKey = $this->askForDigitalOceanAPIKey();
        $this->clientId = $this->askForDigitalOceanClientID();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromDigitalOcean($dataToList);
    }

    private function askForListExecute(){
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    private function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        $options = array("droplets", "sizes", "images", "domains", "regions", "ssh_keys");
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromDigitalOcean($dataToList){
        $callVars = array();
        $curlUrl = "https://api.digitalocean.com/$dataToList/" ;
        return $this->digitalOceanCall($callVars, $curlUrl);
    }

}