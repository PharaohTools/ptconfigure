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
    // set api url
    protected $_apiURL = 'https://api.digitalocean.com';

    protected function askForDigitalOceanV2AccessToken(){
        if (isset($this->params["digital-ocean-v2-access-token"])) { return $this->params["digital-ocean-v2-access-token"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("digital-ocean-v2-access-token") ;

//        var_dump($this->params) ;

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

    protected function digitalOceanV2Call(Array $curlParams, $curlUrl, $httpType='GET') {

        \Model\AppConfig::setProjectVariable("digital-ocean-v2-access-token", $this->accessToken) ;

        $postQuery = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$curlUrl);
        $postData = null;
        //curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($curlParams));
        switch ($httpType){
            case "POST":
//                $postQuery['name'] = $curlParams['name'] ;
//                $postQuery['region'] = $curlParams['region'] ;
//                $postQuery['size'] = $curlParams['size'] ;
//                $postQuery['image'] = $curlParams['image'];
//                $postQuery['ssh_keys'] = (isset($curlParams['ssh_keys'])) ? $curlParams['ssh_keys'] : null ;
                $postData = json_encode($curlParams);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            case "GET":
                curl_setopt($ch, CURLOPT_URL,$curlUrl.'?'.http_build_query($curlParams));
                // curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($curlParams));
                $postData = http_build_query($curlParams);
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_PUT, 1);
                break;
            case "DELETE":
                $postData = "";
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default :
                break;
        }

         if ($httpType == "GET") {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer {$this->accessToken}",
                'Content-Type: application/json',
                // 'Content-Length: ' . strlen($postData)
            ) ); }
         else {
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer {$this->accessToken}",
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ) ); }

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $callObject = json_decode($server_output);

        return $callObject;
    }


}