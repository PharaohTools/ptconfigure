<?php

Namespace Model;

class DigitalOceanAllBoxesDestroy extends BaseDigitalOceanAllOS {

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
        $this->apiKey = $this->askForDigitalOceanAPIKey();
        $this->clientId = $this->askForDigitalOceanClientID();

        $doFactory = new \Model\DigitalOcean();
        $listParams = array("yes" => true, "guess" => true, "digital-ocean-list-data-type" => "droplets") ;
        $doListing = $doFactory->getModel($listParams, "Listing") ;
        $allBoxes = $doListing->askWhetherToListData();

        foreach($allBoxes->droplets as $box) {
            $serverData["dropletID"] = $box->id ;
            $responses[] = $this->destroyServerFromDigitalOcean($serverData) ;
        }

        return true ;

    }

    private function askForOverwriteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Destroy All Digital Ocean Server Boxes? (Careful!)';
        return self::askYesOrNo($question);
    }

    private function destroyServerFromDigitalOcean($serverData) {
        $callVars = array() ;
        $callVars["droplet_id"] = $serverData["dropletID"];
        $curlUrl = "https://api.digitalocean.com/droplets/{$callVars["droplet_id"]}/destroy" ;
        $callOut = $this->digitalOceanCall($callVars, $curlUrl);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Request for destroying Droplet {$callVars["droplet_id"]} complete") ;
        return $callOut ;
    }

}