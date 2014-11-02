<?php

Namespace Model;

class BaseDigitalOceanV2V2AllOS extends Base {

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

    protected function askForDigitalOceanV2AccessToken(){
        if (isset($this->params["digital-ocean-v2-v2-access-token"])) { return $this->params["digital-ocean-v2-v2-access-token"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("digital-ocean-v2-v2-access-token") ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-access-token"]) && $this->params["use-project-access-token"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Digital Ocean Access Token?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("digital-ocean-v2-v2-access-token") ;
        if ($appVar != null) {
            $question = 'Use Application saved Digital Ocean Access Token?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Digital Ocean Access Token';
        return self::askForInput($question, true);
    }

    protected function digitalOceanV2Call(Array $curlParams, $curlUrl, $httpType){
        $curlParams["access-token"] = $this->apiKey;



// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS,
//          http_build_query(array('postvar1' => 'value1')));

        \Model\AppConfig::setProjectVariable("digital-ocean-v2-access-token", $this->apiKey) ;
        $postQuery = "";
        $i = 0;
        foreach ($curlParams as $curlParamKey => $curlParamValue) {
            $postQuery .= ($i==0) ? "" : '&' ;
            if(is_object($curlParamValue)) { var_dump($curlParamKey, $curlParamValue) ;  }
            $postQuery .= $curlParamKey."=".$curlParamValue;
            $i++; }


        // echo $curlUrl.'?'.$postQuery ;


        $ch = curl_init();

        // CURLOPT_POST
        // CURLOPT_HTTPGET
        // CURLOPT_HTTPGET
        // CURLOPT_PUT 	TRUE to HTTP PUT a file. The file to PUT must be set with CURLOPT_INFILE and CURLOPT_INFILESIZE.
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");


        curl_setopt($ch, CURLOPT_URL,"http://www.mysite.com/tester.phtml");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "postvar1=value1&postvar2=value2&postvar3=value3");

        // curl_setopt($ch, CURLOPT_URL, $curlUrl.'?'.$postQuery);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $callObject = json_decode($server_output);
        return $callObject;
    }


}