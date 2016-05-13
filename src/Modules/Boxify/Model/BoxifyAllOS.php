<?php

Namespace Model;

class BoxifyAllOS extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $environmentName ;
    protected $providerName ;
    protected $boxAmount ;
    protected $requestingModule ;
    protected $actionsToMethods =
        array(
            "box-add" => "performBoxAdd",
            "box-ensure" => "performBoxEnsure",
            "box-destroy" => "performBoxDestroy",
            "box-remove" => "performBoxRemove",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Boxify";
        $this->programNameMachine = "boxify"; // command and app dir name
        $this->programNameFriendly = "Boxify!"; // 12 chars
        $this->programNameInstaller = "Boxify your Environments";
        $this->initialize();
    }

    public function performBoxAdd($providerName = null, $environmentName = null, $boxAmount = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        $this->setBoxAmount($boxAmount);
        return $this->addBox();
    }

    public function performBoxEnsure($providerName = null, $environmentName = null, $boxAmount = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        $this->setBoxAmount($boxAmount);
        return $this->ensureBoxes();
    }

    public function performBoxRemove($providerName = null, $environmentName = null) {
        $this->setEnvironment($environmentName);
        return $this->removeBoxes();
    }

    public function performBoxDestroy($providerName = null, $environmentName = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        return $this->destroyBoxes();
    }

    public function setEnvironment($environmentName = null) {
        if (isset($environmentName)) {
            $this->environmentName = $environmentName; }
        else if (isset($this->params["env"])) {
            $this->environmentName = $this->params["env"]; }
        else if (isset($this->params["environmentname"])) {
            $this->environmentName = $this->params["environmentname"]; }
        else if (isset($this->params["environment-name"])) {
            $this->environmentName = $this->params["environment-name"]; }
        else {
            $this->environmentName = self::askForInput("Enter Environment Name:", true); }
    }

    public function setProvider($providerName = null) {
        if (isset($providerName)) {
            $this->providerName = $providerName; }
        else if (isset($this->params["provider"])) {
            $this->providerName = $this->params["provider"]; }
        else if (isset($this->params["providername"])) {
            $this->providerName = $this->params["providername"]; }
        else if (isset($this->params["provider-name"])) {
            $this->providerName = $this->params["provider-name"]; }
        else {
            $this->providerName = self::askForInput("Enter Provider Name:", true); }
    }

    public function setBoxAmount($boxAmount = null) {
        if (isset($boxAmount)) {
            $this->boxAmount = $boxAmount; }
        else if (isset($this->params["boxamount"])) {
            $this->boxAmount = $this->params["boxamount"]; }
        else if (isset($this->params["box-amount"])) {
            $this->boxAmount = $this->params["box-amount"]; }
        else {
            $this->boxAmount = self::askForInput("Enter number of Boxes:", true); }
    }

    protected function addBox($params = null) {
        if ($params == null) { $params = $this->params ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($params);
        $provider = $this->getProvider("BoxAdd", $params);
        if (!is_object($provider)) {
            $logging->log("Requested provider unavailable", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1);
            return false ; }
        $returns = array() ;
        $logging->log("Adding Boxes", $this->getModuleName()) ;
        $result = $provider->addBox() ;
        $returns[] = $result ;
        return (in_array(false, $returns)) ? false : true ;
    }

    protected function ensureBoxes() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $provider = $this->getProvider();
        if (!is_object($provider)) {
            $logging->log("Requested provider unavailable", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1);
            return false ; }

        $boxifyFactory = new \Model\Boxify();
        $lister = $boxifyFactory->getModel($this->params, "Listing");
        $curboxes = $lister->performListing() ;
        $logging->log("Ensuring environment", $this->getModuleName()) ;

//        var_dump($curboxes[0]["servers"]) ;

        $serv_count = (is_array($curboxes["servers"]) && count($curboxes["servers"])>0) ? count($curboxes["servers"]) : 0 ;
        $logging->log("Current number of nodes in environment is {$serv_count}", $this->getModuleName()) ;
        $logging->log("Expected number of nodes in environment is {$this->boxAmount}", $this->getModuleName()) ;
        $cur_statuses = $this->checkCurNodeStatuses($curboxes) ;

        if ($serv_count > $this->boxAmount) {
            $diff = $serv_count - $this->boxAmount ;
            $logging->log("{$diff} more boxes found in environment than needed", $this->getModuleName()) ;
            $logging->log("Removing Extra Boxes", $this->getModuleName()) ;
            $this->fixBrokenNodes($cur_statuses);
//            for ($i=1 ; $i<=$diff; $i++) {
////                $result = $provider->destroyBox() ;
//            }
//            return true ;
        }
        else if ($serv_count < $this->boxAmount) {
            $diff =  $this->boxAmount - $serv_count ;
            $logging->log("{$diff} less boxes found in environment than needed", $this->getModuleName()) ;
            $logging->log("Adding Extra Boxes", $this->getModuleName()) ;

            $this->fixBrokenNodes($cur_statuses);
//            for ($i=1 ; $i<=$diff; $i++) {
//
//                $provider = $this->getProvider("BoxAdd");
//                $result = $provider->addBox() ; }
//            return false ;
        }
        else {
            $logging->log("Box group sizes match", $this->getModuleName()) ;
            if ($cur_statuses["all_stats"] == true) {
                $logging->log("All environment nodes in correct status", $this->getModuleName()) ;
                return true ; }
            $logging->log("Found nodes in unexpected status", $this->getModuleName()) ;
            $logging->log("Fix broken nodes", $this->getModuleName()) ;
            $res = $this->fixBrokenNodes($cur_statuses);
            return $res ;
//            var_dump($cur_statuses) ;
        }
        return true ;

    }

    protected function checkCurNodeStatuses($curboxes) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Checking current node statuses in environment {$curboxes["any-app"]["gen_env_name"]}", $this->getModuleName()) ;

        $results = array() ;

        $i = 0 ;

