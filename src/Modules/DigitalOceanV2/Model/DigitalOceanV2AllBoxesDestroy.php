<?php

Namespace Model;

class DigitalOceanV2AllBoxesDestroy extends BaseDigitalOceanV2AllOS {

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
        $this->accessToken = $this->askForDigitalOceanV2AccessToken();
        $doFactory = new \Model\DigitalOceanV2();
        $listParams = array("yes" => true, "guess" => true, "type" => "droplets") ;
        $doListing = $doFactory->getModel($listParams, "Listing") ;
        $allBoxes = $doListing->askWhetherToListData();

        foreach($allBoxes->droplets as $box) {
            $serverData["dropletID"] = $box->id ;
            $responses[] = $this->destroyServerFromDigitalOceanV2($serverData) ; }
        return true ;

    }

    private function askForOverwriteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Destroy All Digital Ocean Server Boxes? (Careful!)';
        return self::askYesOrNo($question);
    }

    private function destroyServerFromDigitalOceanV2($serverData) {
        $callVars = array() ;
        $callVars["droplet_id"] = $serverData["dropletID"];
        $curlUrl = $this->_apiURL."/v2/droplets/{$callVars["droplet_id"]}" ;
        $callOut = $this->digitalOceanV2Call($callVars, $curlUrl,'DELETE');
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Request for destroying Droplet {$callVars["droplet_id"]} complete", $this->getModuleName()) ;
        return $callOut ;
    }

}