<?php

Namespace Model;

class BaseGameBlocksAllOS extends Base {

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

    protected function askForGameBlocksAPIKey(){
        if (isset($this->params["game-blocks-api-key"])) { return $this->params["game-blocks-api-key"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("game-blocks-api-key") ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-api-key"]) && $this->params["use-project-api-key"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Game Blocks API Key?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("game-blocks-api-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Game Blocks API Key?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Game Blocks API Key';
        return self::askForInput($question, true);
    }

    protected function askForGameBlocksClientID(){
        if (isset($this->params["game-blocks-client-id"])) { return $this->params["game-blocks-client-id"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("game-blocks-client-id") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            if ($this->params["use-project-client-id"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved Game Blocks Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("game-blocks-client-id") ;
        if ($appVar != null) {
            $question = 'Use Application saved Game Blocks Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Game Blocks Client ID';
        return self::askForInput($question, true);
    }

    protected function gameBlocksCall(Array $curlParams, $curlUrl){
        $curlParams["client_id"] = $this->clientId ;
        $curlParams["api_key"] = $this->apiKey;
        // @todo do we actually need to set this every time? highly unlikely
        \Model\AppConfig::setProjectVariable("game-blocks-client-id", $this->clientId) ;
        \Model\AppConfig::setProjectVariable("game-blocks-api-key", $this->apiKey) ;
        $postQuery = "";
        $i = 0;
        foreach ($curlParams as $curlParamKey => $curlParamValue) {
            $postQuery .= ($i==0) ? "" : '&' ;
            if(is_object($curlParamValue)) {
                var_dump($curlParamKey, $curlParamValue) ;  }
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