//        var_dump("daze:", $curboxes) ;
        foreach($curboxes["servers"] as $oneNode) {
            $logging->log("Testing Node {$oneNode["name"]} from provider {$oneNode["provider"]}", $this->getModuleName()) ;

            $providerParams = $this->params ;
//            $providerParams["yes"] = true ;
            $providerParams["id"] = $oneNode["id"] ;
            $providerParams["name"] = $oneNode["name"] ;
            $providerParams["image"] = $oneNode["image"] ;
            $provider = $this->getProvider("NodeTest", $providerParams);
            $nodeTest = $provider->askWhetherToTestNode() ;
            $i ++ ;
            $results["tests"][$i] = $nodeTest ;
            if ($nodeTest["status"] == false) $all_stats_failure = false;
//            var_dump($nodeTest) ;
        }

        if (isset($all_stats_failure)) { $results["all_stats"] = false ; }
        else {  $results["all_stats"] = true ; }
        return $results ;
    }

    protected function fixBrokenNodes($curboxes) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        // how many do we want
        // how many do we have
        // how many do we have healthy
        $how_many_wanted = $this->boxAmount ;
        $how_many_current = (isset($curboxes["tests"]) && is_array($curboxes["tests"])) ? count($curboxes["tests"]) : 0 ;
        $how_many_current_healthy = $this->countHealthyBoxes($curboxes) ;
        $logging->log("Expecting {$how_many_wanted} Nodes", $this->getModuleName()) ;
        $logging->log("{$how_many_current} current Nodes", $this->getModuleName()) ;
        $logging->log("{$how_many_current_healthy} healthy Nodes", $this->getModuleName()) ;
        // if 1) we have more than we want
        if ($how_many_current > $how_many_wanted) {
            $logging->log("Currently more Nodes than needed", $this->getModuleName()) ;
            //     a) we have more healthy than we want
            if ($how_many_current_healthy > $how_many_wanted) {
                $logging->log("Currently more healthy Nodes ({$how_many_current_healthy}) than needed ($how_many_wanted)", $this->getModuleName()) ;
                //     + remove all unhealthy
                $this->nodeAddRemove($curboxes, false, true) ;
                //     + remove highest numbered healthy
                $boxifyFactory = new \Model\Boxify();
                $lister = $boxifyFactory->getModel($this->params, "Listing");
                $curboxes = $lister->performListing() ;
                $diff = $how_many_current_healthy - $how_many_wanted ;
                $this->nodeRemove($curboxes, $diff) ; }
            //     b) we have less healthy than we want
            else if ($how_many_current_healthy < $how_many_wanted) {
                $logging->log("Currently less healthy Nodes ({$how_many_current_healthy}) than needed ($how_many_wanted)", $this->getModuleName()) ;
                //     + fix broken (as many as needed, stop fixing if hit how many we want limit)
                $this->nodeAddRemove($curboxes, true, true) ;
                //       a) fixing has looped to the number of nodes we need
                //       b) fixing has fixed all available
                //     + calculate difference between healthy wanted and have
                $diff = $how_many_wanted - $how_many_current_healthy ;
                //     + create outstanding nodes (if needed)
                $this->nodeAdd($diff, $how_many_current_healthy) ;}
            else {
                $logging->log("Currently have required number of healthy nodes ({$how_many_current_healthy})", $this->getModuleName()) ; }
            return true ; }
        //    2) we have less than or equal to what we want
        else if ($how_many_current <= $how_many_wanted) {
            //     + fix all broken
            $this->nodeAddRemove($curboxes, true, true) ;
            //     if less than
            if ($how_many_current < $how_many_wanted) {
                //     + create outstanding nodes
                $diff = $how_many_wanted - $how_many_current;
                $this->nodeAdd($diff, $how_many_current) ; }
            return true ; }
        return true ;
    }

    protected function nodeAddRemove($curboxes, $add = false, $remove = false) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $i = 0 ;

        if (isset($curboxes["tests"]) && is_array($curboxes["tests"]) && count($curboxes)>0) {
            foreach($curboxes["tests"] as $oneTest) {
                if ($oneTest["status"]==false) {
                    if ($remove == true) {
                        $logging->log( "Fixing broken node id {$oneTest["info"]["id"]}, name {$oneTest["info"]["name"]}", $this->getModuleName() ) ;
                        $destroyParams = $this->params ;
                        $destroyParams["destroy-box-id"] = $oneTest["info"]["id"] ;
                        $nodeDestroy = $this->destroyBoxes($destroyParams) ;
                        $i ++ ;
                        $results["destroys"][$i] = $nodeDestroy ;
                        if ($nodeDestroy["status"] == false) $all_stats_failure = false; }

                    if ($add == true) {
                        $logging->log( "Rebuilding new node", $this->getModuleName() ) ;
                        $addParams = $this->params ;
                        $addParams["box-amount"] = 1 ;
                        $nodeAdd = $this->addBox($addParams) ;
                        $i ++ ;
                        $results["creates"][$i] = $nodeAdd ;
                        if ($nodeAdd["status"] == false) $all_stats_failure = false; } }
                else {
                    $logging->log(
                        "Node id {$oneTest["info"]["id"]}, name {$oneTest["info"]["name"]} is healthy", $this->getModuleName() ) ; } } }
        else if (count($curboxes)==0) {
            $logging->log("No boxes available to test", $this->getModuleName()) ; }
        else {
            $logging->log("Unable to test boxes", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ; }
        if (isset($all_stats_failure)) { $results["all_stats"] = false ; }
        else {  $results["all_stats"] = true ; }
        return $results ;
    }

    protected function nodeAdd($diff, $offset=0) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Adding new nodes diff is {$diff}", $this->getModuleName() ) ;
        $addParams = $this->params ;
        $addParams["box-amount"] = $diff ;
        $addParams["count-start"] = $offset ;
        $nodeAdd = $this->addBox($addParams) ;
        if ($nodeAdd["status"] == false) $nodeAdd = false;
        return $nodeAdd ;
    }

    protected function nodeRemove($curboxes, $diff) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Removing nodes", $this->getModuleName() ) ;
        $curboxes = array_reverse($curboxes) ;
        $nodeRemoves = array() ;
