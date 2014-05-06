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
        return $out ;
    }

    public function destroyBox() {
        if ($this->askForOverwriteExecute() != true) { return false; }
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
                                echo "about to del\n" ;
                                $this->deleteServerFromPapyrus($workingEnvironment, $serverData["dropletID"]); }
                            return true ; }
                        else if (isset($this->params["destroy-box-id"])) {
                            $responses = array();
                            $serverData = array();
                            $serverData["dropletID"] = $this->params["destroy-box-id"] ;
                            $responses[] = $this->destroyServerFromDigitalOcean($serverData) ;
                            $this->deleteServerFromPapyrus($workingEnvironment, $serverData["dropletID"]);
                            return true ; }
                        else {
                            \Core\BootStrap::setExitCode(1) ;
                            $logging->log("You must provide either parameter --destroy-all-boxes or --destroy-box-id");
                            return false ; } } } }
            return true ; }
        else {
            \Core\BootStrap::setExitCode(1) ;
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
            if ($environment["any-app"]["gen_env_name"] == $workingEnvironment) {
                foreach ($environment["servers"] as $server ) {
                    if ($server["id"] != $dropletId) { $newServers[] = $server ; }
                    $environment["servers"] = $newServers ; }
                \Model\AppConfig::setProjectVariable("environments", $environments); } }
    }

}