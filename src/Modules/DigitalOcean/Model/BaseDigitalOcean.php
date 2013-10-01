<?php

Namespace Model;

class BaseDigitalOcean extends Base {

    protected $clientId;
    protected $apiKey;

    public function runAutoPilot($autoPilot){
        $this->runAutoPilotSaveSshKey($autoPilot);
        return true;
    }

    protected function askForDigitalOceanAPIKey(){
        $question = 'Enter Digital Ocean API Key';
        return self::askForInput($question, true);
    }

    protected function askForDigitalOceanClientID(){
        $question = 'Enter Digital Ocean Client ID';
        return self::askForInput($question, true);
    }

    protected function digitalOceanCall($curlParams, $curlUrl){
        $curlParams["client_id"] = $this->clientId ;
        $curlParams["api_key"] = $this->apiKey;
        // $postQuery = http_build_query($curlParams);
        $postQuery = "";
        $i = 0;
        foreach ($curlParams as $curlParamKey => $curlParamValue) {
            $postQuery .= ($i==0) ? "" : '&' ;
            $postQuery .= $curlParamKey."=".$curlParamValue;
            $i++; }
        echo $curlUrl.'?'.$postQuery;
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