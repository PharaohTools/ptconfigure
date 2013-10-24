<?php

Namespace Model;

class BaseDigitalOcean extends Base {

    protected $clientId;
    protected $apiKey;

    protected function askForDigitalOceanAPIKey(){
        $papyrusVar = \Model\AppConfig::getProjectVariable("digital-ocean-api-key") ;
        if ($papyrusVar != null) {
            $question = 'Use saved Digital Ocean API Key?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $question = 'Enter Digital Ocean API Key';
        return self::askForInput($question, true);
    }

    protected function askForDigitalOceanClientID(){
        $papyrusVar = \Model\AppConfig::getProjectVariable("digital-ocean-client-id") ;
        if ($papyrusVar != null) {
            $question = 'Use saved Digital Ocean Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $question = 'Enter Digital Ocean Client ID';
        return self::askForInput($question, true);
    }

    protected function digitalOceanCall(Array $curlParams, $curlUrl){
        $curlParams["client_id"] = $this->clientId ;
        $curlParams["api_key"] = $this->apiKey;
        \Model\AppConfig::setProjectVariable("digital-ocean-client-id", $this->clientId) ;
        \Model\AppConfig::setProjectVariable("digital-ocean-api-key", $this->apiKey) ;
        $postQuery = "";
        $i = 0;
        foreach ($curlParams as $curlParamKey => $curlParamValue) {
            $postQuery .= ($i==0) ? "" : '&' ;
            $postQuery .= $curlParamKey."=".$curlParamValue;
            $i++; }
        // echo $curlUrl.'?'.$postQuery ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curlUrl.'?'.$postQuery);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $callObject = json_decode($server_output);
        return $callObject;
    }


}