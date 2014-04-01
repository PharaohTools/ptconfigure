<?php

Namespace Model;

class AWSEC2SshKey extends BaseAWSEC2AllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("SshKey") ;

    public function askWhetherToSaveSshKey($params=null) {
        return $this->performAWSEC2SaveSshKey($params);
    }

    public function performAWSEC2SaveSshKey($params=null){
        if ($this->askForSSHKeyExecute() != true) { return false; }
        $this->apiKey = $this->askForAWSEC2APIKey();
        $this->clientId = $this->askForAWSEC2ClientID();
        $fileLocation = $this->askForSSHKeyPublicFileLocation();
        $fileData = file_get_contents($fileLocation);
        $keyName = $this->askForSSHKeyNameForAWSEC2();
        return $this->saveSshKeyToAWSEC2($fileData, $keyName);
    }

    private function askForSSHKeyExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Save local SSH Public Key file to AWS EC2?';
        return self::askYesOrNo($question);
    }

    private function askForSSHKeyPublicFileLocation() {
        if (isset($this->params["aws-ec2-ssh-key-path"]) && $this->params["aws-ec2-ssh-key-path"]==true) {
            return $this->params["aws-ec2-ssh-key-path"] ; }
        $question = 'Enter Location of ssh public key file to upload';
        return self::askForInput($question, true);
    }

    private function askForSSHKeyNameForAWSEC2(){
        if (isset($this->params["aws-ec2-ssh-key-name"]) && $this->params["aws-ec2-ssh-key-name"]==true) {
            return $this->params["aws-ec2-ssh-key-name"] ; }
        $question = 'Enter name to store ssh key under on AWS EC2';
        return self::askForInput($question, true);
    }

    public function saveSshKeyToAWSEC2($keyData, $keyName){
        $callVars = array();
        $keyData = str_replace("\n", "", $keyData);
        $callVars["ssh_pub_key"] = urlencode($keyData);
        $callVars["name"] = $keyName;
        $curlUrl = "https://api.awsec2.com/ssh_keys/new" ;
        return $this->awsCall($callVars, $curlUrl);
    }

}