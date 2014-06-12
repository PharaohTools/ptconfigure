<?php

Namespace Model;

class GameBlocksSshKey extends BaseGameBlocksAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("SshKey") ;

    public function askWhetherToSaveSshKey($params=null) {
        return $this->performGameBlocksSaveSshKey($params);
    }

    public function performGameBlocksSaveSshKey($params=null){
        if ($this->askForSSHKeyExecute() != true) { return false; }
        $this->apiKey = $this->askForGameBlocksAPIKey();
        $this->clientId = $this->askForGameBlocksClientID();
        $fileLocation = $this->askForSSHKeyPublicFileLocation();
        $fileData = file_get_contents($fileLocation);
        $keyName = $this->askForSSHKeyNameForGameBlocks();
        return $this->saveSshKeyToGameBlocks($fileData, $keyName);
    }

    private function askForSSHKeyExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Save local SSH Public Key file to Game Blocks?';
        return self::askYesOrNo($question);
    }

    private function askForSSHKeyPublicFileLocation() {
        if (isset($this->params["game-blocks-ssh-key-path"]) && $this->params["game-blocks-ssh-key-path"]==true) {
            return $this->params["game-blocks-ssh-key-path"] ; }
        $question = 'Enter Location of ssh public key file to upload';
        return self::askForInput($question, true);
    }

    private function askForSSHKeyNameForGameBlocks(){
        if (isset($this->params["game-blocks-ssh-key-name"]) && $this->params["game-blocks-ssh-key-name"]==true) {
            return $this->params["game-blocks-ssh-key-name"] ; }
        $question = 'Enter name to store ssh key under on Game Blocks';
        return self::askForInput($question, true);
    }

    public function saveSshKeyToGameBlocks($keyData, $keyName){
        $callVars = array();
        $keyData = str_replace("\n", "", $keyData);
        $callVars["ssh_pub_key"] = urlencode($keyData);
        $callVars["name"] = $keyName;
        $curlUrl = "https://api.digitalocean.com/ssh_keys/new" ;
        return $this->gameBlocksCall($callVars, $curlUrl);
    }

}