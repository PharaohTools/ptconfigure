<?php

Namespace Model;

class AWSEC2List extends BaseAWSEC2AllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {
        return $this->performAWSEC2ListData($params);
    }

    protected function performAWSEC2ListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->apiKey = $this->askForAWSEC2APIKey();
        $this->clientId = $this->askForAWSEC2ClientID();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromAWSEC2($dataToList);
    }

    private function askForListExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    private function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        $options = array("droplets", "sizes", "images", "domains", "regions", "ssh_keys");
        if (isset($this->params["aws-ec2-list-data-type"]) &&
            in_array($this->params["aws-ec2-list-data-type"], $options)) {
            return $this->params["aws-ec2-list-data-type"] ; }
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromAWSEC2($dataToList){
        $callVars = array();
        $curlUrl = "https://api.awsec2.com/$dataToList/" ;
        return $this->awsCall($callVars, $curlUrl);
    }

}