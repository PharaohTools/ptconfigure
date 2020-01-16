<?php

Namespace Model;

class ProxmoxList extends BaseProxmoxAllOS {

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
        return $this->performProxmoxListData();
    }

    protected function performProxmoxListData(){
        if ($this->askForListExecute() != true) { return false; }
        $this->setCredentials() ;
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromProxmox($dataToList, array("per_page" =>100));
    }

    private function askForListExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    private function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        $options = array("virtual_machines", "sizes", "images", "ssh_keys");
        if (isset($this->params["type"]) &&
            in_array($this->params["type"], $options)) {
            return $this->params["type"] ; }
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromProxmox($dataToList, $callVars = array()){
//        if ($dataToList == "ssh_keys") {$dataToList = "account/keys";}
        require_once (dirname(__DIR__)).DS.'Libraries'.DS.'vendor'.DS.'autoload.php' ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        try {
            $proxmox = new \ProxmoxVE\Proxmox($this->credentials);
        } catch (\Error $e) {
            $logging->log("Proxmox Error: {$e->getMessage()}", $this->getModuleName()) ;
            return false ;
        }
        $return_data = [] ;
        if ($dataToList == 'virtual_machines') {
            $nodenames = array() ;
            try {
                $allNodes = $proxmox->get('/nodes');
            } catch (\Error $e) {
                $logging->log("Proxmox Error: {$e->getMessage()}", $this->getModuleName()) ;
                return false ;
            }
            foreach ($allNodes['data'] as $node) {
                $nodenames[] = $node['node'] ;
            }
            $return_data['vms_all_nodes'] = array() ;
            foreach ($nodenames as $nodename) {
                $vms_from_node = $proxmox->get('/nodes/'.$nodename.'/qemu');
                $return_data['vms_all_nodes'] = array_merge($return_data['vms_all_nodes'], $vms_from_node) ;
            }

            if ($this->params['output-format'] == 'JSON') {

            } else {

//            return $vms_all_nodes ;
                foreach ($return_data['vms_all_nodes'] as $one_vm) {
                    foreach ($one_vm as $one_vm_index => $one_vm_details) {
                        echo "\nVirtual Machine $one_vm_index:\n" ;
                        echo '  Name: '.$one_vm_details['name'] . "\n" ;
                        echo '  ID: '.$one_vm_details['vmid'] . "\n" ;
                        echo '  Status: '.$one_vm_details['status'] . "\n" ;
                        echo '  CPU Usage: '.$one_vm_details['cpu'] . "\n" ;
                        echo '  CPU Count: '.$one_vm_details['cpus'] . "\n" ;
                        echo '  Memory: '.$one_vm_details['mem'] . "\n" ;
                        echo '  Disk: '.$one_vm_details['disk'] . "\n" ;
                        echo '  Max Disk: '.$one_vm_details['maxdisk'] . "\n" ;
                        echo '  Network Input: '.$one_vm_details['netin'] . "\n" ;
                        echo '  Network Output: '.$one_vm_details['netout'] . "\n" ;
                        echo '  Disk Read: '.$one_vm_details['diskread'] . "\n" ;
                        echo '  Disk Write: '.$one_vm_details['diskwrite'] . "\n" ;
                        echo '  Max Memory: '.$one_vm_details['maxmem'] . "\n" ;
                        echo '  Uptime: '.$one_vm_details['uptime'] . "\n" ;
                        echo '  PID: '.$one_vm_details['pid'] . "\n" ;
                        echo '  Template: '.$one_vm_details['template'] . "\n" ;
//                    foreach ($one_vm_details as $one_vm_detail_key => $one_vm_detail_value) {
//                        echo "  $one_vm_detail_key: $one_vm_detail_value\n" ;
//                    }
                    }
                }
            }

        } else if ($dataToList == 'sizes') {

        } else if ($dataToList == 'images') {

        } else if ($dataToList == 'ssh_keys') {

        }

//        $curlUrl = $this->_apiURL."/v2/$dataToList" ;
//        $httpType = "GET" ;
//        return $this->proxmoxCall($callVars, $curlUrl, $httpType);
        return $return_data ;
    }

}