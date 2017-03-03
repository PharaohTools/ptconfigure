<?php

Namespace Model;

class DigitalOceanV2LoadBalancer extends BaseDigitalOceanV2AllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("LoadBalancer") ;

    public function askForLoadBalancerAddExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Ensure Digital Ocean Load Balancer?';
        return self::askYesOrNo($question);
    }

    public function getLoadBalancerPrefix() {
        if (isset($this->params["server-prefix"])) {
            return $this->params["server-prefix"] ; }
        if (isset($this->params["prefix"])) {
            return $this->params["prefix"] ; }
        $question = 'Enter Prefix for all LoadBalancers (None is fine)';
        return self::askForInput($question);
    }

    public function getLoadBalancerCountStart() {
        if (isset($this->params["count-start"])) {
            return $this->params["count-start"] ; }
        if (isset($this->params["countstart"])) {
            $this->params["count-start"] = $this->params["countstart"] ;
            return $this->params["count-start"] ; }
        if (isset($this->params["guess"])) {
            $this->params["count-start"] = 0 ;
            return $this->params["count-start"] ;  }
        $question = 'Enter Count to begin from';
        return self::askForInput($question);
    }

    public function getLoadBalancerGroupRegionID() {
        if (isset($this->params["region-id"])) {
            return $this->params["region-id"] ; }
        $question = 'Enter Region ID';
        return self::askForInput($question, true);
    }

    public function getLoadBalancerGroupLoadBalancerAmount() {
        if (isset($this->params["box-amount"])) {
            return $this->params["box-amount"] ; }
        $question = 'Enter number of boxes to add to Environment';
        $this->params["box-amount"] = self::askForInput($question, true);
        return $this->params["box-amount"] ;
    }

    public function askForSSHKeyIds() {
        $question = 'Enter SSH Key ID\'s, comma separated';
        return self::askForInput($question, true);
    }

    public function getNewLoadBalancerFromDigitalOceanV2($serverData) {
        $callVars = array() ;
        $callVars["name"] = $serverData["name"];
//        $callVars["size"] = $serverData["sizeID"];
//        $callVars["image"] = $serverData["imageID"];
        $callVars["region"] = $serverData["regionID"];
//        $callVars["ssh_keys"] = $serverData["sshKeyIds"] ;


        $curlUrl = $this->_apiURL."/v2/load_balancers/" ;
        $httpType = "POST" ;
        $callOut = $this->digitalOceanV2Call($callVars, $curlUrl, $httpType);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Request for {$callVars["name"]} complete", $this->getModuleName()) ;
        if ( isset($callOut->id) && $callOut->id == "unprocessable_entity") {
            $logging->log("Request for {$callVars["name"]} errored with message: {$callOut->message}", $this->getModuleName()) ; }
        return $callOut ;
    }

    public function addLoadBalancerToPapyrus($envName, $data) {

        if (!isset($data->load_balancer)) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
//            debug_print_backtrace() ;
            $logging->log("Error, attempted adding server to papyrus with no data", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }

        if (isset($data) && is_object($data)) {
            $load_balancerData = $this->getLoadBalancerData($data->load_balancer->id);
            if (!isset($load_balancerData->load_balancer->networks->v4[0]->ip_address) && isset($this->params["wait-for-box-info"])) {
                $load_balancerData = $this->waitForLoadBalancerInfo($data->load_balancer->id); }
            if (($load_balancerData->load_balancer->status != "active") && isset($this->params["wait-until-active"])) {
                $load_balancerData = $this->waitUntilActive($data->load_balancer->id); }
            $server = array();
//            var_dump('net', $load_balancerData->load_balancer->networks) ;

            foreach ($load_balancerData->load_balancer->networks->v4 as $iface) {
                if ($iface->type == 'private') {
                    $server["target_private"] = $iface->ip_address;
                    if ( (isset($this->params["default-target"]) && $this->params["default-target"] == 'private') ||
                        !isset($this->params["default-target"])) {
                        $server["target"] = $iface->ip_address; } }
                else if ($iface->type == 'public') {
                    $server["target_public"] = $iface->ip_address;
                    if ( (isset($this->params["default-target"]) && $this->params["default-target"] == 'public') ||
                        !isset($this->params["default-target"])) {
                        $server["target"] = $iface->ip_address; } } }
//            $server["target"] = $load_balancerData->load_balancer->networks->v4[0]->ip_address;
//            $server["user"] = $this->getUsernameOfLoadBalancer() ;
//            $server["password"] = $this->getSSHKeyLocation() ;
            $server["provider"] = "DigitalOceanV2";
            $server["id"] = $data->load_balancer->id;
            $server["name"] = $data->load_balancer->name;
//            $server["image"] = $data->load_balancer->image->id;
            // file_put_contents("/tmp/outloc", getcwd()) ;
            // file_put_contents("/tmp/outsrv", $server) ;
            $environments = \Model\AppConfig::getProjectVariable("environments");
            // file_put_contents("/tmp/outenv1", serialize($environments)) ;
            for ($i= 0 ; $i<count($environments); $i++) {
                if ($environments[$i]["any-app"]["gen_env_name"] == $envName) {
                    $environments[$i]["servers"][] = $server; } }
            // file_put_contents("/tmp/outenv2", serialize($environments)) ;
            \Model\AppConfig::setProjectVariable("environments", $environments);
            return true ; }
        else {
            return false ; }
    }


    /**
     * Get load_balancer information via load_balancer-id
     * @param $load_balancerId
     * @return mixed
     */
    public function getLoadBalancerData($load_balancerId) {
        $curlUrl = $this->_apiURL."/v2/load_balancers/$load_balancerId" ;
        $load_balancerObject =  $this->digitalOceanV2Call(array(), $curlUrl);
        return $load_balancerObject;
    }

    public function waitForLoadBalancerInfo($load_balancerId) {
        $maxWaitTime = (isset($this->params["max-box-info-wait-time"])) ? $this->params["max-box-info-wait-time"] : "300" ;
        $i2 = 1 ;
        for($i=0; $i<=$maxWaitTime; $i=$i+10){
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Attempt $i2 for load_balancer $load_balancerId box info...", $this->getModuleName()) ;
            $load_balancerData = $this->getLoadBalancerData($load_balancerId);
            if (isset($load_balancerData->load_balancer->networks->v4[0]->ip_address)) {
                return $load_balancerData ; }
            sleep (10);
            $i2++; }
        return null;
    }

    public function waitUntilActive($load_balancerId) {
        $maxWaitTime = (isset($this->params["max-active-wait-time"])) ? $this->params["max-active-wait-time"] : "300" ;
        $i2 = 1 ;
        for($i=0; $i<=$maxWaitTime; $i=$i+10){
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Attempt $i2 for load_balancer $load_balancerId to become active...", $this->getModuleName()) ;
            $load_balancerData = $this->getLoadBalancerData($load_balancerId);
            if (isset($load_balancerData->load_balancer->status) && $load_balancerData->load_balancer->status=="active") {
                return $load_balancerData ; }
            sleep (10);
            $i2++; }
        return null;
    }



    public function askWhetherToAddNode($params=null) {
        return $this->addNode($params);
    }

    public function askWhetherToAddLoadBalancer() {
        return $this->addLoadBalancer();
    }

    public function askWhetherToEnsureLoadBalancer() {
        return $this->EnsureLoadBalancer();
    }

    public function askWhetherToListLoadBalancer($params=null) {
        return $this->listLoadBalancer($params);
    }

    public function askWhetherToListNode($params=null) {
        return $this->listNode($params);
    }

    public function askWhetherToRemoveLoadBalancer($params=null) {
        return $this->RemoveLoadBalancer($params);
    }

