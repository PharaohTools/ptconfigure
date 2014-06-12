<?php

Namespace Model;

class GameBlocksList extends BaseGameBlocksAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function __construct($params) {
        parent::__construct($params) ;
    }

    public function askWhetherToListData() {
        return $this->performGameBlocksListData();
    }

    protected function performGameBlocksListData(){
        if ($this->askForListExecute() != true) { return false; }
        $this->apiKey = $this->askForGameBlocksAPIKey();
        $this->clientId = $this->askForGameBlocksClientID();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromGameBlocks($dataToList);
    }

    private function askForListExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    private function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        $options = array("droplets", "sizes", "images", "domains", "regions", "ssh_keys");
        if (isset($this->params["game-blocks-list-data-type"]) &&
            in_array($this->params["game-blocks-list-data-type"], $options)) {
            return $this->params["game-blocks-list-data-type"] ; }
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromGameBlocks($dataToList){
        $callVars = array();
        $curlUrl = "https://api.digitalocean.com/$dataToList/" ;
        return $this->gameBlocksCall($callVars, $curlUrl);
    }

}