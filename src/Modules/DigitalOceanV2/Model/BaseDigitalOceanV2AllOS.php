<?php

Namespace Model;

class BaseDigitalOceanV2AllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Base") ;

    protected $accessToken ;

    protected function askForDigitalOceanV2AccessToken(){
        if (isset($this->params["digital-ocean-v2-access-token"])) { return $this->params["digital-ocean-v2-access-token"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("digital-ocean-v2-access-token") ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-access-token"]) && $this->params["use-project-access-token"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Digital Ocean Access Token?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("digital-ocean-v2-access-token") ;
        if ($appVar != null) {
            $question = 'Use Application saved Digital Ocean Access Token?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Digital Ocean Access Token';
        return self::askForInput($question, true);
    }

    protected function digitalOceanV2Call(Array $curlParams, $curlUrl, $httpType){

        \Model\AppConfig::setProjectVariable("digital-ocean-v2-access-token", $this->accessToken) ;
        $postQuery = "";
        $i = 0;
        foreach ($curlParams as $curlParamKey => $curlParamValue) {
            $postQuery .= ($i==0) ? "" : '&' ;
            if(is_object($curlParamValue)) { var_dump($curlParamKey, $curlParamValue) ;  }
            $postQuery .= $curlParamKey."=".$curlParamValue;
            $i++; }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$curlUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($curlParams));
        switch ($httpType){
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            case "GET":
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_PUT, 1);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default :
                break;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer {$this->accessToken}",
                'Content-Type: application/json',
                'Content-Length: ' . strlen(http_build_query($curlParams)))
        );
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $callObject = json_decode($server_output);
        return $callObject;
    }


}