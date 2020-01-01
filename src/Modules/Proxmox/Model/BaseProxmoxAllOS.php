<?php

Namespace Model;

class BaseProxmoxAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Base") ;

    // set api url
    protected $_apiURL ;
    protected $credentials = array() ;

    protected function askForProxmoxHost(){
        if (isset($this->params["proxmox-host"])) { return $this->params["proxmox-host"] ; }
        if (isset($this->params["host"])) { return $this->params["host"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("proxmox-host") ;
//        var_dump($this->params) ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-host"]) && $this->params["use-project-host"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Proxmox Host?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("proxmox-host") ;
        if ($appVar != null) {
            $question = 'Use Application saved Proxmox Host?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Proxmox Host';
        $input = self::askForInput($question, true);
        $this->_apiURL = $input ;
        return $input ;
    }

    protected function askForProxmoxUser(){
        if (isset($this->params["proxmox-user"])) { return $this->params["proxmox-user"] ; }
        if (isset($this->params["user"])) { return $this->params["user"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("proxmox-user") ;
//        var_dump($this->params) ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-user"]) && $this->params["use-project-user"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Proxmox User?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("proxmox-user") ;
        if ($appVar != null) {
            $question = 'Use Application saved Proxmox User?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Proxmox User';
        return self::askForInput($question, true);
    }

    protected function askForProxmoxPassword(){
        if (isset($this->params["proxmox-password"])) { return $this->params["proxmox-password"] ; }
        if (isset($this->params["password"])) { return $this->params["password"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("proxmox-password") ;
//        var_dump($this->params) ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-password"]) && $this->params["use-project-password"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Proxmox Password?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("proxmox-password") ;
        if ($appVar != null) {
            $question = 'Use Application saved Proxmox Password?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Proxmox Password';
        return self::askForInput($question, true);
    }

    protected function askForProxmoxRealm(){
        if (isset($this->params["proxmox-realm"])) { return $this->params["proxmox-realm"] ; }
        if (isset($this->params["realm"])) { return $this->params["realm"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("proxmox-realm") ;
        if (isset($this->params["guess"])) {
            return 'pam' ; }
//        var_dump($this->params) ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-realm"]) && $this->params["use-project-realm"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Proxmox Realm?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("proxmox-realm") ;
        if ($appVar != null) {
            $question = 'Use Application saved Proxmox Realm?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Proxmox Realm';
        return self::askForInput($question, true);
    }

    protected function askForProxmoxPort(){
        if (isset($this->params["proxmox-port"])) { return $this->params["proxmox-port"] ; }
        if (isset($this->params["port"])) { return $this->params["port"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("proxmox-port") ;
//        var_dump($this->params) ;
        if (isset($this->params["guess"])) {
            return '8006' ; }
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) {
                return $papyrusVar ; }
            if (isset($this->params["use-project-port"]) && $this->params["use-project-port"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Proxmox Port?';
            if (self::askYesOrNo($question, true) == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("proxmox-port") ;
        if ($appVar != null) {
            $question = 'Use Application saved Proxmox Port?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Proxmox Port';
        return self::askForInput($question, true);
    }

    protected function setCredentials(){
        $this->credentials['hostname'] = $this->askForProxmoxHost() ;
        $this->credentials['username'] = $this->askForProxmoxUser() ;
        $this->credentials['password'] = $this->askForProxmoxPassword() ;
        $this->credentials['realm'] = $this->askForProxmoxRealm() ;
        $this->credentials['port'] = $this->askForProxmoxPort() ;
        return true ;
    }

    protected function proxmoxCall(Array $curlParams, $curlUrl, $httpType='GET') {

        \Model\AppConfig::setProjectVariable("proxmox-access-token", $this->accessToken) ;

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