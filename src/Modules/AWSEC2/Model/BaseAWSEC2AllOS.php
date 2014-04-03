<?php

Namespace Model;

class BaseAWSEC2AllOS extends Base {

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

    public function __construct($params) {
        $this->loadLibs();
        parent::__construct($params);
    }

    private function loadLibs() {
        $srcFolder =  str_replace("/Model", "", dirname(__FILE__) ) ;
        $pharFile = $srcFolder."/Libraries/aws.phar" ;
        require_once($pharFile) ;
    }

    protected function askForAWSEC2AccessKey(){
        if (isset($this->params["aws-ec2-access-key"])) {
            putenv("AWS_ACCESS_KEY_ID={$this->params["aws-ec2-access-key"]}") ;
            return $this->params["aws-ec2-access-key"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("aws-ec2-access-key") ;
        if ($papyrusVar != null) {
            $question = 'Use Project saved AWS Access Key?';
            if (self::askYesOrNo($question, true) == true || $this->params["yes"] == true) {
                putenv("AWS_ACCESS_KEY_ID=$papyrusVar") ;
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("aws-ec2-access-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved AWS Access Key?';
            if (self::askYesOrNo($question, true) == true || $this->params["yes"] == true) {
                putenv("AWS_ACCESS_KEY_ID=$appVar") ;
                return $appVar ; } }
        $question = 'Enter AWS Access Key';
        $key = self::askForInput($question, true);
        putenv("AWS_ACCESS_KEY_ID=$key") ;
        return $key ;
    }

    protected function askForAWSEC2SecretKey(){
        if (isset($this->params["aws-ec2-secret-key"])) {
            putenv("AWS_SECRET_KEY={$this->params["aws-ec2-secret-key"]}") ;
            return $this->params["aws-ec2-secret-key"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("aws-ec2-secret-key") ;
        if ($papyrusVar != null) {
            $question = 'Use Project saved AWS EC2 Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                putenv("AWS_SECRET_KEY=$papyrusVar") ;
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("aws-ec2-secret-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved AWS EC2 Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                putenv("AWS_SECRET_KEY=$appVar") ;
                return $appVar ; } }
        $question = 'Enter AWS EC2 Secret Key';
        return self::askForInput($question, true);
    }

    protected function awsCall(Array $curlParams, $curlUrl){
        $client = "" ;
        $curlParams["client_id"] = $this->clientId ;
        $curlParams["api_key"] = $this->apiKey;
        // @todo do we actually need to set this every time? highly unlikely
        \Model\AppConfig::setProjectVariable("aws-ec2-client-id", $this->clientId) ;
        \Model\AppConfig::setProjectVariable("aws-ec2-api-key", $this->apiKey) ;
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