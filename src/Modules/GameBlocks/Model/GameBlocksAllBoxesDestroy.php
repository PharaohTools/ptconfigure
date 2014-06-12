<?php

Namespace Model;

class GameBlocksAllBoxesDestroy extends BaseGameBlocksAllOS {

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
        $this->apiKey = $this->askForGameBlocksAPIKey();
        $this->clientId = $this->askForGameBlocksClientID();

        $doFactory = new \Model\GameBlocks();
        $listParams = array("yes" => true, "guess" => true, "game-blocks-list-data-type" => "droplets") ;
        $doListing = $doFactory->getModel($listParams, "Listing") ;
        $allBoxes = $doListing->askWhetherToListData();

        foreach($allBoxes->droplets as $box) {
            $serverData["dropletID"] = $box->id ;
            $responses[] = $this->destroyServerFromGameBlocks($serverData) ;
        }

        return true ;

    }

    private function askForOverwriteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Destroy All Game Blocks Server Boxes? (Careful!)';
        return self::askYesOrNo($question);
    }

    private function destroyServerFromGameBlocks($serverData) {
        $callVars = array() ;
        $callVars["droplet_id"] = $serverData["dropletID"];
        $curlUrl = "https://api.digitalocean.com/droplets/{$callVars["droplet_id"]}/destroy" ;
        $callOut = $this->gameBlocksCall($callVars, $curlUrl);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Request for destroying Droplet {$callVars["droplet_id"]} complete") ;
        return $callOut ;
    }

}