//    public function RemoveLoadBalancer() {
//        if ($this->askForLoadBalancerAddExecute() != true) { return false; }
//        $this->initialiseRackspace();
//        $this->getLoadBalancerName();
//        try {
//            $service = $this->rackspaceClient->loadBalancerService(null,$this->region);  }
//        catch (\Exception $e) {
//            $logging->log("Error initialising the Rackspace service: ".$e->getMessage() , $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            return false ; }
//        $loadBalancers = $service->loadBalancerList(false);
//        $loadBalancer="";
//        foreach($loadBalancers as $load) {
//            if($load->name==$this->params["name"]) {
//                $loadBalancer = $load; } }
//        $loadBalancer->delete();
//        return $loadBalancer;
//    }


//    public function EnsureLoadBalancer() {
//        if ($this->askForLoadBalancerAddExecute() != true) { return false; }
//        if ($this->initialiseRackspace() != true) { return false; }
//        $this->getLoadBalancerName();
//        $this->getPortNumber();
//        $env = $this->getWorkingEnvironment() ;
//        $loggingFactory = new \Model\Logging();
//        $logging = $loggingFactory->getModel($this->params);
//        try {
//            $service = $this->rackspaceClient->loadBalancerService(null,$this->region);  }
//        catch (\Exception $e) {
//            $logging->log("Error initialising the Rackspace service", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            $logging->log("Error: ".$e->getMessage() , $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            return false ; }
//        if (!isset($this->params["public-ip"]) && !isset($this->params["private-ip"])) {
//            $logging->log("Your Load Balancer must have at least one Public or Private IP Address", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            return false ; }
//        $logging->log("Attempting to create Load Balancer", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//        try {
//            $loadBalancer = $service->loadBalancer();
//            if (isset($this->params["public-ip"])) { $loadBalancer->addVirtualIp('PUBLIC', 4); }
//            if (isset($this->params["private-ip"])) { $loadBalancer->addVirtualIp('PRIVATE', 4); }
//            $loadBalancer->create( array(
//                'name'     => $this->params["name"],
//                'port'     => $this->params["port"],
//                'protocol' => $this->params["protocol"] ) );}
//        catch (\Exception $e) {
//            $logging->log("Error from Rackspace Load Balancer creation service", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            $logging->log("Error: ".$e->getMessage() , $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            return false ; }
//        $logging->log("Adding Balancer to Papyrus", $this->getModuleName()) ;
//        $this->addBalancerToPapyrus($env, $loadBalancer);
//        return $loadBalancer;
//    }
//
//    public function AddLoadBalancer() {
//        if ($this->askForLoadBalancerAddExecute() != true) { return false; }
//        $this->initialiseRackspace();
//        $this->getLoadBalancerName();
//        $this->getProtocol();
//        $this->getPortNumber();
//        $env = $this->getWorkingEnvironment() ;
//        $loggingFactory = new \Model\Logging();
//        $logging = $loggingFactory->getModel($this->params);
//        try {
//            $service = $this->rackspaceClient->loadBalancerService(null,$this->region);  }
//        catch (\Exception $e) {
//            $logging->log("Error initialising the Rackspace service", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            $logging->log("Error: ".$e->getMessage() , $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//            return false ; }
//        $loadBalancer = $service->loadBalancer();
//        if (!isset($this->params["public-ip"]) && !isset($this->params["private-ip"])) {
//            $logging->log("Your Load Balancer must have at least one Public or Private IP Address", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ; }
//        if (isset($this->params["public-ip"])) { $loadBalancer->addVirtualIp('PUBLIC', 4); }
//        if (isset($this->params["private-ip"])) { $loadBalancer->addVirtualIp('PRIVATE', 4); }
//        $logging->log("Attempting to create Load Balancer", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
//
//
//        $LoadBalancers= $service->loadBalancerList(false);
//        var_dump($LoadBalancers) ;
//
//
//        $loadBalancer->create( array(
//            'name'     => $this->params["name"],
//            'port'     => $this->params["port"],
//            'protocol' => $this->params["protocol"] ) );
//        $logging->log("Adding Balancer to Papyrus", $this->getModuleName()) ;
//        $this->addBalancerToPapyrus($env, $loadBalancer);
//        return $loadBalancer;
//    }


    public function addNode() {
        if ($this->askForObjectAddExecute() != true) { return false; }
        $this->initialiseRackspace();
        $this->getLoadBalancerName();
        $this->getServerId();
        $this->getPortNumber();
        $service = $this->rackspaceClient->loadBalancerService(null,$this->region);
        $loadBalancers = $service->loadBalancerList(false);
        $loadBalancer="";
        foreach($loadBalancers as $load) {
            if($load->name==$this->params["name"]) {
                $loadBalancer = $load; } }

        $balancerService = $this->rackspaceClient->computeService('cloudServersOpenStack',$this->region);
        $balancers = $this->params["server-id"] ;
        foreach ($balancers as $balancer) {
            $balancer = $balancerService->server($balancer);
            $balancerOneNode = $loadBalancer->node(array(
                'address'   => $balancer->addresses->private[0]->addr,
                'port'      => $this->params["port"],
                'condition' => NodeCondition::ENABLED
            ));
            $result = $balancerOneNode->create();
//        $result = new StdClass() ;
//            $result->status = "created" ;
//            var_dump($result) ;
            $results[] = $result ; }
        return $results;
    }


    protected function getWorkingEnvironment() {
        if (isset($this->params["environment-name"])) {
            return $this->params["environment-name"] ; }
        if (isset($this->params["env"])) {
            return $this->params["env"] ; }
        $question = 'Enter Environment to add Load Balancer to';
        return self::askForInput($question);
    }

    protected function turnParameterToArray($param) {
        if (!is_array($param)) {
            $param = explode(",", $param) ; }
        return  $param ;
    }
    protected function getPortNumber(){
        if (isset($this->params["port"])) { return ; }
        $question = 'Enter Port Number:';
        $this->params["port"] = self::askForInput($question, true);
    }

    protected function getProtocol(){
        if (isset($this->params["protocol"])) { return ; }
        $question = 'Enter Load Balancer Protocol:';
        $this->params["protocol"] = self::askForInput($question, true);
    }

    protected function getLoadBalancerName() {
        if (isset($this->params["name"])) { return ; }
        $question = 'Enter Load Balancer Name:';
        $this->params["name"] = self::askForInput($question, true);
    }

    protected function getMaxWaitTime() {
        if (isset($this->params["max-active-wait-time"])) { return $this->params["max-active-wait-time"] ; }
        return "300";
    }



    public function addLoadBalancer() {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if ($this->askForLoadBalancerAddExecute() != true) { return false; }
        $this->accessToken = $this->askForDigitalOceanV2AccessToken();
        if (strlen($this->accessToken)==0) {
            \Core\BootStrap::setExitCode(1) ;
            $logging->log("Unable to initialize Digital Ocean credentials.", $this->getModuleName()) ;
            return false ;
        }
        $serverPrefix = $this->getLoadBalancerPrefix();
        $environments = \Model\AppConfig::getProjectVariable("environments");

        $workingEnvironment = $this->getWorkingEnvironment();

        foreach ($environments as $environment) {
            if ($environment["any-app"]["gen_env_name"] == $workingEnvironment) {
                $environmentExists = true ; } }

        if (isset($environmentExists)) {
            foreach ($environments as $environment) {
                if ($environment["any-app"]["gen_env_name"] == $workingEnvironment) {
                    $envName = $environment["any-app"]["gen_env_name"];

                    if (isset($this->params["yes"]) && $this->params["yes"]==true) {
                        $addToThisEnvironment = true ; }
                    else {
                        $question = 'Add Digital Ocean Load Balancers to '.$envName.'?';
                        $addToThisEnvironment = self::askYesOrNo($question); }

                    if ($addToThisEnvironment == true) {
                        $box_amount=$this->getLoadBalancerGroupLoadBalancerAmount();
                        for ($i = 0; $i < $box_amount; $i++) {
                            $serverData = array();
                            $serverData["prefix"] = $serverPrefix ;
                            $serverData["envName"] = $envName ;
                            $serverData["sCount"] = $i + $this->getLoadBalancerCountStart() ;
//                            $serverData["sizeID"] = $this->getLoadBalancerGroupSizeID() ;
//                            $serverData["imageID"] = $this->getLoadBalancerGroupImageID() ;
                            $serverData["regionID"] = $this->getLoadBalancerGroupRegionID() ;
                            $serverData["name"] = (isset( $serverData["prefix"]) && strlen( $serverData["prefix"])>0)
                                ? $serverData["prefix"].'-'.$serverData["envName"].'-'.$serverData["sCount"]
                                : $serverData["envName"].'-'.$serverData["sCount"] ;
//                            $epn = $this->getEnablePrivateNetwork() ;
//                            if ($epn === true ) { $serverData["privateNetwork"] = true ; }
//                            $serverData["sshKeyIds"] = $this->getSshKeyIds();
                            $response = $this->getNewLoadBalancerFromDigitalOceanV2($serverData) ;
                            if ( isset($response->id) && $response->id == "unprocessable_entity") {
                                $logging->log("Load Balancer Request for {$serverData["name"]} failed", $this->getModuleName()) ;
                                return false ; }
                            else {
                                $this->addLoadBalancerToPapyrus($envName, $response); } } } } }
            return true ; }
        else {
            $logging->log("The environment $workingEnvironment does not exist.", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ; }
    }

    protected function addBalancerToPapyrus($envName, $data) {
        if (isset($data) && is_object($data)) {
            $balancer = array();
            $address = $data->virtualIps->current();
            $balancer["target"] = $address->address ;
            $balancer["provider"] = "Rackspace" ;
            $balancer["id"] = $data->id ;
            $balancer["name"] = $data->name ;
            $balancer["port"] = $data->port ;
            $balancer["protocol"] = $data->protocol ;
            $environments = \Model\AppConfig::getProjectVariable("environments");
            // file_put_contents("/tmp/outenv1", serialize($environments)) ;
            for ($i= 0 ; $i<count($environments); $i++) {
                if ($environments[$i]["any-app"]["gen_env_name"] == $envName) {
                    $environments[$i]["balancers"][] = $balancer; } }
            // file_put_contents("/tmp/outenv2", serialize($environments)) ;
            \Model\AppConfig::setProjectVariable("environments", $environments);
            return true ; }
        else {
            return false ; }
    }

}
