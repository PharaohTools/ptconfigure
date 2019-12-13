<?php

Namespace Model;

class RoleExecutionAnyOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function performRoleExecution() {
        if ($this->askForRoleExecutionExecute() != true) {
            return false ;
        }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Roles", $this->getModuleName());

        $roles_dir = $this->getRolesDirectory() ;
        $role_path = $roles_dir.'roles.yml' ;
        $roles = $this->yamlParser($role_path) ;
        $roles = $roles[0] ;
//
        return $this->executeAllRoles($roles) ;
    }

    protected function getRoleObject($role) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Role Object", $this->getModuleName());
        $roles_dir = $this->getRolesDirectory() ;
        $config_path = $roles_dir.$role.DS.'config.yml' ;
        $config = $this->yamlParser($config_path) ;
        if (!isset($config->index)) {
            $role_path = $roles_dir.$role.DS.'index.dsl.yml' ;
        } else {
            $role_path = $roles_dir.$role.DS.$config->index ;
        }
        $logging->log("Using Role Index of $role_path", $this->getModuleName());
        $object = new \StdClass() ;
        $object->role_path = $role_path ;
        $object->vars = $config->vars ;
        return $object ;
    }


    protected function executeAllRoles($roles) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting Role Execution", $this->getModuleName());

        foreach ($roles as $role) {

            $role_object = $this->getRoleObject($role) ;
            $roles_dir = $this->getRolesDirectory() ;
            $full_role_dir = $roles_dir.$role ;

            if (!is_dir($full_role_dir)) {
                $logging->log("Unable to find role directory {$full_role_dir}", $this->getModuleName()) ;
                return false ;
            }

            $comm  = 'cd  '.getcwd().DS. ' && ' ;
            $comm .= 'ptconfigure auto x ' ;
            $comm .= '--af="'.$role_object->role_path.'" ' ;
            $comm .= ' --vars="'.implode(',', $role_object->vars).'" ;' ;

            $logging->log("Executing $comm", $this->getModuleName()) ;
//            $res = 0 ;
            $res = $this->liveOutput($comm) ;
            if ($res == 0) {
                $logging->log("Role Execution Successful", $this->getModuleName()) ;
            } else {
                $logging->log("Role Execution Failed", $this->getModuleName()) ;
                return false ;
            }
        }
        return true ;
    }

    public function yamlParser($file_path) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (function_exists('yaml_parse_file')) {
            $logging->log("Using PHP Native Yaml Parser", $this->getModuleName()) ;
            $unformatted = yaml_parse_file($file_path) ;
        } else {
            $logging->log("Using Yaml Parser Library", $this->getModuleName()) ;
            require dirname(__DIR__).DS.'Libraries'.DS.'Spyc.php' ;
            $unformatted = \Spyc::YAMLLoad($file_path) ;
        }
        return $unformatted ;
    }

    protected function liveOutput($comm) {
        require_once(dirname(__DIR__).DS.'Libraries'.DS.'vendor'.DS.'autoload.php') ;
        $process = new \Symfony\Component\Process\Process($comm);
        $process->setTimeout(0);
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo $data;
            } else { // $process::ERR === $type
                echo "ERR: ".$data;
            }
        }
        return $process->getExitCode();
    }

    protected function askForRoleExecutionExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Execute Roles?';
        return self::askYesOrNo($question);
    }

    protected function getRolesDirectory() {
        if (isset($this->params["role-dir"]) &&
            strlen($this->params["role-dir"])>0) {
                if (substr($this->params["role-dir"], -1, 1) !== DS) {
                    $this->params["role-dir"] .= DS ;
                }
                if (is_dir(getcwd().DS.$this->params["role-dir"])) {
                    return getcwd().DS.$this->params["role-dir"] ;
                }
                return $this->params["role-dir"] ;
        }
        return getcwd().DS.'roles'.DS ;
    }

    protected function getRoleFile() {
        if (isset($this->params["rolefile"])) { return $this->params["rolefile"] ; }
        return getcwd().DS.'roles.yml' ;
    }

}