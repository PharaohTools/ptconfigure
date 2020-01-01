<?php

Namespace Model;

class ProxmoxAllBoxesDestroy extends BaseProxmoxAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("BoxDestroyAll") ;

    public function destroyAllBoxes() {
        if ($this->askForOverwriteExecute() != true) { return false; }
        $this->accessToken = $this->askForProxmoxAccessToken();
        $doFactory = new \Model\Proxmox();
        $listParams = array("yes" => true, "guess" => true, "type" => "virtual_machines") ;
        $doListing = $doFactory->getModel($listParams, "Listing") ;
        $allBoxes = $doListing->askWhetherToListData();

        foreach($allBoxes->virtual_machines as $box) {
            $serverData["virtual_machineID"] = $box->id ;
            $responses[] = $this->destroyServerFromProxmox($serverData) ; }
        return true ;

    }

    private function askForOverwriteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Destroy All Proxmox Server Boxes? (Careful!)';
        return self::askYesOrNo($question);
    }

    private function destroyServerFromProxmox($serverData) {
        $callVars = array() ;
        $callVars["virtual_machine_id"] = $serverData["virtual_machineID"];
        $curlUrl = $this->_apiURL."/v2/virtual_machines/{$callVars["virtual_machine_id"]}" ;
        $callOut = $this->proxmoxCall($callVars, $curlUrl,'DELETE');
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Request for destroying Droplet {$callVars["virtual_machine_id"]} complete", $this->getModuleName()) ;
        return $callOut ;
    }

}