//        var_dump($curboxes) ;
        $icount = count($curboxes)-1 ;
        for ($i=$icount; $i>=0; $i--) {
            $delParams = $this->params ;
            $delParams["destroy-box-id"] = $curboxes["servers"][$i]["id"] ;
            $nodeRemoves[] = $this->destroyBoxes($delParams) ; }
        return $nodeRemoves ;
    }

    protected function countHealthyBoxes($curboxes) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $healthy_count = 0 ;
        if (isset($curboxes["tests"]) && is_array($curboxes["tests"])) {
            foreach($curboxes["tests"] as $oneBox) {
                if ($oneBox["status"]==true) {
                    $healthy_count ++ ; } } }
        else {
            $logging->log("Unable to test boxes", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ; }
        $logging->log("Healthy count is {$healthy_count}", $this->getModuleName()) ;
        return $healthy_count ;
    }

    protected function removeBoxes() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        foreach($this->boxAmount as $oneBox) {
            $logging->log("Removing Box $oneBox", $this->getModuleName()) ;
//            $this->setEnvironmentStatusInCleovars($oneBox, false) ;
        }
        return true ;
    }

    protected function destroyBoxes($params = null) {
        if ($params == null) { $params = $this->params ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($params);

        if (isset($params["destroy-all-boxes"]) && $params["destroy-all-boxes"] == true) {
            $params["destroy-all-boxes"] = "true" ; }

        $provider = $this->getProvider("BoxDestroy", $params);
        if (!is_object($provider)) {
            $logging->log("Requested provider unavailable", $this->getModuleName()) ;
            return false ; }
        $logging->log("Destroying Boxes in environment $this->environmentName", $this->getModuleName()) ;

        $return = $provider->destroyBox() ;
        return $return ;
    }

    protected function getProvider($modGroup = "BoxAdd", $params = null) {
        if ($params == null) { $params = $this->params ; }
        $infoObjects = \Core\AutoLoader::getInfoObjects();
        $allProviders = array();
        foreach($infoObjects as $infoObject) {
            if ( method_exists($infoObject, "boxProviderName") ) {
                $allProviders[] = $infoObject->boxProviderName(); } }
        foreach($allProviders as $oneProvider) {
            if ( (isset($this->providerName) && $this->providerName == $oneProvider) ) {
                $className = '\Model\\'.$oneProvider ;
                $providerFactory = new $className();
                $provider = $providerFactory->getModel($params, $modGroup);
                return $provider ; } }
        return false ;
    }

}
