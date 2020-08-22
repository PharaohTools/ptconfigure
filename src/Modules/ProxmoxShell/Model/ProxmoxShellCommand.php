<?php

Namespace Model;

class ProxmoxShellCommand extends BaseProxmoxAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("ShellCommand") ;

    public function __construct($params) {
        parent::__construct($params) ;
    }

    public function askWhetherToListData($action) {
        return $this->performProxmoxShellCommand($action);
    }

    protected function performProxmoxShellCommand($action){
        if ($this->askForAPICommandExecute() != true) { return false; }
        $this->setCredentials() ;
        return $this->runAPICommand($action);
    }

    private function askForAPICommandExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Run Proxmox API Command?';
        return self::askYesOrNo($question);
    }

    public function runAPICommand($action){
        require_once (dirname(dirname(__DIR__))).DS.'Proxmox'.DS.'Libraries'.DS.'vendor'.DS.'autoload.php' ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        try {
            $proxmox = new \ProxmoxVE\Proxmox($this->credentials);
            $return_data = [] ;
            $available_actions = ['get', 'create', 'delete', 'ls', 'set'] ;
            $path = $this->params['path'] ;
            if (in_array($action, $available_actions)) {
                $data = $this->collateDataParams() ;
                var_dump("data is: ".var_export($data, true)) ;
                $result = $proxmox->$action($path, $data);
                echo var_export($result, true) ;
            }
        } catch (\Error $e) {
            $logging->log("Proxmox Error: {$e->getMessage()}", $this->getModuleName()) ;
            return false ;
        }
        return $return_data ;
    }

    protected function collateDataParams() {
        $data = [] ;
        foreach ($this->params as $key => $value) {
            if (substr($key, 0, 5) === 'data-') {
                $datakey = substr($key, 5) ;
                if ($value === "true") {
                    $data[$datakey] = true ;
                } else {
                    $data[$datakey] = $value ;
                }
            }
        }
        return $data ;
    }

}