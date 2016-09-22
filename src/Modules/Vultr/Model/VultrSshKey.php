<?php

Namespace Model;

class VultrSshKey extends BaseVultrAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("SshKey") ;

    public function askWhetherToSaveSshKey($params=null) {
        return $this->performVultrSaveSshKey($params);
    }

    public function performVultrSaveSshKey($params=null){
        if ($this->askForSSHKeyExecute() != true) { return false; }
        $this->accessToken = $this->askForVultrAccessToken();
        $fileLocation = $this->askForSSHKeyPublicFileLocation();
        $fileData = file_get_contents($fileLocation);
        $keyName = $this->askForSSHKeyNameForVultr();
        return $this->saveSshKeyToVultr($fileData, $keyName);
    }

    private function askForSSHKeyExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Save local SSH Public Key file to Vultr Cloud?';
        return self::askYesOrNo($question);
    }

    private function askForSSHKeyPublicFileLocation() {
        if (isset($this->params["path"]) && $this->params["path"]==true) {
            return $this->params["path"] ; }
        $question = 'Enter Location of ssh public key file to upload';
        return self::askForInput($question, true);
    }

    private function askForSSHKeyNameForVultr(){
        if (isset($this->params["name"]) && $this->params["name"]==true) {
            return $this->params["name"] ; }
        $question = 'Enter name to store ssh key under on Vultr Cloud';
        return self::askForInput($question, true);
    }

    public function saveSshKeyToVultr($keyData, $keyName){
        $callVars = array();
        $keyData = str_replace("\n", "", $keyData);
        //$callVars["public_key"] = urlencode($keyData);
        $callVars["public_key"] = $keyData;
        $callVars["name"] = $keyName;
        $curlUrl = $this->_apiURL."account/keys" ;
        $httpType = "POST" ;
        return $this->digitalOceanV2Call($callVars, $curlUrl, $httpType);
    }

}