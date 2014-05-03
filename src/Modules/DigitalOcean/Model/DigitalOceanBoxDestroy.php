<?php

Namespace Model;

class DigitalOceanBoxDestroy extends BaseDigitalOceanAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("BoxDestroy") ;

    public function askWhetherToBoxDestroy() {
        $out = $this->destroyBox();
        var_dump($out) ;
        return $out ;
    }

    public function destroyBox() {
        if ($this->askForOverwriteExecute() != true) { return false; }
        echo "here" ;
        $this->apiKey = $this->askForDigitalOceanAPIKey();
        $this->clientId = $this->askForDigitalOceanClientID();
        $environments = \Model\AppConfig::getProjectVariable("environments");
        $workingEnvironment = $this->getWorkingEnvironment();

        foreach ($environments as $environment) {
            if (isset($environment["any-app"]["gen_env_name"]) && $environment["any-app"]["gen_env_name"] == $workingEnvironment) {
                $environmentExists = true ; } }

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if (isset($environmentExists)) {
            for ($i = 0; $i<count($environments); $i++) {
                if ($environments[$i]["any-app"]["gen_env_name"] == $workingEnvironment) {
                    $envName = $environments[$i]["any-app"]["gen_env_name"];

                    if (isset($this->params["yes"]) && $this->params["yes"]==true) {
                        $removeFromThisEnvironment = true ; }
                    else {
                        $question = 'Remove Digital Ocean Server Boxes from '.$envName.'?';
                        $removeFromThisEnvironment = self::askYesOrNo($question); }

                    if ($removeFromThisEnvironment == true) {
                        if (isset($this->params["destroy-all-boxes"])) {
                            $responses = array();
                            for ($iBox = 0; $iBox < count($environments[$i]["servers"]); $iBox++) {
                                $serverData = array();
                                $serverData["dropletID"] = $environments[$i]["servers"][$iBox]["id"] ;
                                $responses[] = $this->destroyServerFromDigitalOcean($serverData) ;
                                $this->deleteServerFromPapyrus($workingEnvironment, $serverData["dropletID"]);
                                return true ; } }
                        else if (isset($this->params["destroy-box-id"])) {
                            $responses = array();
                            $serverData = array();
                            $serverData["dropletID"] = $this->params["destroy-box-id"] ;
                            $responses[] = $this->destroyServerFromDigitalOcean($serverData) ;
                            $this->deleteServerFromPapyrus($workingEnvironment, $serverData["dropletID"]);
                            return true ; }
                        else {
                            echo "bum" ; //@todo
                            $responses = (isset($responses)) ? $responses : "anything else" ; } } } }
            return true ; }
        else {
            $logging->log("The environment $workingEnvironment does not exist.") ; }
    }

    private function askForOverwriteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Destroy Digital Ocean Server Boxes?';
        return self::askYesOrNo($question);
    }

    private function getWorkingEnvironment() {
        if (isset($this->params["environment-name"])) {
            return $this->params["environment-name"] ; }
        $question = 'Enter Environment to destroy Servers in';
        return self::askForInput($question);
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

    private function deleteServerFromPapyrus($workingEnvironment, $dropletId) {
        $environments = \Model\AppConfig::getProjectVariable("environments");
        $newServers = array() ;
        foreach ($environments as &$environment) {
            echo "a".$environment["any-app"]["gen_env_name"].$workingEnvironment."\n" ;
            if ($environment["any-app"]["gen_env_name"] == $workingEnvironment) {
                echo "b"."\n" ;
                foreach ($environment["servers"] as $server ) {
                    echo "c".$server["id"].$dropletId."\n" ;
                    if ($server["id"] != $dropletId) {
                        echo "d"."\n" ;
                        echo "setting {$server["id"]} as new srv\n" ;
                        $newServers[] = $server ; }
                    echo "saving the following to papyrus\n" ;
                    $environment["servers"] = $newServers ; }
                // var_dump($environments);
                \Model\AppConfig::setProjectVariable("environments", $environments); } }
    }

}