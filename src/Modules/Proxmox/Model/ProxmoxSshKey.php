<?php

Namespace Model;

class ProxmoxSshKey extends BaseProxmoxAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("SshKey") ;

    public function askWhetherToSaveSshKey($params=null) {
        return $this->performProxmoxSaveSshKey($params);
    }

    public function performProxmoxSaveSshKey($params=null){
        if ($this->askForSSHKeyExecute() != true) { return false; }
        $this->accessToken = $this->askForProxmoxAccessToken();
        $fileLocation = $this->askForSSHKeyPublicFileLocation();
        $fileData = file_get_contents($fileLocation);
        $keyName = $this->askForSSHKeyNameForProxmox();
        return $this->saveSshKeyToProxmox($fileData, $keyName);
    }

    private function askForSSHKeyExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Save local SSH Public Key file to Proxmox?';
        return self::askYesOrNo($question);
    }

    private function askForSSHKeyPublicFileLocation() {
        if (isset($this->params["path"]) && $this->params["path"]==true) {
            return $this->params["path"] ; }
        $question = 'Enter Location of ssh public key file to upload';
        return self::askForInput($question, true);
    }

    private function askForSSHKeyNameForProxmox(){
        if (isset($this->params["name"]) && $this->params["name"]==true) {
            return $this->params["name"] ; }
        $question = 'Enter name to store ssh key under on Proxmox';
        return self::askForInput($question, true);
    }

    public function saveSshKeyToProxmox($keyData, $keyName){
        $callVars = array();
        $keyData = str_replace("\n", "", $keyData);

        //$callVars["public_key"] = urlencode($keyData);
        $callVars["public_key"] = $keyData;
        $callVars["name"] = $keyName;
        $curlUrl = $this->_apiURL."/v2/account/keys" ;
        $httpType = "POST" ;
        return $this->proxmoxCall($callVars, $curlUrl, $httpType);
    }

}