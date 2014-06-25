<?php

Namespace Model;

class BaseDigitalOceanAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Base") ;

    protected $clientId ;
    protected $apiKey ;

    protected function askForDigitalOceanAPIKey(){
        if (isset($this->params["digital-ocean-api-key"])) { return $this->params["digital-ocean-api-key"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("digital-ocean-api-key") ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-api-key"]) && $this->params["use-project-api-key"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Digital Ocean API Key?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("digital-ocean-api-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Digital Ocean API Key?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Digital Ocean API Key';
        return self::askForInput($question, true);
    }

    protected function askForDigitalOceanClientID(){
        if (isset($this->params["digital-ocean-client-id"])) { return $this->params["digital-ocean-client-id"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("digital-ocean-client-id") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            if ($this->params["use-project-client-id"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved Digital Ocean Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("digital-ocean-client-id") ;
        if ($appVar != null) {
            $question = 'Use Application saved Digital Ocean Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Digital Ocean Client ID';
        return self::askForInput($question, true);
    }

    protected function digitalOceanCall(Array $curlParams, $curlUrl){
        $curlParams["client_id"] = $this->clientId ;
        $curlParams["api_key"] = $this->apiKey;
        // @todo do we actually need to set this every time? highly unlikely
        \Model\AppConfig::setProjectVariable("digital-ocean-client-id", $this->clientId) ;
        \Model\AppConfig::setProjectVariable("digital-ocean-api-key", $this->apiKey) ;
        $postQuery = "";
        $i = 0;
        foreach ($curlParams as $curlParamKey => $curlParamValue) {
            $postQuery .= ($i==0) ? "" : '&' ;
            if(is_object($curlParamValue)) {
                var_dump($curlParamKey, $curlParamValue) ;  }
            $postQuery .= $curlParamKey."=".$curlParamValue;
            $i++; }
        echo $curlUrl.'?'.$postQuery ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curlUrl.'?'.$postQuery);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        var_dump($server_output) ;
        curl_close ($ch);
        $callObject = json_decode($server_output);
        return $callObject;
    }


}